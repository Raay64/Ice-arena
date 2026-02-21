@extends('layouts.app')

@section('title', 'Управление коньками')

@section('content')
    <div class="container" style="padding: 40px 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
            <div>
                <h1 style="color: #0f172a; margin-bottom: 8px;">Управление коньками</h1>
                <p style="color: #64748b;">Добавление, редактирование и удаление коньков</p>
            </div>
            <a href="{{ route('admin.skates.create') }}" class="btn btn-primary">
                + Добавить коньки
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom: 24px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Модель</th>
                    <th>Размер</th>
                    <th>В наличии</th>
                    <th>Дата добавления</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @forelse($skates as $skate)
                    <tr>
                        <td>#{{ $skate->id }}</td>
                        <td>
                            <div style="font-weight: 500;">{{ $skate->model }}</div>
                        </td>
                        <td>{{ $skate->size }}</td>
                        <td>
                            @if($skate->quantity > 0)
                                <span style="color: #059669; background: #d1fae5; padding: 4px 8px; border-radius: 20px; font-size: 12px;">
                                {{ $skate->quantity }} шт.
                            </span>
                            @else
                                <span style="color: #b91c1c; background: #fee2e2; padding: 4px 8px; border-radius: 20px; font-size: 12px;">
                                Нет в наличии
                            </span>
                            @endif
                        </td>
                        <td>{{ $skate->created_at->format('d.m.Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('admin.skates.edit', $skate) }}" class="btn btn-secondary" style="padding: 6px 12px;">
                                    Редактировать
                                </a>
                                <form action="{{ route('admin.skates.destroy', $skate) }}" method="POST" onsubmit="return confirm('Вы уверены?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 6px 12px;">
                                        Удалить
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 48px;">
                            <div style="font-size: 48px; margin-bottom: 16px;">⛸️</div>
                            <h3 style="color: #0f172a; margin-bottom: 8px;">Коньки не добавлены</h3>
                            <p style="color: #64748b; margin-bottom: 24px;">Добавьте первую пару коньков</p>
                            <a href="{{ route('admin.skates.create') }}" class="btn btn-primary">
                                Добавить коньки
                            </a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            @if($skates->hasPages())
                <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                    {{ $skates->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
