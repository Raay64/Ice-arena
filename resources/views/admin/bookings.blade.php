@extends('layouts.app')

@section('content')
    <div class="container" style="padding: 40px 0;">
        <h1 style="color: white; margin-bottom: 32px;">Бронирования</h1>

        <div class="card">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                <tr style="border-bottom: 2px solid #e0e0e0;">
                    <th style="padding: 12px; text-align: left;">ID</th>
                    <th style="padding: 12px; text-align: left;">ФИО</th>
                    <th style="padding: 12px; text-align: left;">Телефон</th>
                    <th style="padding: 12px; text-align: left;">Часы</th>
                    <th style="padding: 12px; text-align: left;">Коньки</th>
                    <th style="padding: 12px; text-align: left;">Сумма</th>
                    <th style="padding: 12px; text-align: left;">Статус</th>
                    <th style="padding: 12px; text-align: left;">Дата</th>
                    <th style="padding: 12px; text-align: left;"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($bookings as $booking)
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 12px;">{{ $booking->id }}</td>
                        <td style="padding: 12px;">{{ $booking->fio }}</td>
                        <td style="padding: 12px;">{{ $booking->phone }}</td>
                        <td style="padding: 12px;">{{ $booking->hours }} ч</td>
                        <td style="padding: 12px;">
                            @if($booking->has_skates)
                                {{ $booking->skate->model ?? 'Не указано' }} (р-р {{ $booking->skate->size ?? '?' }})
                            @else
                                Свои
                            @endif
                        </td>
                        <td style="padding: 12px;">{{ $booking->total_amount }} ₽</td>
                        <td style="padding: 12px;">
                            @if($booking->is_paid)
                                <span style="color: #28a745;">Оплачено</span>
                            @else
                                <span style="color: #dc3545;">Не оплачено</span>
                            @endif
                        </td>
                        <td style="padding: 12px;">{{ $booking->created_at->format('d.m.Y H:i') }}</td>
                        <td style="padding: 12px;">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-outline" style="padding: 4px 8px;">Детали</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div style="margin-top: 24px;">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
@endsection
