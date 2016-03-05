<?php

namespace Geohash\Tests;

use Geohash\Geohash;

class GeohashTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provider
     */
    public function testEncode($lat, $lng, $geohash)
    {
        $this->assertEquals($geohash, Geohash::encode($lat, $lng));
    }

    /**
     * @dataProvider provider
     */
    public function testDecode($lat, $lng, $geohash)
    {
        $this->assertEquals(array($lat, $lng), Geohash::decode($geohash));
    }

    /**
     * @dataProvider adjacentProvider
     */
    public function testCalculateAdjacent($hash, $direction, $adjacentHash)
    {
        $this->assertEquals($adjacentHash, Geohash::calculateAdjacent($hash, $direction));
    }

    /**
     * @dataProvider neighborProvider
     */
    public function testGetNeighbors($hash, $layer, $neighbors)
    {
        $this->assertEquals($neighbors, Geohash::getNeighbors($hash, $layer));
    }

    /**
     * All data come from http://geohash.org
     */
    public function provider()
    {
        return [
            [31.283131, 121.500831, 'wtw3uyfjqw61'],
            [31.28, 121.500831, 'wtw3uy65nwdh'],
            [31.283131, 121.500, 'wtw3uyct7nq3'],
        ];
    }

    public function adjacentProvider()
    {
        return [
            ['r', Geohash::DIRECTION_TOP, 'x'],
            ['r', Geohash::DIRECTION_BOTTOM, 'p'],
            ['r', Geohash::DIRECTION_LEFT, 'q'],
            ['r', Geohash::DIRECTION_RIGHT, '2'],
            ['r3', Geohash::DIRECTION_TOP, 'r6'],
            ['r3', Geohash::DIRECTION_BOTTOM, 'r2'],
            ['r3', Geohash::DIRECTION_LEFT, 'r1'],
            ['r3', Geohash::DIRECTION_RIGHT, 'r9'],
            ['r3g', Geohash::DIRECTION_TOP, 'r65'],
            ['r3g', Geohash::DIRECTION_BOTTOM, 'r3e'],
            ['r3g', Geohash::DIRECTION_LEFT, 'r3f'],
            ['r3g', Geohash::DIRECTION_RIGHT, 'r3u'],
            ['r3gx', Geohash::DIRECTION_TOP, 'r658'],
            ['r3gx', Geohash::DIRECTION_BOTTOM, 'r3gw'],
            ['r3gx', Geohash::DIRECTION_LEFT, 'r3gr'],
            ['r3gx', Geohash::DIRECTION_RIGHT, 'r3gz'],
            ['r3gx0', Geohash::DIRECTION_TOP, 'r3gx2'],
            ['r3gx0', Geohash::DIRECTION_BOTTOM, 'r3gwb'],
            ['r3gx0', Geohash::DIRECTION_LEFT, 'r3grp'],
            ['r3gx0', Geohash::DIRECTION_RIGHT, 'r3gx1'],
            ['r3gx0w', Geohash::DIRECTION_TOP, 'r3gx0x'],
            ['r3gx0w', Geohash::DIRECTION_BOTTOM, 'r3gx0t'],
            ['r3gx0w', Geohash::DIRECTION_LEFT, 'r3gx0q'],
            ['r3gx0w', Geohash::DIRECTION_RIGHT, 'r3gx0y'],

            ['zzpgxc', Geohash::DIRECTION_BOTTOM, 'zzpgxb'],
            ['zzpgxc', Geohash::DIRECTION_LEFT, 'zzpgx9'],
            ['zzpgxc', Geohash::DIRECTION_RIGHT, 'bp0581'],

            ['bp0581', Geohash::DIRECTION_LEFT, 'zzpgxc'],
        ];
    }

    public function neighborProvider()
    {
        return array(
            ['r3gx0', 1, [
                'r3gx2', // Top
                'r3gx3', // Top Right
                'r3gx1', // Right
                'r3gwc', // Bottom Right
                'r3gwb', // Bottom
                'r3gqz', // Bottom Left
                'r3grp', // Left
                'r3grr', // Top Left
            ],
            ],
            ['r3gx0', 2, [
                'r3gx8',
                'r3gx9',
                'r3gxd',
                'r3gx6',
                'r3gx4',
                'r3gwf',
                'r3gwd',
                'r3gw9',
                'r3gw8',
                'r3gqx',
                'r3gqw',
                'r3gqy',
                'r3grn',
                'r3grq',
                'r3grw',
                'r3grx',
            ],
            ],
            ['r3gx0', 3, [
                'r3gxb',
                'r3gxc',
                'r3gxf',
                'r3gxg',
                'r3gxe',
                'r3gx7',
                'r3gx5',
                'r3gwg',
                'r3gwe',
                'r3gw7',
                'r3gw6',
                'r3gw3',
                'r3gw2',

                'r3gqr',
                'r3gqq',
                'r3gqm',
                'r3gqt',
                'r3gqv',
                'r3grj',
                'r3grm',
                'r3grt',
                'r3grv',
                'r3gry',
                'r3grz',
            ],
            ],
        );
    }
}
