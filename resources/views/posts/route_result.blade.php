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
                    <p>出発日: {{ \Carbon\Carbon::parse($start_date)->format('Y年m月d日') }}</p>
                    <p>出発時刻: {{ \Carbon\Carbon::parse($start_time)->format('H:i') }}</p>
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
                                <p>所要時間: {{ $route['summary']['move']['time'] }} 分</p>
                                <p>総距離: {{ $route['summary']['move']['distance'] }} m</p>
                                <p>乗換回数: {{ $route['summary']['move']['transit_count'] }} 回</p>
                                @if(isset($route['summary']['move']['fare']['unit_0']))
                                    <p>運賃: {{ $route['summary']['move']['fare']['unit_0'] }} 円</p>
                                @endif
                                
                                <p>出発: {{ $route['summary']['start']['name'] }} 出発時刻 {{ \Carbon\Carbon::parse($route['summary']['move']['from_time'])->format('H:i') }}</p>
                                
                                @foreach($route['sections'] as $sectionIndex => $section)
                                    @if($section['type'] === 'move')
                                        @if($section['move'] === 'walk')
                                            <p>徒歩: {{ $section['time'] }}分 ({{ $section['distance'] }}m)</p>
                                        @else
                                            <p>{{ $section['line_name'] }}</p>
                                        @endif
                                    @endif
                                    @if($section['type'] === 'point' && $sectionIndex > 0 && $sectionIndex < count($route['sections']) - 1)
                                        @php
                                            $prevSection = $route['sections'][$sectionIndex - 1];
                                            $nextSection = $route['sections'][$sectionIndex + 1];
                                        @endphp
                                        @if($prevSection['type'] === 'move' && $nextSection['type'] === 'move' && $prevSection['move'] !== $nextSection['move'])
                                            <p>乗換え駅: {{ $section['name'] }}
                                               到着時刻 {{ \Carbon\Carbon::parse($prevSection['to_time'])->format('H:i') }}
                                               出発時刻 {{ \Carbon\Carbon::parse($nextSection['from_time'])->format('H:i') }}
                                            </p>
                                        @endif
                                    @endif
                                @endforeach
                                
                                <p>到着: {{ $route['summary']['goal']['name'] }} 到着時刻 {{ \Carbon\Carbon::parse($route['summary']['move']['to_time'])->format('H:i') }}</p>
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