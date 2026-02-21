@extends('layouts.app')

@section('title', 'Бронирования')

@section('content')
    <div class="container" style="padding: 40px 0;">
        <div style="margin-bottom: 32px;">
            <h1 style="color: #0f172a; margin-bottom: 8px;">Бронирования</h1>
            <p style="color: #64748b;">Список всех бронирований на каток</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom: 24px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filters -->
        <div style="display: flex; gap: 16px; margin-bottom: 24px;">
            <a href="{{ route('admin.bookings') }}"
               style="padding: 8px 16px; border-radius: 8px; text-decoration: none; {{ !request('filter') ? 'background: #2563eb; color: white;' : 'background: white; color: #64748b;' }}">
                Все
            </a>
            <a href="{{ route('admin.bookings', ['filter' => 'paid']) }}"
               style="padding: 8px 16px; border-radius: 8px; text-decoration: none; {{ request('filter') == 'paid' ? 'background: #2563eb; color: white;' : 'background: white; color: #64748b;' }}">
                Оплаченные
            </a>
            <a href="{{ route('admin.bookings', ['filter' => 'unpaid']) }}"
               style="padding: 8px 16px; border-radius: 8px; text-decoration: none; {{ request('filter') == 'unpaid' ? 'background: #2563eb; color: white;' : 'background: white; color: #64748b;' }}">
                Неоплаченные
            </a>
        </div>

        <div class="card">
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>ФИО</th>
                    <th>Телефон</th>
                    <th>Часы</th>
                    <th>Коньки</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                    <th>Дата</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>#{{ $booking->id }}</td>
                        <td>
                            <div style="font-weight: 500;">{{ $booking->fio }}</div>
                        </td>
                        <td>{{ $booking->phone }}</td>
                        <td>{{ $booking->hours }} ч</td>
                        <td>
                            @if($booking->has_skates && $booking->skate)
                                <div style="display: flex; flex-direction: column;">
                                    <span>{{ $booking->skate->model }}</span>
                                    <small style="color: #64748b;">размер {{ $booking->skate->size }}</small>
                                </div>
                            @else
                                <span style="color: #64748b;">Свои коньки</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight: 500;">{{ number_format($booking->total_amount, 0, '.', ' ') }} ₽</div>
                        </td>
                        <td>
                            @if($booking->is_paid)
                                <span style="background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                                Оплачено
                            </span>
                            @else
                                <span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                                Не оплачено
                            </span>
                            @endif
                        </td>
                        <td>{{ $booking->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-secondary" style="padding: 6px 12px;">
                                Детали
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 48px;">
                            <div style="font-size: 48px; margin-bottom: 16px;">📋</div>
                            <h3 style="color: #0f172a; margin-bottom: 8px;">Бронирований нет</h3>
                            <p style="color: #64748b;">Пока никто не забронировал каток</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            @if($bookings->hasPages())
                <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
