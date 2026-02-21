@extends('layouts.app')

@section('content')
    <div class="container" style="padding: 40px 0;">
        <div style="margin-bottom: 32px;">
            <a href="{{ route('admin.skates') }}" style="color: white; text-decoration: none;">← Назад к списку</a>
        </div>

        <div class="card" style="max-width: 500px; margin: 0 auto;">
            <h2 style="margin-bottom: 24px;">{{ $skate ? 'Редактировать' : 'Добавить' }} коньки</h2>

            <form action="{{ $skate ? route('admin.skates.update', $skate) : route('admin.skates.store') }}" method="POST">
                @csrf
                @if($skate)
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label class="form-label">Модель</label>
                    <input type="text" name="model" class="form-control" value="{{ $skate->model ?? old('model') }}" required>
                    @error('model')
                    <small style="color: #c00;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Размер</label>
                    <input type="number" name="size" class="form-control" min="20" max="50" value="{{ $skate->size ?? old('size') }}" required>
                    @error('size')
                    <small style="color: #c00;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Количество</label>
                    <input type="number" name="quantity" class="form-control" min="0" value="{{ $skate->quantity ?? old('quantity') }}" required>
                    @error('quantity')
                    <small style="color: #c00;">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    {{ $skate ? 'Обновить' : 'Добавить' }}
                </button>
            </form>
        </div>
    </div>
@endsection
