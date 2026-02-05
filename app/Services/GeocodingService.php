<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeocodingService
{
    public static function getAddress(float $lat, float $lng): ?string
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'MFYTizim/1.0',
            ])->get('https://nominatim.openstreetmap.org/reverse', [
                'lat' => $lat,
                'lon' => $lng,
                'format' => 'json',
                'accept-language' => 'uz',
            ]);

            if ($response->successful()) {
                return $response->json('display_name');
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}