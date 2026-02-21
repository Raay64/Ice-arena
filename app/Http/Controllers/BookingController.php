<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Skate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use YooKassa\Client;
use YooKassa\Model\Payment\PaymentStatus;

class BookingController extends Controller
{
    private function getYooKassaClient(): Client
    {
        $shopId = config('yookassa.shop_id') ?: env('YOOKASSA_SHOP_ID');
        $secretKey = config('yookassa.secret_key') ?: env('YOOKASSA_SECRET_KEY');

        if (empty($shopId) || empty($secretKey)) {
            throw new \Exception('Настройки ЮKassa не сконфигурированы');
        }

        $client = new Client();
        $client->setAuth($shopId, $secretKey);
        return $client;
    }

    public function store(Request $request)
    {
        Log::info('Booking store started', $request->all());

        try {
            $validated = $request->validate([
                'fio' => 'required|string|max:255',
                'phone' => 'required|string|max:18',
                'hours' => 'required|in:1,2,3,4',
                'skate_id' => 'nullable|exists:skates,id'
            ]);

            $phone = preg_replace('/[^0-9]/', '', $validated['phone']);
            if (strlen($phone) === 11) {
                $phoneForYooKassa = $phone;
            } elseif (strlen($phone) === 10) {
                $phoneForYooKassa = '7' . $phone;
            } else {
                $phoneForYooKassa = $phone;
            }

            $ticketPrice = 300;
            $skatePrice = 150;
            $totalAmount = $ticketPrice;

            $hasSkates = $request->has('needSkates') && $request->filled('skate_id');
            if ($hasSkates) {
                $totalAmount += $skatePrice * $request->hours;
            }

            $booking = Booking::create([
                'fio' => $validated['fio'],
                'phone' => $validated['phone'],
                'hours' => $validated['hours'],
                'skate_id' => $hasSkates ? $request->skate_id : null,
                'total_amount' => $totalAmount,
                'has_skates' => $hasSkates,
                'status' => 'pending',
                'is_paid' => false
            ]);

            Log::info('Booking created', ['booking_id' => $booking->id]);

            try {
                $client = $this->getYooKassaClient();

                $paymentData = [
                    'amount' => [
                        'value' => number_format($totalAmount, 2, '.', ''),
                        'currency' => 'RUB',
                    ],
                    'confirmation' => [
                        'type' => 'redirect',
                        'return_url' => route('payment.success', $booking->id),
                    ],
                    'capture' => true,
                    'description' => "Бронирование катка #{$booking->id}",
                    'metadata' => [
                        'booking_id' => $booking->id,
                    ],
                ];

                // Добавляем чек если есть телефон
                if (!empty($phoneForYooKassa) && strlen($phoneForYooKassa) >= 10) {
                    $items = [
                        [
                            'description' => 'Входной билет на каток',
                            'quantity' => '1.00',
                            'amount' => [
                                'value' => number_format($ticketPrice, 2, '.', ''),
                                'currency' => 'RUB',
                            ],
                            'vat_code' => 1,
                            'payment_mode' => 'full_prepayment',
                            'payment_subject' => 'service',
                        ]
                    ];

                    if ($hasSkates) {
                        $items[] = [
                            'description' => "Аренда коньков ({$request->hours} ч)",
                            'quantity' => '1.00',
                            'amount' => [
                                'value' => number_format($skatePrice * $request->hours, 2, '.', ''),
                                'currency' => 'RUB',
                            ],
                            'vat_code' => 1,
                            'payment_mode' => 'full_prepayment',
                            'payment_subject' => 'service',
                        ];
                    }

                    $paymentData['receipt'] = [
                        'customer' => [
                            'full_name' => $validated['fio'],
                            'phone' => $phoneForYooKassa,
                        ],
                        'items' => $items
                    ];
                }

                $payment = $client->createPayment(
                    $paymentData,
                    uniqid('booking_', true)
                );

                $booking->update([
                    'payment_id' => $payment->getId(),
                    'payment_url' => $payment->getConfirmation()->getConfirmationUrl(),
                ]);

                Log::info('Payment created', [
                    'booking_id' => $booking->id,
                    'payment_id' => $payment->getId()
                ]);

                return redirect($payment->getConfirmation()->getConfirmationUrl());

            } catch (\Exception $e) {
                Log::error('YooKassa error', [
                    'error' => $e->getMessage(),
                    'booking_id' => $booking->id
                ]);

                $booking->update(['status' => 'failed']);
                return back()->with('error', 'Ошибка при создании платежа: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            Log::error('Validation error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Ошибка валидации: ' . $e->getMessage());
        }
    }

    public function success(Booking $booking)
    {
        Log::info('Payment success page accessed', [
            'booking_id' => $booking->id,
            'current_status' => $booking->status,
            'is_paid' => $booking->is_paid
        ]);

        if ($booking->is_paid && $booking->status === 'paid') {
            return view('payment.success', compact('booking'));
        }

        if ($booking->payment_id) {
            try {
                $client = $this->getYooKassaClient();
                $payment = $client->getPaymentInfo($booking->payment_id);

                $paymentStatus = $payment->getStatus();
                Log::info('Payment status from YooKassa', [
                    'booking_id' => $booking->id,
                    'status' => $paymentStatus
                ]);

                if ($paymentStatus === PaymentStatus::SUCCEEDED) {
                    return $this->markAsPaid($booking);
                } elseif ($paymentStatus === PaymentStatus::CANCELED) {
                    $booking->update(['status' => 'failed']);
                    return redirect()->route('payment.cancel', $booking);
                } else {
                    return view('payment.pending', compact('booking'));
                }
            } catch (\Exception $e) {
                Log::error('Error checking payment status', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage()
                ]);
                return view('payment.pending', compact('booking'));
            }
        }

        return redirect()->route('home')->with('error', 'Платеж не найден');
    }

    private function markAsPaid(Booking $booking)
    {
        Log::info('Marking booking as paid', ['booking_id' => $booking->id]);

        if ($booking->has_skates && $booking->skate_id) {
            $skate = Skate::find($booking->skate_id);
            if ($skate && $skate->quantity > 0) {
                $skate->decrement('quantity');
                Log::info('Skates quantity decreased', [
                    'skate_id' => $skate->id,
                    'new_quantity' => $skate->quantity
                ]);
            }
        }

        $booking->update([
            'status' => 'paid',
            'is_paid' => true,
            'paid_at' => now(),
        ]);

        Log::info('Booking marked as paid', [
            'booking_id' => $booking->id,
            'status' => $booking->status,
            'is_paid' => $booking->is_paid
        ]);

        return view('payment.success', compact('booking'));
    }

    public function cancel(Booking $booking, Request $request)
    {
        $timeout = $request->get('timeout', false);

        Log::info('Payment cancelled', [
            'booking_id' => $booking->id,
            'reason' => $timeout ? 'timeout' : 'user_cancelled'
        ]);

        $booking->update([
            'status' => 'failed',
            'is_paid' => false
        ]);

        if ($booking->payment_id && !$timeout) {
            try {
                $client = $this->getYooKassaClient();
            } catch (\Exception $e) {
                Log::error('Failed to cancel payment', ['error' => $e->getMessage()]);
            }
        }

        return view('payment.cancel', [
            'booking' => $booking,
            'timeout' => $timeout
        ]);
    }

    public function checkStatus(Booking $booking)
    {
        try {
            if (empty($booking->payment_id)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No payment ID'
                ]);
            }

            $client = $this->getYooKassaClient();
            $payment = $client->getPaymentInfo($booking->payment_id);

            $status = $payment->getStatus();

            $response = [
                'status' => $status,
                'paid' => $status === PaymentStatus::SUCCEEDED
            ];

            if ($status === PaymentStatus::SUCCEEDED && !$booking->is_paid) {
                $this->markAsPaid($booking);
                $response['updated'] = true;
            }

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Check status error', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function webhook(Request $request)
    {
        $data = $request->all();
        Log::info('YooKassa webhook', $data);

        if (isset($data['object']['id'])) {
            $paymentId = $data['object']['id'];
            $status = $data['object']['status'] ?? null;

            $booking = Booking::where('payment_id', $paymentId)->first();

            if ($booking) {
                if ($status === 'succeeded') {
                    $this->markAsPaid($booking);
                } elseif ($status === 'canceled') {
                    $booking->update([
                        'status' => 'failed',
                        'is_paid' => false
                    ]);
                }
            }
        }

        return response('OK', 200);
    }
}
