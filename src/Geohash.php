<?php
declare(strict_types=1);

namespace Geohash;

class Geohash
{
    const ODD = 'odd';
    const EVEN = 'even';
    const DIRECTION_TOP = 'top';
    const DIRECTION_BOTTOM = 'bottom';
    const DIRECTION_LEFT = 'left';
    const DIRECTION_RIGHT = 'right';

    private static $characters = '0123456789bcdefghjkmnpqrstuvwxyz';
    private static $bits = [16, 8, 4, 2, 1];

    private static $neighbors = [
        self::DIRECTION_RIGHT => [
            self::EVEN => 'bc01fg45238967deuvhjyznpkmstqrwx',
            self::ODD => 'p0r21436x8zb9dcf5h7kjnmqesgutwvy',
        ],
        self::DIRECTION_LEFT => [
            self::EVEN => '238967debc01fg45kmstqrwxuvhjyznp',
            self::ODD => '14365h7k9dcfesgujnmqp0r2twvyx8zb',
        ],
        self::DIRECTION_TOP => [
            self::EVEN => 'p0r21436x8zb9dcf5h7kjnmqesgutwvy',
            self::ODD => 'bc01fg45238967deuvhjyznpkmstqrwx',
        ],
        self::DIRECTION_BOTTOM => [
            self::EVEN => '14365h7k9dcfesgujnmqp0r2twvyx8zb',
            self::ODD => '238967debc01fg45kmstqrwxuvhjyznp',
        ],
    ];

    private static $borders = [
        self::DIRECTION_RIGHT => [
            self::EVEN => 'bcfguvyz',
            self::ODD => 'prxz',
        ],
        self::DIRECTION_LEFT => [
            self::EVEN => '0145hjnp',
            self::ODD => '028b',
        ],
        self::DIRECTION_TOP => [
            self::EVEN => 'prxz',
            self::ODD => 'bcfguvyz',
        ],
        self::DIRECTION_BOTTOM => [
            self::EVEN => '028b',
            self::ODD => '0145hjnp',
        ],
    ];

    /**
     * @param float $latitude
     * @param float $longitude
     * @param int $precision
     *
     * @return string
     */
    public static function encode(float $latitude, float $longitude, int $precision = 0): string
    {
        $latitudePrecision = strlen((string) $latitude) - strpos((string) $latitude, '.');
        $longitudePrecision = strlen((string) $longitude) - strpos((string) $longitude, '.');
        $precision = $precision != 0 ? $precision : pow(10, -max($latitudePrecision - 1, $longitudePrecision - 1, 0)) / 2;

        $minLatitude = (float) -90;
        $maxLatitude = (float) 90;
        $minLongitude = (float) -180;
        $maxLongitude = (float) 180;

        $geohash = [];
        $error = (float) 180;
        $isEven = true;
        $character = 0;
        $bit = 0;

        while ($error >= $precision) {
            if ($isEven) {
                $next = ($minLongitude + $maxLongitude) / 2;

                if ($longitude > $next) {
                    $character |= self::$bits[$bit];
                    $minLongitude = $next;
                } else {
                    $maxLongitude = $next;
                }
            } else {
                $next = ($minLatitude + $maxLatitude) / 2;

                if ($latitude > $next) {
                    $character |= self::$bits[$bit];
                    $minLatitude = $next;
                } else {
                    $maxLatitude = $next;
                }
            }
            $isEven = !$isEven;

            if ($bit < 4) {
                $bit++;
            } else {
                $geohash[] = self::$characters[$character];
                $error = max($maxLongitude - $minLongitude, $maxLatitude - $minLatitude);
                $bit = 0;
                $character = 0;
            }
        }

        return implode('', $geohash);
    }

