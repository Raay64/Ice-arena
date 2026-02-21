@extends('layouts.app')

@section('title', 'Оплаченные билеты')

@section('content')
    <div class="container" style="padding: 40px 0;">
        <div style="margin-bottom: 32px;">
            <h1 style="color: #0f172a; margin-bottom: 8px;">Оплаченные билеты</h1>
            <p style="color: #64748b;">История всех оплаченных бронирований</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 32px;">
            <div class="stat-card">
                <div class="stat-value">{{ $paidBookings->total() }}</div>
                <div class="stat-label">Всего билетов</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $paidBookings->sum('total_amount') }} ₽</div>
                <div class="stat-label">Общая выручка</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $paidBookings->where('has_skates', true)->count() }}</div>
                <div class="stat-label">С арендой коньков</div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom: 24px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>ФИО</th>
                    <th>Телефон</th>
                    <th>Дата покупки</th>
                    <th>Коньки</th>
                    <th>Сумма</th>
                    <th>ID платежа</th>
                </tr>
                </thead>
                <tbody>
                @forelse($paidBookings as $booking)
                    <tr>
                        <td>#{{ $booking->id }}</td>
                        <td>
                            <div style="font-weight: 500;">{{ $booking->fio }}</div>
                        </td>
                        <td>{{ $booking->phone }}</td>
                        <td>{{ $booking->updated_at->format('d.m.Y H:i') }}</td>
                        <td>
                            @if($booking->has_skates && $booking->skate)
                                <span style="color: #059669;">{{ $booking->skate->model }} (р-р {{ $booking->skate->size }})</span>
                            @else
                                <span style="color: #64748b;">Свои коньки</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight: 500; color: #059669;">{{ number_format($booking->total_amount, 0, '.', ' ') }} ₽</div>
                        </td>
                        <td>
                            <div style="font-family: monospace; font-size: 12px; color: #64748b;">
                                {{ substr($booking->payment_id, 0, 8) }}...
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 48px;">
                            <div style="font-size: 48px; margin-bottom: 16px;">🎫</div>
                            <h3 style="color: #0f172a; margin-bottom: 8px;">Нет оплаченных билетов</h3>
                            <p style="color: #64748b;">Пока никто не оплатил бронирование</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            @if($paidBookings->hasPages())
                <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                    {{ $paidBookings->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
