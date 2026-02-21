<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Skate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use YooKassa\Client;

class BookingController extends Controller
{
    private function getYooKassaClient(): Client
    {
        $shopId = config('yookassa.shop_id') ?: env('YOOKASSA_SHOP_ID');
        $secretKey = config('yookassa.secret_key') ?: env('YOOKASSA_SECRET_KEY');

        Log::info('YooKassa config', [
            'shop_id' => $shopId ? 'set' : 'not set',
            'secret_key' => $secretKey ? 'set' : 'not set'
        ]);

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

            Log::info('Validation passed', $validated);

            // Очищаем телефон от всех символов кроме цифр
            $phone = preg_replace('/[^0-9]/', '', $validated['phone']);

            // Приводим к формату, который принимает ЮKassa (только цифры)
            // ЮKassa принимает телефон в формате: 79001234567 (11 цифр)
            if (strlen($phone) === 11) {
                $phoneForYooKassa = $phone;
            } elseif (strlen($phone) === 10) {
                // Если без 7, добавляем
                $phoneForYooKassa = '7' . $phone;
            } else {
                // Если какой-то другой формат, оставляем как есть, но без символов
                $phoneForYooKassa = $phone;
            }

            Log::info('Phone formatted', [
                'original' => $validated['phone'],
                'cleaned' => $phone,
                'for_yookassa' => $phoneForYooKassa
            ]);

            // Расчет стоимости
            $ticketPrice = 300;
            $skatePrice = 150;
            $totalAmount = $ticketPrice;

            $hasSkates = false;
            if ($request->filled('skate_id')) {
                $totalAmount += $skatePrice * $request->hours;
                $hasSkates = true;
            }

            Log::info('Price calculated', [
                'total' => $totalAmount,
                'has_skates' => $hasSkates
            ]);

            // Создание бронирования
            $booking = Booking::create([
                'fio' => $validated['fio'],
                'phone' => $validated['phone'], // Сохраняем как ввел пользователь
                'hours' => $validated['hours'],
                'skate_id' => $request->skate_id,
                'total_amount' => $totalAmount,
                'has_skates' => $hasSkates,
                'status' => 'pending'
            ]);

            Log::info('Booking created', ['booking_id' => $booking->id]);

            try {
                $client = $this->getYooKassaClient();

                // Формируем описание товаров для чека
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

                // Подготавливаем данные клиента для чека
                $customerData = [
                    'full_name' => $validated['fio'],
                ];

                // Добавляем телефон только если он прошел валидацию
                // ЮKassa требует либо phone, либо email
                if (!empty($phoneForYooKassa) && strlen($phoneForYooKassa) >= 10) {
                    $customerData['phone'] = $phoneForYooKassa;
                } else {
                    // Если телефон не прошел, используем заглушку email
                    $customerData['email'] = 'customer@ice-arena.ru';
                    Log::warning('Using fallback email for receipt', [
                        'original_phone' => $validated['phone']
                    ]);
                }

                // Формируем чек
                $receiptData = [
                    'customer' => $customerData,
                    'items' => $items
                ];

                Log::info('Creating payment with receipt', [
                    'customer' => $customerData,
                    'amount' => number_format($totalAmount, 2, '.', ''),
                ]);

                // Создаем платеж с чеком
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

                // Добавляем чек только если есть телефон или email
                if (isset($customerData['phone']) || isset($customerData['email'])) {
                    $paymentData['receipt'] = $receiptData;
                }

                $payment = $client->createPayment(
                    $paymentData,
                    uniqid('booking_', true)
                );

                Log::info('Payment created successfully', [
                    'payment_id' => $payment->getId(),
                    'confirmation_url' => $payment->getConfirmation()->getConfirmationUrl()
                ]);

                // Сохраняем данные платежа
                $booking->update([
                    'payment_id' => $payment->getId(),
                    'payment_url' => $payment->getConfirmation()->getConfirmationUrl(),
                ]);

                return redirect($payment->getConfirmation()->getConfirmationUrl());

            } catch (\Exception $e) {
                Log::error('YooKassa payment creation failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                $booking->update(['status' => 'failed']);

                return back()->with('error', 'Ошибка при создании платежа: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            Log::error('Booking store failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Произошла ошибка: ' . $e->getMessage());
        }
    }

    public function success(Booking $booking)
    {
        Log::info('Payment success page accessed', [
            'booking_id' => $booking->id,
            'status' => $booking->status
        ]);

        return view('payment.success', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        Log::info('Payment cancelled', ['booking_id' => $booking->id]);

        $booking->update(['status' => 'failed']);
        return view('payment.cancel', compact('booking'));
    }

    public function checkStatus(Booking $booking)
    {
        Log::info('Checking payment status', ['booking_id' => $booking->id]);

        try {
            if (empty($booking->payment_id)) {
                return response()->json(['status' => 'no_payment']);
            }

            $client = $this->getYooKassaClient();
            $payment = $client->getPaymentInfo($booking->payment_id);

            $status = $payment->getStatus();
            Log::info('Payment status from YooKassa', [
                'booking_id' => $booking->id,
                'status' => $status
            ]);

            if ($status === 'succeeded' && $booking->status !== 'paid') {
                if ($booking->has_skates && $booking->skate_id) {
                    $skate = Skate::find($booking->skate_id);
                    if ($skate && $skate->quantity > 0) {
                        $skate->decrement('quantity');
                    }
                }

                $booking->update([
                    'status' => 'paid',
                    'is_paid' => true,
                    'paid_at' => now(),
                ]);
            }

            return response()->json([
                'status' => $status,
                'paid' => $status === 'succeeded'
            ]);

        } catch (\Exception $e) {
            Log::error('Status check failed', [
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
