<?php

namespace App\Services\Api\Admin;

use App\Models\ServiceArea;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class LocationService
{
    public function isInsideServiceArea(
        float $latitude,
        float $longitude,
        ServiceArea $serviceArea
    ): bool {
        $addressData = $this->reverseGeocode($latitude, $longitude);

        foreach ($addressData as $address) {
            $postalCode = $this->normalize($address['postal_code'] ?? null);
            $city = $this->normalize($address['city'] ?? null);
            $country = $this->normalize($address['country'] ?? null);

            $servicePostalCode = $this->normalize($serviceArea->postal_code);
            $serviceCity = $this->normalize($serviceArea->city);
            $serviceCountry = $this->normalize($serviceArea->country);

            if (
                $postalCode === $servicePostalCode &&
                $city === $serviceCity &&
                $country === $serviceCountry
            ) {
                return true;
            }
        }

        return false;
    }

    public function reverseGeocode(float $latitude, float $longitude): array
    {
        $apiKey = config('services.google_maps.key');

        if (! $apiKey) {
            throw new RuntimeException('Google Maps API key is missing.');
        }

        $response = Http::timeout(10)->get('https://maps.googleapis.com/maps/api/geocode/json', [
            'latlng' => $latitude . ',' . $longitude,
            'key' => $apiKey,
            'language' => 'en',
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('Google reverse geocoding request failed.');
        }

        $data = $response->json();

        if (($data['status'] ?? null) !== 'OK') {
            return [];
        }

        $addresses = [];

        foreach ($data['results'] ?? [] as $result) {
            $addresses[] = $this->extractAddressParts($result['address_components'] ?? []);
        }

        return $addresses;
    }

    private function extractAddressParts(array $components): array
    {
        $postalCode = null;
        $city = null;
        $country = null;

        foreach ($components as $component) {
            $types = $component['types'] ?? [];

            if (in_array('postal_code', $types, true)) {
                $postalCode = $component['long_name'] ?? null;
            }

            if (
                in_array('locality', $types, true) ||
                in_array('postal_town', $types, true) ||
                in_array('administrative_area_level_3', $types, true)
            ) {
                $city = $component['long_name'] ?? null;
            }

            if (in_array('country', $types, true)) {
                $country = $component['long_name'] ?? null;
            }
        }

        return [
            'postal_code' => $postalCode,
            'city' => $city,
            'country' => $country,
        ];
    }

    private function normalize(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return mb_strtolower(trim($value));
    }
}