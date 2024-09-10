<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NavitimeApiService
{
    protected $apiKey;
    protected $baseUrl = 'https://navitime-route-totalnavi.p.rapidapi.com/route_transit';

    public function __construct()
    {
        $this->apiKey = config('services.navitime.api_key');
    }

    public function getRouteDetails($startPoint, $endPoint, $viaPoints = [], $startTime = null)
    {
        $response = Http::withHeaders([
            'X-RapidAPI-Host' => 'navitime-route-totalnavi.p.rapidapi.com',
            'X-RapidAPI-Key' => $this->apiKey,
        ])->get($this->baseUrl, [
            'start' => $startPoint,
            'goal' => $endPoint,
            'via' => implode(',', $viaPoints),
            'start_time' => $startTime ?? now()->format('Y-m-d\TH:i:s'),
            'format' => 'json',
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to fetch route details from NAVITIME API');
    }
}