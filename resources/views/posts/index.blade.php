<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Blog</title>
        <!--これコメントタグ下のコードはlinkタグでフォント指定 -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
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
                <label for="start_time_input">出発時刻</label>
                <input type="datetime-local" id="start_time_input" required>
                <input type="hidden" id="start_time" name="start_time" value="{{ old('start_time') }}">
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
    <script src="{{ asset('js/routePlanner.js') }}"></script>
</x-app-layout>    
</html>