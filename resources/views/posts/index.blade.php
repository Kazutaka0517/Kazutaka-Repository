<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Blog</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/routePlanner.css', 'resources/js/routePlanner.js'])
    </head>
    
    <x-app-layout>
    <x-slot name="header">
        ルート検索
    </x-slot>
    <div>
        <h1>ルート検索</h1>
        @if ($errors->any())
            <div style="color: red;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id="routePlannerForm" action="{{ route('route.find') }}" method="POST">
            @csrf
            <div>
                <label for="start">出発地点</label>
                <input type="text" id="start" name="start" required placeholder="例: 東京駅" value="{{ old('start') }}">
            </div>
            <div>
                <label for="goal">目的地点</label>
                <input type="text" id="goal" name="goal" required placeholder="例: 横浜駅" value="{{ old('goal') }}">
            </div>
            <div>
                <label for="start_date">出発日</label>
                <input type="date" id="start_date" name="start_date" required value="{{ old('start_date') }}">
            </div>
            <div>
                <label for="start_time">出発時刻</label>
                <input type="time" id="start_time" name="start_time" required value="{{ old('start_time') }}">
            </div>
            <div id="viaPointsContainer">
                @if(old('via'))
                    @foreach(old('via') as $index => $via)
                        <div>
                            <label for="via{{ $index + 1 }}">経由地点 {{ $index + 1 }}</label>
                            <input type="text" id="via{{ $index + 1 }}" name="via[]" placeholder="例: 新宿駅" value="{{ $via }}">
                            <button type="button" class="removeViaPoint">削除</button>
                        </div>
                    @endforeach
                @endif
            </div>
            <button type="button" id="addViaPoint">経由地点を追加</button>
            <div>
                <button type="submit">ルート検索</button>
            </div>
        </form>
    </div>
    </x-app-layout>    
</html>