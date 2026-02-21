<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оплата успешна - билет #{{ $booking->id }}</title>
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

        /* Билет для печати */
        .ticket {
            background: white;
            border: 2px solid #2563eb;
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 32px;
            position: relative;
            text-align: left;
        }

        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 2px dashed #e2e8f0;
        }

        .ticket-logo {
            font-size: 24px;
            font-weight: 700;
            color: #2563eb;
        }

        .ticket-number {
            background: #f1f5f9;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 14px;
            color: #475569;
            font-weight: 500;
        }

        .ticket-info {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 24px;
        }

        .info-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-label {
            width: 120px;
            color: #64748b;
            font-weight: 500;
        }

        .info-value {
            color: #0f172a;
            font-weight: 600;
            flex: 1;
        }

        .skates-details {
            background: #f8fafc;
            padding: 16px;
            border-radius: 12px;
            margin: 16px 0;
        }

        .skates-details p {
            margin: 4px 0;
            color: #334155;
        }

        .ticket-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 24px;
            border-top: 2px solid #e2e8f0;
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
        }

        .total-price {
            color: #2563eb;
            font-size: 24px;
        }

        .qr-placeholder {
            width: 120px;
            height: 120px;
            background: #f1f5f9;
            border-radius: 16px;
            margin: 20px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            border: 2px dashed #cbd5e1;
        }

        .actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 24px;
        }

        .btn {
            display: inline-block;
            padding: 14px 32px;
            border-radius: 10px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
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

        .note {
            margin-top: 24px;
            color: #94a3b8;
            font-size: 14px;
        }

        /* Стили для печати */
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
                min-height: auto;
            }

            .container {
                max-width: 100%;
                padding: 0;
            }

            .card {
                box-shadow: none;
                padding: 20px;
            }

            .success-icon,
            .actions,
            .btn,
            .note {
                display: none;
            }

            .ticket {
                border: 2px solid #000;
                box-shadow: none;
                page-break-inside: avoid;
            }

            .ticket-header {
                border-bottom: 2px dashed #000;
            }

            .ticket-number {
                background: #f0f0f0;
                color: #000;
                border: 1px solid #000;
            }

            .info-row {
                border-bottom: 1px solid #ccc;
            }

            .skates-details {
                background: #f9f9f9;
            }

            .ticket-total {
                border-top: 2px solid #000;
            }

            h1, .description {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="success-icon">✓</div>

        <h1>Оплата прошла успешно!</h1>
        <p class="description">Ваш билет на ледовую арену</p>

        <!-- Билет -->
        <div class="ticket">
            <div class="ticket-header">
                <span class="ticket-logo">❄️ ICE ARENA</span>
                <span class="ticket-number">Билет #{{ $booking->id }}</span>
            </div>

            <div class="ticket-info">
                <div class="info-row">
                    <span class="info-label">ФИО:</span>
                    <span class="info-value">{{ $booking->fio }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Телефон:</span>
                    <span class="info-value">{{ $booking->phone }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Время катания:</span>
                    <span class="info-value">{{ $booking->hours }} {{ $booking->hours == 1 ? 'час' : ($booking->hours < 5 ? 'часа' : 'часов') }}</span>
                </div>

                @if($booking->has_skates)
                    <div class="skates-details">
                        <p><strong>⛸️ Аренда коньков:</strong></p>
                        <p>Модель: {{ $booking->skate->model ?? 'Не указана' }}</p>
                        <p>Размер: {{ $booking->skate->size ?? 'Не указан' }}</p>
                        <p>Стоимость: 150 ₽ × {{ $booking->hours }} ч = {{ 150 * $booking->hours }} ₽</p>
                    </div>
                @else
                    <div class="info-row">
                        <span class="info-label">Коньки:</span>
                        <span class="info-value">Свои коньки</span>
                    </div>
                @endif
            </div>

            <div class="ticket-total">
                <span>ИТОГО К ОПЛАТЕ:</span>
                <span class="total-price">{{ number_format($booking->total_amount, 0, '.', ' ') }} ₽</span>
            </div>

            <div style="margin-top: 24px; padding-top: 16px; border-top: 1px dashed #e2e8f0; font-size: 13px; color: #64748b;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                    <span>ID покупки (транзакции):</span>
                    <span style="font-family: monospace; color: #334155; font-weight: 500;">{{ $booking->payment_id ?? 'Не указан' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                    <span>Дата покупки:</span>
                    <span>{{ $booking->created_at->format('d.m.Y H:i:s') }}</span>
                </div>
                @if($booking->paid_at)
                    <div style="display: flex; justify-content: space-between;">
                        <span>Время оплаты:</span>
                        <span>{{ $booking->paid_at->format('d.m.Y H:i:s') }}</span>
                    </div>
                @endif
            </div>
        </div>

        <p style="color: #64748b; font-size: 14px; margin-bottom: 24px;">
            Можете сделать скриншот
        </p>
        <p style="color: #64748b; font-size: 14px; margin-bottom: 24px;">
            Покажите этот билет на входе
        </p>

        <!-- Кнопки действий -->
        <div class="actions">
            <button onclick="window.print()" class="btn btn-secondary">
                🖨️ Распечатать билет
            </button>
            <a href="{{ route('home') }}" class="btn btn-primary">
                На главную
            </a>
        </div>

        <p class="note">
            Ждем вас на нашем катке! Не забудьте взять с собой хорошее настроение 😊
        </p>
    </div>
</div>

<script>
    // Автоматическое предложение печати (можно убрать если не нужно)
    setTimeout(() => {
        if (confirm('Распечатать билет?')) {
            window.print();
        }
    }, 1000);
</script>
</body>
</html>
