<x-app-layout>
    <x-slot name="header">
        ルート検索結果
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold mb-4">検索条件</h2>
                    <p>出発地: {{ $start }}</p>
                    <p>目的地: {{ $goal }}</p>
                    <p>出発時刻: {{ $start_time }}</p>
                    @if(!empty($via))
                        <p>経由地:</p>
                        <ul>
                            @foreach($via as $point)
                                <li>{{ $point }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <h2 class="text-2xl font-bold mt-8 mb-4">検索結果</h2>
                    @if(count($routes) > 0)
                        @foreach($routes as $index => $route)
                            <div class="mb-8 p-4 border rounded">
                                <h3 class="text-xl font-bold">ルート {{ $index + 1 }}</h3>
                                <p>出発: {{ $route['summary']['start']['name'] }} ({{ $route['summary']['move']['from_time'] }})</p>
                                <p>到着: {{ $route['summary']['goal']['name'] }} ({{ $route['summary']['move']['to_time'] }})</p>
                                <p>所要時間: {{ $route['summary']['move']['time'] }} 分</p>
                                <p>総距離: {{ $route['summary']['move']['distance'] }} m</p>
                                <p>乗換回数: {{ $route['summary']['move']['transit_count'] }} 回</p>
                                @if(isset($route['summary']['move']['fare']['unit_0']))
                                    <p>運賃: {{ $route['summary']['move']['fare']['unit_0'] }} 円</p>
                                @endif
                                <h4 class="text-lg font-bold mt-4 mb-2">詳細</h4>
                                @foreach($route['sections'] as $section)
                                    @if($section['type'] === 'move')
                                        <div class="ml-4 mb-2">
                                            <p>{{ $section['line_name'] }} ({{ $section['move'] }})</p>
                                            <p>{{ $section['from_time'] }} - {{ $section['to_time'] }} ({{ $section['time'] }}分)</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                    @else
                        <p>ルートが見つかりませんでした。</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>