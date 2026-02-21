@extends('layouts.app')

@section('content')
    <div class="container" style="padding: 40px 0;">
        <h1 style="color: white; margin-bottom: 32px;">Оплаченные билеты</h1>

        <div class="card">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                <tr style="border-bottom: 2px solid #e0e0e0;">
                    <th style="padding: 12px; text-align: left;">ID</th>
                    <th style="padding: 12px; text-align: left;">ФИО</th>
                    <th style="padding: 12px; text-align: left;">Телефон</th>
                    <th style="padding: 12px; text-align: left;">Сумма</th>
                    <th style="padding: 12px; text-align: left;">ID платежа</th>
                    <th style="padding: 12px; text-align: left;">Дата оплаты</th>
                </tr>
                </thead>
                <tbody>
                @forelse($paidBookings as $booking)
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 12px;">{{ $booking->id }}</td>
                        <td style="padding: 12px;">{{ $booking->fio }}</td>
                        <td style="padding: 12px;">{{ $booking->phone }}</td>
                        <td style="padding: 12px;">{{ $booking->total_amount }} ₽</td>
                        <td style="padding: 12px;">{{ $booking->payment_id }}</td>
                        <td style="padding: 12px;">{{ $booking->updated_at->format('d.m.Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 24px; text-align: center; color: #666;">
                            Нет оплаченных билетов
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div style="margin-top: 24px;">
                {{ $paidBookings->links() }}
            </div>
        </div>
    </div>
@endsection
