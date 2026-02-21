<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ожидание оплаты</title>
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
            max-width: 500px;
            width: 100%;
        }

        .card {
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            text-align: center;
        }

        .icon {
            width: 80px;
            height: 80px;
            background: #fef3c7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 40px;
        }

        h1 {
            color: #0f172a;
            font-size: 28px;
            margin-bottom: 12px;
        }

        .description {
            color: #64748b;
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .info-block {
            background: #f8fafc;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
            border: 1px solid #e2e8f0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            color: #64748b;
        }

        .info-value {
            color: #0f172a;
            font-weight: 500;
        }

        .total {
            font-size: 24px;
            color: #2563eb;
            font-weight: 700;
        }

        .loader {
            width: 48px;
            height: 48px;
            border: 3px solid #e2e8f0;
            border-radius: 50%;
            border-top-color: #2563eb;
            animation: spin 1s linear infinite;
            margin: 0 auto 24px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .status-message {
            color: #64748b;
            margin-bottom: 24px;
        }

        .btn {
            display: inline-block;
            padding: 12px 32px;
            border-radius: 10px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
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

        .error-message {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="icon">⏳</div>

        <h1>Ожидание оплаты</h1>
        <p class="description">Мы ждем подтверждение платежа от банка. Это может занять несколько минут.</p>

        <div class="loader"></div>

        <div class="info-block">
            <div class="info-row">
                <span class="info-label">Номер бронирования:</span>
                <span class="info-value">#{{ $booking->id }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">ФИО:</span>
                <span class="info-value">{{ $booking->fio }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Телефон:</span>
                <span class="info-value">{{ $booking->phone }}</span>
            </div>
            @if($booking->has_skates)
                <div class="info-row">
                    <span class="info-label">Коньки:</span>
                    <span class="info-value">{{ $booking->skate->model ?? 'Не указано' }} (р-р {{ $booking->skate->size ?? '?' }})</span>
                </div>
            @endif
            <div class="info-row">
                <span class="info-label">К оплате:</span>
                <span class="info-value total">{{ number_format($booking->total_amount, 0, '.', ' ') }} ₽</span>
            </div>
        </div>

        <p class="status-message" id="statusMessage">Проверяем статус платежа...</p>

        <div style="display: flex; gap: 12px;">
            <a href="{{ route('home') }}" class="btn btn-secondary" style="flex: 1;">
                На главную
            </a>
            <button onclick="checkPayment()" class="btn btn-primary" style="flex: 1;">
                Проверить статус
            </button>
        </div>

        <div id="errorContainer" style="display: none; margin-top: 20px;"></div>
    </div>
</div>

<script>
    function checkPayment() {
        const statusMessage = document.getElementById('statusMessage');
        const errorContainer = document.getElementById('errorContainer');

        statusMessage.textContent = 'Проверяем статус платежа...';
        errorContainer.style.display = 'none';

        fetch('{{ route("payment.check", $booking) }}')
            .then(response => response.json())
            .then(data => {
                if (data.paid) {
                    window.location.reload();
                } else if (data.status === 'succeeded') {
                    window.location.reload();
                } else if (data.status === 'canceled') {
                    window.location.href = '{{ route("payment.cancel", $booking) }}';
                } else {
                    statusMessage.textContent = 'Платеж еще не поступил. Попробуйте проверить позже.';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorContainer.style.display = 'block';
                errorContainer.className = 'error-message';
                errorContainer.textContent = 'Ошибка при проверке статуса. Пожалуйста, попробуйте позже.';
                statusMessage.textContent = 'Не удалось проверить статус';
            });
    }

    // Автоматическая проверка каждые 5 секунд
    setInterval(checkPayment, 5000);
</script>
</body>
</html>
