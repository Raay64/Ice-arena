@extends('layouts.app')

@section('title', $skate ? 'Редактирование коньков' : 'Добавление коньков')

@section('content')
    <div class="container" style="padding: 40px 0; max-width: 600px;">
        <div style="margin-bottom: 32px;">
            <a href="{{ route('admin.skates') }}" style="color: #64748b; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                ← Назад к списку
            </a>
        </div>

        <div class="card">
            <h2 style="color: #0f172a; margin-bottom: 24px;">
                {{ $skate ? 'Редактирование коньков' : 'Добавление новых коньков' }}
            </h2>

            @if($errors->any())
                <div class="alert alert-error" style="margin-bottom: 24px;">
                    <ul style="margin-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ $skate ? route('admin.skates.update', $skate) : route('admin.skates.store') }}" method="POST">
                @csrf
                @if($skate)
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label class="form-label">Модель коньков</label>
                    <input type="text"
                           name="model"
                           class="form-control"
                           value="{{ old('model', $skate->model ?? '') }}"
                           required
                           placeholder="Например: Bauer Vapor">
                    @error('model')
                    <small style="color: #dc2626;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Размер</label>
                    <input type="number"
                           name="size"
                           class="form-control"
                           value="{{ old('size', $skate->size ?? '') }}"
                           required
                           min="20"
                           max="50"
                           placeholder="20-50">
                    @error('size')
                    <small style="color: #dc2626;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Количество в наличии</label>
                    <input type="number"
                           name="quantity"
                           class="form-control"
                           value="{{ old('quantity', $skate->quantity ?? '') }}"
                           required
                           min="0"
                           placeholder="0">
                    @error('quantity')
                    <small style="color: #dc2626;">{{ $message }}</small>
                    @enderror
                </div>

                <div style="display: flex; gap: 16px; margin-top: 32px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        {{ $skate ? 'Сохранить изменения' : 'Добавить коньки' }}
                    </button>
                    <a href="{{ route('admin.skates') }}" class="btn btn-secondary" style="flex: 1;">
                        Отмена
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
