<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оплата отменена</title>
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
            padding: 48px 40px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            text-align: center;
        }

        .icon {
            width: 80px;
            height: 80px;
            background: #fee2e2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 40px;
            color: #dc2626;
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
            text-align: left;
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

        .btn {
            display: inline-block;
            padding: 14px 32px;
            border-radius: 10px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            width: 100%;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            margin-bottom: 12px;
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

        .help-text {
            margin-top: 24px;
            color: #94a3b8;
            font-size: 14px;
        }

        .help-text a {
            color: #2563eb;
            text-decoration: none;
        }

        .help-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="icon">✕</div>

        <h1>Оплата не завершена</h1>
        <p class="description">Вы отменили платеж. Бронирование не было создано.</p>

        <div class="info-block">
            <div class="info-row">
                <span>Что случилось?</span>
                <span>❓</span>
            </div>
            <div style="color: #475569; font-size: 14px; line-height: 1.6;">
                Возможные причины:
                <ul style="margin-top: 8px; margin-left: 20px;">
                    <li>Вы отменили платеж вручную</li>
                    <li>Недостаточно средств на карте</li>
                    <li>Технический сбой при оплате</li>
                    <li>Банк отклонил операцию</li>
                </ul>
            </div>
        </div>

        <a href="{{ route('home') }}#booking" class="btn btn-primary">
            Попробовать снова
        </a>

        <a href="{{ route('home') }}" class="btn btn-secondary">
            Вернуться на главную
        </a>

        <p class="help-text">
            Если у вас возникли вопросы, напишите нам <a href="mailto:support@ice-arena.ru">support@ice-arena.ru</a>
        </p>
    </div>
</div>
</body>
</html>
