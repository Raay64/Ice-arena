@extends('layouts.app')

@section('content')
    <div class="container" style="padding: 40px 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
            <h1 style="color: white;">Управление коньками</h1>
            <a href="{{ route('admin.skates.create') }}" class="btn btn-primary">Добавить коньки</a>
        </div>

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                <tr style="border-bottom: 2px solid #e0e0e0;">
                    <th style="padding: 12px; text-align: left;">ID</th>
                    <th style="padding: 12px; text-align: left;">Модель</th>
                    <th style="padding: 12px; text-align: left;">Размер</th>
                    <th style="padding: 12px; text-align: left;">Количество</th>
                    <th style="padding: 12px; text-align: left;">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($skates as $skate)
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 12px;">{{ $skate->id }}</td>
                        <td style="padding: 12px;">{{ $skate->model }}</td>
                        <td style="padding: 12px;">{{ $skate->size }}</td>
                        <td style="padding: 12px;">{{ $skate->quantity }}</td>
                        <td style="padding: 12px;">
                            <a href="{{ route('admin.skates.edit', $skate) }}" class="btn btn-outline" style="padding: 6px 12px; margin-right: 8px;">Редактировать</a>
                            <form action="{{ route('admin.skates.destroy', $skate) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-primary" style="padding: 6px 12px; background: #dc3545;" onclick="return confirm('Удалить?')">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div style="margin-top: 24px;">
                {{ $skates->links() }}
            </div>
        </div>
    </div>
@endsection
