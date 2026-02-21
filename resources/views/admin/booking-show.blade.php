@extends('layouts.app')

@section('content')
    <div class="container" style="padding: 40px 0;">
        <div style="margin-bottom: 32px;">
            <a href="{{ route('admin.bookings') }}" style="color: white; text-decoration: none;">← Назад к списку</a>
        </div>

        <div class="card" style="max-width: 600px; margin: 0 auto;">
            <h2 style="margin-bottom: 24px;">Детали бронирования #{{ $booking->id }}</h2>

            <div style="margin-bottom: 16px;">
                <strong>ФИО:</strong> {{ $booking->fio }}
            </div>

            <div style="margin-bottom: 16px;">
                <strong>Телефон:</strong> {{ $booking->phone }}
            </div>

            <div style="margin-bottom: 16px;">
                <strong>Часов аренды:</strong> {{ $booking->hours }}
            </div>

            <div style="margin-bottom: 16px;">
                <strong>Коньки:</strong>
                @if($booking->has_skates && $booking->skate)
                    {{ $booking->skate->model }} (размер {{ $booking->skate->size }})
                @else
                    Свои коньки
                @endif
            </div>

            <div style="margin-bottom: 16px;">
                <strong>Сумма:</strong> {{ $booking->total_amount }} ₽
            </div>

            <div style="margin-bottom: 16px;">
                <strong>Статус оплаты:</strong>
                @if($booking->is_paid)
                    <span style="color: #28a745;">Оплачено</span>
                @else
                    <span style="color: #dc3545;">Не оплачено</span>
                @endif
            </div>

            @if($booking->payment_id)
                <div style="margin-bottom: 16px;">
                    <strong>ID платежа:</strong> {{ $booking->payment_id }}
                </div>
            @endif

            <div style="margin-bottom: 16px;">
                <strong>Дата создания:</strong> {{ $booking->created_at->format('d.m.Y H:i') }}
            </div>

            @if($booking->is_paid)
                <div style="margin-bottom: 16px;">
                    <strong>Дата оплаты:</strong> {{ $booking->updated_at->format('d.m.Y H:i') }}
                </div>
            @endif
        </div>
    </div>
@endsection
