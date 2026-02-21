@extends('layouts.app')

@section('title', 'Админ-панель')

@section('content')
    <div class="container" style="padding: 40px 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
            <h1 style="color: #0f172a;">Админ-панель</h1>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-secondary">Выйти</button>
            </form>
        </div>

        <div class="grid grid-4" style="margin-bottom: 40px;">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total_bookings'] }}</div>
                <div class="stat-label">Всего бронирований</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['paid_bookings'] }}</div>
                <div class="stat-label">Оплаченных</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total_skates'] }}</div>
                <div class="stat-label">Коньков в наличии</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['today_bookings'] }}</div>
                <div class="stat-label">За сегодня</div>
            </div>
        </div>

        <div class="grid grid-3" style="gap: 20px;">
            <a href="{{ route('admin.skates') }}" style="text-decoration: none;">
                <div class="card" style="text-align: center; padding: 40px;">
                    <div style="font-size: 48px; margin-bottom: 16px;">⛸️</div>
                    <h3 style="color: #0f172a; margin-bottom: 8px;">Управление коньками</h3>
                    <p style="color: #64748b;">Добавление, редактирование, удаление</p>
                </div>
            </a>

            <a href="{{ route('admin.bookings') }}" style="text-decoration: none;">
                <div class="card" style="text-align: center; padding: 40px;">
                    <div style="font-size: 48px; margin-bottom: 16px;">📋</div>
                    <h3 style="color: #0f172a; margin-bottom: 8px;">Бронирования</h3>
                    <p style="color: #64748b;">Просмотр всех бронирований</p>
                </div>
            </a>

            <a href="{{ route('admin.tickets') }}" style="text-decoration: none;">
                <div class="card" style="text-align: center; padding: 40px;">
                    <div style="font-size: 48px; margin-bottom: 16px;">🎫</div>
                    <h3 style="color: #0f172a; margin-bottom: 8px;">Оплаченные билеты</h3>
                    <p style="color: #64748b;">Список оплаченных билетов</p>
                </div>
            </a>
        </div>
    </div>
@endsection
