@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section style="padding: 80px 0; text-align: center; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
        <div class="container">
            <h1 style="font-size: 48px; margin-bottom: 16px; animation: fadeInUp 0.8s ease; color: white; font-weight: 600; letter-spacing: -0.5px;">
                Добро пожаловать на ледовую арену
            </h1>
            <p style="font-size: 20px; margin-bottom: 32px; animation: fadeInUp 1s ease; color: #94a3b8;">
                Почувствуйте магию льда с комфортом и стилем
            </p>
            <a href="#booking" class="btn btn-primary" style="animation: fadeInUp 1.2s ease; padding: 12px 32px; font-size: 16px;">
                Забронировать сейчас
            </a>
        </div>
    </section>

    <!-- Features -->
    <section style="padding: 60px 0; background: white;">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 32px;">
                <div style="text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 16px;">⛸️</div>
                    <h3 style="color: #0f172a; margin-bottom: 8px;">Профессиональный лед</h3>
                    <p style="color: #64748b;">Отличное качество льда для комфортного катания</p>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 16px;">🕒</div>
                    <h3 style="color: #0f172a; margin-bottom: 8px;">Гибкий график</h3>
                    <p style="color: #64748b;">Работаем ежедневно с 10:00 до 23:00</p>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 16px;">🛡️</div>
                    <h3 style="color: #0f172a; margin-bottom: 8px;">Безопасность</h3>
                    <p style="color: #64748b;">Медицинский контроль и инструкторы</p>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 16px;">🎫</div>
                    <h3 style="color: #0f172a; margin-bottom: 8px;">Удобная оплата</h3>
                    <p style="color: #64748b;">Онлайн оплата банковской картой</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Form -->
    <section id="booking" style="padding: 60px 0; background: #f8fafc;">
        <div class="container">
            <div style="text-align: center; margin-bottom: 48px;">
                <h2 style="color: #0f172a; font-size: 36px; margin-bottom: 16px;">Забронируйте катание</h2>
                <p style="color: #64748b; font-size: 18px;">Заполните форму и оплатите онлайн</p>
            </div>

            <div class="card" style="max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0;">
                @if(session('error'))
                    <div style="background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #fecaca;">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">ФИО</label>
                        <input type="text" name="fio" class="form-control" required value="{{ old('fio') }}" placeholder="Иванов Иван Иванович">
                        @error('fio')
                        <small style="color: #dc2626;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Телефон</label>
                        <input type="tel" name="phone" class="form-control phone-mask" required placeholder="+7 (___) ___-__-__" value="{{ old('phone') }}">
                        @error('phone')
                        <small style="color: #dc2626;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Количество часов аренды</label>
                        <select name="hours" class="form-control" required id="hoursSelect">
                            <option value="1">1 час</option>
                            <option value="2">2 часа</option>
                            <option value="3">3 часа</option>
                            <option value="4">4 часа</option>
                        </select>
                    </div>

                    <div style="background: #f8fafc; padding: 16px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e2e8f0;">
                        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                            <input type="checkbox" id="needSkates" style="width: 18px; height: 18px; cursor: pointer;">
                            <span style="color: #334155; font-weight: 500;">Мне нужны коньки напрокат (150 ₽/час)</span>
                        </label>
                    </div>

                    <div id="skatesSection" style="display: none; margin-bottom: 20px;">
                        <div class="form-group">
                            <label class="form-label">Выберите коньки</label>
                            <select name="skate_id" class="form-control" id="skateSelect">
                                <option value="">-- Выберите модель --</option>
                                @foreach($skates as $skate)
                                    @if($skate->quantity > 0)
                                        <option value="{{ $skate->id }}" data-size="{{ $skate->size }}">
                                            {{ $skate->model }} | Размер {{ $skate->size }} | В наличии: {{ $skate->quantity }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div style="background: #f8fafc; padding: 20px; border-radius: 12px; margin: 24px 0; border: 1px solid #e2e8f0;">
                        <h3 style="color: #0f172a; margin-bottom: 16px; font-size: 18px;">Детали заказа</h3>

                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px; color: #475569;">
                            <span>Входной билет:</span>
                            <span style="font-weight: 500;">300 ₽</span>
                        </div>

                        <div id="skatesPrice" style="display: none; justify-content: space-between; margin-bottom: 12px; color: #475569;">
                            <span>Аренда коньков:</span>
                            <span style="font-weight: 500;" id="skatesAmount">150 ₽ × <span id="hoursCount">1</span>ч</span>
                        </div>

                        <div style="height: 1px; background: #e2e8f0; margin: 16px 0;"></div>

                        <div style="display: flex; justify-content: space-between; font-weight: 600; font-size: 20px; color: #0f172a;">
                            <span>Итого к оплате:</span>
                            <span id="totalAmount">300 ₽</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 16px;">
                        Перейти к оплате
                    </button>

                    <p style="text-align: center; margin-top: 16px; color: #64748b; font-size: 14px;">
                        Оплата происходит через защищенное соединение
                    </p>
                </form>
            </div>
        </div>
    </section>

    <!-- Prices -->
    <section id="prices" style="padding: 80px 0; background: white;">
        <div class="container">
            <div style="text-align: center; margin-bottom: 48px;">
                <h2 style="color: #0f172a; font-size: 36px; margin-bottom: 16px;">Наши цены</h2>
                <p style="color: #64748b; font-size: 18px;">Прозрачные и доступные тарифы</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
                <div class="card" style="text-align: center; border: 1px solid #e2e8f0;">
                    <div style="background: #f8fafc; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <span style="font-size: 40px;">🎫</span>
                    </div>
                    <h3 style="color: #2563eb; margin-bottom: 16px; font-size: 20px;">Входной билет</h3>
                    <div style="font-size: 42px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">300 ₽</div>
                    <p style="color: #64748b;">Неограниченное время на катке</p>
                    <div style="margin-top: 24px;">
                        <a href="#booking" class="btn btn-primary" style="width: 100%;">Выбрать</a>
                    </div>
                </div>

                <div class="card" style="text-align: center; border: 1px solid #e2e8f0; transform: scale(1.05); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);">
                    <div style="background: #2563eb; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <span style="font-size: 40px; color: white;">⛸️</span>
                    </div>
                    <h3 style="color: #2563eb; margin-bottom: 16px; font-size: 20px;">Аренда коньков</h3>
                    <div style="font-size: 42px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">150 ₽</div>
                    <div style="color: #64748b; margin-bottom: 16px;">в час</div>
                    <p style="color: #64748b;">Профессиональные коньки</p>
                    <div style="margin-top: 24px;">
                        <a href="#booking" class="btn btn-primary" style="width: 100%;">Выбрать</a>
                    </div>
                </div>

                <div class="card" style="text-align: center; border: 1px solid #e2e8f0;">
                    <div style="background: #f8fafc; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <span style="font-size: 40px;">👟</span>
                    </div>
                    <h3 style="color: #2563eb; margin-bottom: 16px; font-size: 20px;">Свои коньки</h3>
                    <div style="font-size: 42px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">0 ₽</div>
                    <p style="color: #64748b;">Бесплатное катание со своими коньками</p>
                    <div style="margin-top: 24px;">
                        <a href="#booking" class="btn btn-secondary" style="width: 100%;">Выбрать</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section id="contact" style="padding: 80px 0; background: #f8fafc;">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 48px;">
                <div>
                    <h2 style="color: #0f172a; font-size: 36px; margin-bottom: 24px;">Контакты</h2>
                    <div style="margin-bottom: 32px;">
                        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
                            <div style="background: #2563eb; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <span style="color: white; font-size: 20px;">📍</span>
                            </div>
                            <div>
                                <div style="color: #64748b; font-size: 14px;">Адрес</div>
                                <div style="color: #0f172a; font-weight: 500;">г. Москва, ул. Ледовая, д. 1</div>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
                            <div style="background: #2563eb; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <span style="color: white; font-size: 20px;">📞</span>
                            </div>
                            <div>
                                <div style="color: #64748b; font-size: 14px;">Телефон</div>
                                <div style="color: #0f172a; font-weight: 500;">+7 (495) 123-45-67</div>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 16px;">
                            <div style="background: #2563eb; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <span style="color: white; font-size: 20px;">✉️</span>
                            </div>
                            <div>
                                <div style="color: #64748b; font-size: 14px;">Email</div>
                                <div style="color: #0f172a; font-weight: 500;">info@ice-arena.ru</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 style="color: #0f172a; font-size: 36px; margin-bottom: 24px;">Часы работы</h2>
                    <div style="background: white; border-radius: 12px; padding: 32px; border: 1px solid #e2e8f0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid #e2e8f0;">
                            <span style="color: #334155;">Понедельник - Пятница</span>
                            <span style="color: #0f172a; font-weight: 500;">10:00 - 23:00</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid #e2e8f0;">
                            <span style="color: #334155;">Суббота</span>
                            <span style="color: #0f172a; font-weight: 500;">10:00 - 00:00</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #334155;">Воскресенье</span>
                            <span style="color: #0f172a; font-weight: 500;">10:00 - 22:00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            // Phone mask
            document.querySelector('.phone-mask').addEventListener('input', function(e) {
                let x = e.target.value.replace(/\D/g, '').match(/(\d{0,1})(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
                e.target.value = !x[2] ? x[1] : '+7 (' + x[2] + ') ' + x[3] + (x[4] ? '-' + x[4] : '') + (x[5] ? '-' + x[5] : '');
            });

            // Toggle skates section
            document.getElementById('needSkates').addEventListener('change', function(e) {
                const skatesSection = document.getElementById('skatesSection');
                const skatesPrice = document.getElementById('skatesPrice');

                if (e.target.checked) {
                    skatesSection.style.display = 'block';
                    skatesPrice.style.display = 'flex';
                    document.getElementById('skateSelect').required = true;
                } else {
                    skatesSection.style.display = 'none';
                    skatesPrice.style.display = 'none';
                    document.getElementById('skateSelect').required = false;
                    document.getElementById('skateSelect').value = '';
                }
                updateTotal();
            });

            // Update total
            function updateTotal() {
                const hours = parseInt(document.getElementById('hoursSelect').value) || 1;
                const needSkates = document.getElementById('needSkates').checked;

                document.getElementById('hoursCount').textContent = hours;

                let total = 300; // Билет
                if (needSkates) {
                    total += 150 * hours;
                    document.getElementById('skatesAmount').innerHTML = `150 ₽ × ${hours}ч`;
                }

                document.getElementById('totalAmount').textContent = total + ' ₽';
            }

            document.getElementById('hoursSelect').addEventListener('change', updateTotal);

            // Initialize
            updateTotal();
        </script>
    @endpush

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
@endsection
