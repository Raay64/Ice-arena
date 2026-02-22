<p align="center"><a>ДЗ. / It's just homework.</a></p>

## Изначально, что нужно делать:

Качаем проект -> распаковываем и далее:

1. cd 'путь'
2. composer update
3. copy .env.example .env
3. php artisan key:generate
4. Потом заходим в .env и вводим данные от тестового магазины ЮКассы, по дефолту переменные в .env в самом конце:</br>
YOOKASSA_SHOP_ID=</br>
YOOKASSA_SECRET_KEY=</br>

После заполнения - сохраняем (если нет авто-сейва), и всоп, идем дальше.</br>

5. php artisan migrate --force --seed
6. php artisan serve
И все держим открытым до конца и проверяем работу.

Тестовый юзер:</br>
Админ - admin@example.com ; пароль - password</br>
*перед проверкой, если проверять "мне на прокат еще нужны коньки", нужно добавить через админ-панель коньки)


