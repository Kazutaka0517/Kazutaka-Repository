<?php

namespace App\Http\Controllers;

use App\Models\SavedRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class RouteController extends Controller
{
    private $apiKey;
    private $apiHost = 'navitime-transport.p.rapidapi.com';

    public function __construct()
    {
        $this->apiKey = config('services.navitime.key');
    }

    public function findRoute(Request $request)
    {
        try {
          
    $request->validate([
        'start' => 'required|string',
        'goal' => 'required|string',
        'start_date' => 'required|date_format:Y-m-d',
        'start_time' => 'required|date_format:H:i',
        'via' => 'nullable|array',
    ]);
        
            $startInfo = $this->getStationInfo($request->input('start'));
            $goalInfo = $this->getStationInfo($request->input('goal'));
            $startDateTime = $request->input('start_date') . 'T' . $request->input('start_time') . ':00';
        
        
        if (!$startInfo || !$goalInfo) {
            throw new \Exception('出発地または目的地の情報が見つかりません。');
            }
        
            $apiKey = config('services.navitime.key');
        
            $response = Http::withHeaders([
            
                'X-RapidAPI-Host' => 'navitime-route-totalnavi.p.rapidapi.com',
                'X-RapidAPI-Key' => $apiKey,
            
            ])->get('https://navitime-route-totalnavi.p.rapidapi.com/route_transit', [
                    
                    'start' => $startInfo['lat'] . ',' . $startInfo['lon'],
                    'goal' => $goalInfo['lat'] . ',' . $goalInfo['lon'],
                    'start_time' => $startDateTime,
                    'via' => $request->input('via', []),
                    'limit' => 5,
                    'datum' => 'wgs84',
                    'format' => 'json',
                    'options' => 'railway_calling_at',
                    'shape' => 'true',
            ]);
        
        if (!$response->successful()) {
            throw new \Exception('Route API request failed: ' . $response->status() . ' - ' . $response->body());
            }
        
            $data = $response->json();
        
             $viewData = [
            'routes' => $data['items'] ?? [],
            'start' => $request->input('start'),
            'goal' => $request->input('goal'),
            'start_date' => $request->input('start_date'),
            'start_time' => $request->input('start_time'),
            'via' => $request->input('via', []),
        ];

        if ($request->ajax()) {
            return view('posts.route_result', $viewData)->render();
        }
        
        return view('posts.route_result', $viewData);
        
    } catch (\Exception $e) {
            \Log::error('Route search error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
        if ($request->ajax()) {
            return response()->json(['error' => 'ルート検索中にエラーが発生しました: ' . $e->getMessage()], 500);
        }
            return back()->withInput()->withErrors(['error' => 'ルート検索中にエラーが発生しました: ' . $e->getMessage()]);
        }
    }

   

    public function autocomplete(Request $request)
{
    try {
        $request->validate([
            'word' => 'required|string|min:2|max:50',
        ]);

        $apiKey = config('services.navitime.key');

        $response = Http::withHeaders([
            'X-RapidAPI-Host' => 'navitime-transport.p.rapidapi.com',
            'X-RapidAPI-Key' => $apiKey,
        ])->get('https://navitime-transport.p.rapidapi.com/transport_node/autocomplete', [
            'word' => $request->input('word'),
            'limit' => 10,
        ]);

        if (!$response->successful()) {
            \Log::error('API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $response->headers(),
            ]);
            throw new \Exception('API request failed: ' . $response->status() . ' - ' . $response->body());
        }

        $data = $response->json();

        // APIレスポンスのログ記録（デバッグ用）
        \Log::debug('API Response', ['data' => $data]);

        // レスポンスデータの検証
        if (!isset($data['items']) || !is_array($data['items'])) {
            throw new \Exception('Invalid API response format');
        }

        $formattedItems = array_map(function($item) {
            return [
                'name' => $item['name'] ?? '',
                'lat' => $item['coord']['lat'] ?? null,
                'lon' => $item['coord']['lon'] ?? null,
            ];
        }, $data['items']);

        return response()->json([
            'items' => $formattedItems,
        ]);

    } catch (\Exception $e) {
        \Log::error('Autocomplete error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return response()->json(['error' => 'オートコンプリートの取得中にエラーが発生しました。'], 500);
    }
}
private function getStationInfo($stationName)
{
    $request = new Request(['word' => $stationName]);
    $response = $this->autocomplete($request);
    
    $content = json_decode($response->getContent(), true);
    
    if (isset($content['items']) && count($content['items']) > 0) {
        return $content['items'][0];
    }
    
    return null;
}
}