    /**
     * @param string $geohash
     *
     * @return float[]
     */
    public static function decode(string $geohash): array
    {
        $minLongitude = -180;
        $maxLongitude = 180;
        $minLatitude = -90;
        $maxLatitude = 90;
        $latitudeE = 90;
        $longitudeE = 180;

        for ($i = 0; $i < strlen($geohash); $i++) {
            $characterValue = strpos(self::$characters, $geohash[$i]);
            if (1 & $i) {
                if (16 & $characterValue) {
                    $minLatitude = ($minLatitude + $maxLatitude) / 2;
                } else {
                    $maxLatitude = ($minLatitude + $maxLatitude) / 2;
                }
                if (8 & $characterValue) {
                    $minLongitude = ($minLongitude + $maxLongitude) / 2;
                } else {
                    $maxLongitude = ($minLongitude + $maxLongitude) / 2;
                }
                if (4 & $characterValue) {
                    $minLatitude = ($minLatitude + $maxLatitude) / 2;
                } else {
                    $maxLatitude = ($minLatitude + $maxLatitude) / 2;
                }
                if (2 & $characterValue) {
                    $minLongitude = ($minLongitude + $maxLongitude) / 2;
                } else {
                    $maxLongitude = ($minLongitude + $maxLongitude) / 2;
                }
                if (1 & $characterValue) {
                    $minLatitude = ($minLatitude + $maxLatitude) / 2;
                } else {
                    $maxLatitude = ($minLatitude + $maxLatitude) / 2;
                }
                $latitudeE /= 8;
                $longitudeE /= 4;
            } else {
                if (16 & $characterValue) {
                    $minLongitude = ($minLongitude + $maxLongitude) / 2;
                } else {
                    $maxLongitude = ($minLongitude + $maxLongitude) / 2;
                }
                if (8 & $characterValue) {
                    $minLatitude = ($minLatitude + $maxLatitude) / 2;
                } else {
                    $maxLatitude = ($minLatitude + $maxLatitude) / 2;
                }
                if (4 & $characterValue) {
                    $minLongitude = ($minLongitude + $maxLongitude) / 2;
                } else {
                    $maxLongitude = ($minLongitude + $maxLongitude) / 2;
                }
                if (2 & $characterValue) {
                    $minLatitude = ($minLatitude + $maxLatitude) / 2;
                } else {
                    $maxLatitude = ($minLatitude + $maxLatitude) / 2;
                }
                if (1 & $characterValue) {
                    $minLongitude = ($minLongitude + $maxLongitude) / 2;
                } else {
                    $maxLongitude = ($minLongitude + $maxLongitude) / 2;
                }
                $latitudeE /= 4;
                $longitudeE /= 8;
            }
        }
        $latitude = round(($minLatitude + $maxLatitude) / 2, (int) max(1, -round(log10($latitudeE))) - 1);
        $longitude = round(($minLongitude + $maxLongitude) / 2, (int) max(1, -round(log10($longitudeE))) - 1);

        return [$latitude, $longitude];
    }

    /**
     * @param string $geohash
     * @param string $direction
     *
     * Based on David Troy implementation.
     *
     * @link https://github.com/davetroy/geohash-js Original implementation in javascript
     *
     * @return string
     */
    public static function calculateAdjacent(string $geohash, string $direction): string
    {
        $geohash = strtolower($geohash);
        $lastChar = substr($geohash, -1);
        $type = strlen($geohash) % 2 ? self::ODD : self::EVEN;
        $base = substr($geohash, 0, -1);

        if (!empty($base) && strpos(self::$borders[$direction][$type], $lastChar) !== false) {
            $base = self::calculateAdjacent($base, $direction);
        }

        return $base . self::$characters[strpos(self::$neighbors[$direction][$type], $lastChar)];
    }

    /**
     * @param string $geohash
     * @param int $layer
     *
     * @return array
     */
    public static function getNeighbors(string $geohash, int $layer = 1)
    {
        $neighbors = [];

        $currentHash = $geohash;
        // Go Up
        for ($i = 0; $i < $layer; $i++) {
            $currentHash = self::calculateAdjacent($currentHash, self::DIRECTION_TOP);
        }
        $neighbors[] = $currentHash;

        // Go Right
        for ($i = 0; $i < $layer; $i++) {
            $currentHash = self::calculateAdjacent($currentHash, self::DIRECTION_RIGHT);
            $neighbors[] = $currentHash;
        }

        // Go Down
        for ($i = 0; $i < $layer * 2; $i++) {
            $currentHash = self::calculateAdjacent($currentHash, self::DIRECTION_BOTTOM);
            $neighbors[] = $currentHash;
        }

        // Go Left
        for ($i = 0; $i < $layer * 2; $i++) {
            $currentHash = self::calculateAdjacent($currentHash, self::DIRECTION_LEFT);
            $neighbors[] = $currentHash;
        }

        // Go Up Again
        for ($i = 0; $i < $layer * 2; $i++) {
            $currentHash = self::calculateAdjacent($currentHash, self::DIRECTION_TOP);
            $neighbors[] = $currentHash;
        }

        // Go Right Again
        for ($i = 0; $i < $layer - 1; $i++) {
            $currentHash = self::calculateAdjacent($currentHash, self::DIRECTION_RIGHT);
            $neighbors[] = $currentHash;
        }

        return $neighbors;
    }
}
