@extends('layouts.app')

@section('title', 'Детали бронирования #' . $booking->id)

@section('content')
    <div class="container" style="padding: 40px 0; max-width: 800px;">
        <div style="margin-bottom: 32px;">
            <a href="{{ route('admin.bookings') }}" style="color: #64748b; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                ← Назад к списку бронирований
            </a>
        </div>

        <div style="display: grid; gap: 24px;">
            <!-- Header -->
            <div class="card" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="color: #0f172a; margin-bottom: 4px;">Бронирование #{{ $booking->id }}</h2>
                    <p style="color: #64748b;">Создано {{ $booking->created_at->format('d.m.Y в H:i') }}</p>
                </div>
                <div>
                    @if($booking->is_paid)
                        <span style="background: #d1fae5; color: #065f46; padding: 8px 16px; border-radius: 30px; font-weight: 500;">
                        ✓ Оплачено
                    </span>
                    @else
                        <span style="background: #fee2e2; color: #991b1b; padding: 8px 16px; border-radius: 30px; font-weight: 500;">
                        ✗ Ожидает оплаты
                    </span>
                    @endif
                </div>
            </div>

            <!-- Client info -->
            <div class="card">
                <h3 style="color: #0f172a; margin-bottom: 20px; font-size: 18px;">Информация о клиенте</h3>
                <div style="display: grid; gap: 16px;">
                    <div style="display: flex;">
                        <div style="width: 120px; color: #64748b;">ФИО:</div>
                        <div style="color: #0f172a; font-weight: 500;">{{ $booking->fio }}</div>
                    </div>
                    <div style="display: flex;">
                        <div style="width: 120px; color: #64748b;">Телефон:</div>
                        <div style="color: #0f172a; font-weight: 500;">{{ $booking->phone }}</div>
                    </div>
                </div>
            </div>

            <!-- Order details -->
            <div class="card">
                <h3 style="color: #0f172a; margin-bottom: 20px; font-size: 18px;">Детали заказа</h3>
                <div style="display: grid; gap: 16px;">
                    <div style="display: flex;">
                        <div style="width: 120px; color: #64748b;">Часов аренды:</div>
                        <div style="color: #0f172a; font-weight: 500;">{{ $booking->hours }} ч</div>
                    </div>
                    <div style="display: flex;">
                        <div style="width: 120px; color: #64748b;">Коньки:</div>
                        <div>
                            @if($booking->has_skates && $booking->skate)
                                <div style="color: #0f172a; font-weight: 500;">{{ $booking->skate->model }}</div>
                                <div style="color: #64748b; font-size: 14px;">Размер {{ $booking->skate->size }}</div>
                            @else
                                <span style="color: #64748b;">Свои коньки</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment details -->
            <div class="card">
                <h3 style="color: #0f172a; margin-bottom: 20px; font-size: 18px;">Информация об оплате</h3>
                <div style="display: grid; gap: 16px;">
                    <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                        <span style="color: #64748b;">Входной билет:</span>
                        <span style="color: #0f172a; font-weight: 500;">300 ₽</span>
                    </div>

                    @if($booking->has_skates)
                        <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                            <span style="color: #64748b;">Аренда коньков ({{ $booking->hours }} ч):</span>
                            <span style="color: #0f172a; font-weight: 500;">{{ 150 * $booking->hours }} ₽</span>
                        </div>
                    @endif

                    <div style="display: flex; justify-content: space-between; padding-top: 8px;">
                        <span style="color: #0f172a; font-weight: 600;">Итого:</span>
                        <span style="color: #2563eb; font-weight: 700; font-size: 20px;">{{ number_format($booking->total_amount, 0, '.', ' ') }} ₽</span>
                    </div>

                    @if($booking->payment_id)
                        <div style="margin-top: 16px; padding: 16px; background: #f8fafc; border-radius: 8px;">
                            <div style="font-size: 14px; color: #64748b;">ID платежа:</div>
                            <div style="color: #0f172a; font-family: monospace;">{{ $booking->payment_id }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div style="display: flex; gap: 16px;">
                <a href="{{ route('admin.bookings') }}" class="btn btn-secondary" style="flex: 1;">
                    Вернуться к списку
                </a>
                <button onclick="window.print()" class="btn btn-primary" style="flex: 1;">
                    🖨️ Распечатать
                </button>
            </div>
        </div>
    </div>
@endsection
