<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StationAutocompleteController extends Controller
{
    public function autocomplete(Request $request)
    {
        $word = $request->input('word');

        $response = Http::withHeaders([
            'X-RapidAPI-Host' => 'navitime-transport.p.rapidapi.com',
            'X-RapidAPI-Key' => env('RAPIDAPI_KEY'),
        ])->get('https://navitime-transport.p.rapidapi.com/transport_node/autocomplete', [
            'word' => $word,
            'lang' => 'ja',
        ]);

        if ($response->successful()) {
            return $response->json();
        } else {
            return response()->json(['error' => '駅名の取得に失敗しました'], 500);
        }
    }
}