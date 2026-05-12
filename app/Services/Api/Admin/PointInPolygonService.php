<?php

namespace App\Services\Api\Admin;

class PointInPolygonService
{
    public function contains(float $latitude, float $longitude, array $polygon): bool
    {
        $pointsCount = count($polygon);

        if ($pointsCount < 3) {
            return false;
        }

        $inside = false;

        $j = $pointsCount - 1;

        for ($i = 0; $i < $pointsCount; $i++) {
            $pointI = $polygon[$i];
            $pointJ = $polygon[$j];

            $latI = (float) $pointI['lat'];
            $lngI = (float) $pointI['lng'];

            $latJ = (float) $pointJ['lat'];
            $lngJ = (float) $pointJ['lng'];

            $intersect = (($lngI > $longitude) !== ($lngJ > $longitude))
                && ($latitude < ($latJ - $latI) * ($longitude - $lngI) / (($lngJ - $lngI) ?: 0.0000000001) + $latI);

            if ($intersect) {
                $inside = ! $inside;
            }

            $j = $i;
        }

        return $inside;
    }
}