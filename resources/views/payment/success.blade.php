<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оплата успешна</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f0f4f8;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            width: 100%;
        }

        .card {
            background: white;
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            text-align: center;
        }

        .success-icon {
            width: 100px;
            height: 100px;
            background: #d1fae5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 32px;
            font-size: 50px;
            color: #059669;
            animation: scaleIn 0.5s ease;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        h1 {
            color: #0f172a;
            font-size: 32px;
            margin-bottom: 16px;
        }

        .description {
            color: #64748b;
            margin-bottom: 32px;
            font-size: 18px;
        }

        .ticket {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border-radius: 20px;
            padding: 32px;
            color: white;
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
        }

        .ticket::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
            pointer-events: none;
        }

        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 2px dashed rgba(255,255,255,0.3);
        }

        .ticket-title {
            font-size: 24px;
            font-weight: 600;
        }

        .ticket-number {
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 14px;
        }

        .ticket-info {
            display: grid;
            gap: 16px;
            text-align: left;
        }

        .ticket-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ticket-label {
            opacity: 0.9;
            font-size: 14px;
        }

        .ticket-value {
            font-weight: 600;
            font-size: 18px;
        }

        .ticket-total {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 2px dashed rgba(255,255,255,0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 20px;
            font-weight: 700;
        }

        .info-block {
            background: #f8fafc;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
            text-align: left;
            border: 1px solid #e2e8f0;
        }

        .info-title {
            color: #0f172a;
            font-weight: 600;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .qr-placeholder {
            width: 120px;
            height: 120px;
            background: #f1f5f9;
            border-radius: 16px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
        }

        .btn {
            display: inline-block;
            padding: 14px 32px;
            border-radius: 10px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 16px;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            width: 100%;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #334155;
        }

        .btn-secondary:hover {
            background: #cbd5e1;
        }

        .actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 24px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="success-icon">✓</div>

        <h1>Оплата прошла успешно!</h1>
        <p class="description">Спасибо за бронирование. Ваш билет уже ждет вас.</p>

        <!-- Билет -->
        <div class="ticket">
            <div class="ticket-header">
                <span class="ticket-title">ICE ARENA</span>
                <span class="ticket-number">#{{ $booking->id }}</span>
            </div>

            <div class="ticket-info">
                <div class="ticket-row">
                    <span class="ticket-label">ФИО:</span>
                    <span class="ticket-value">{{ $booking->fio }}</span>
                </div>
                <div class="ticket-row">
                    <span class="ticket-label">Телефон:</span>
                    <span class="ticket-value">{{ $booking->phone }}</span>
                </div>
                <div class="ticket-row">
                    <span class="ticket-label">Время катания:</span>
                    <span class="ticket-value">{{ $booking->hours }} ч</span>
                </div>
                @if($booking->has_skates)
                    <div class="ticket-row">
                        <span class="ticket-label">Коньки:</span>
                        <span class="ticket-value">{{ $booking->skate->model ?? 'Не указано' }} (р-р {{ $booking->skate->size ?? '?' }})</span>
                    </div>
                @endif
            </div>

            <div class="ticket-total">
                <span>Итого оплачено:</span>
                <span>{{ number_format($booking->total_amount, 0, '.', ' ') }} ₽</span>
            </div>
        </div>

        <!-- Детали -->
        <div class="info-block">
            <div class="info-title">
                <span>📋</span> Детали бронирования
            </div>
            <div style="display: grid; gap: 12px;">
                <div style="display: flex; justify-content: space-between; color: #334155;">
                    <span>Дата бронирования:</span>
                    <span style="font-weight: 500;">{{ $booking->created_at->format('d.m.Y H:i') }}</span>
                </div>
                @if($booking->paid_at)
                    <div style="display: flex; justify-content: space-between; color: #334155;">
                        <span>Дата оплаты:</span>
                        <span style="font-weight: 500;">{{ $booking->paid_at->format('d.m.Y H:i') }}</span>
                    </div>
                @endif
                @if($booking->payment_id)
                    <div style="display: flex; justify-content: space-between; color: #334155;">
                        <span>ID платежа:</span>
                        <span style="font-family: monospace; font-size: 14px;">{{ substr($booking->payment_id, 0, 16) }}...</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- QR код (заглушка) -->
        <div class="qr-placeholder">
            🎫
        </div>
        <p style="color: #64748b; font-size: 14px; margin-top: 8px; margin-bottom: 24px;">
            Покажите этот билет на входе
        </p>

        <!-- Кнопки действий -->
        <div class="actions">
            <button onclick="window.print()" class="btn btn-secondary">
                🖨️ Распечатать
            </button>
            <a href="{{ route('home') }}" class="btn btn-primary">
                На главную
            </a>
        </div>

        <p style="margin-top: 24px; color: #94a3b8; font-size: 14px;">
            Ждем вас на нашем катке! Не забудьте взять с собой хорошее настроение 😊
        </p>
    </div>
</div>

<script>
    // Автоматическая печать через 1 секунду
    setTimeout(() => {
        if (confirm('Хотите распечатать билет?')) {
            window.print();
        }
    }, 1000);
</script>
</body>
</html>
