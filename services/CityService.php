<?php

namespace app\services;

use app\models\City;

class CityService
{
    /**
     * @return array
     */
    public function findAll(): array
    {
        return City::find()->limit(10)->all();
    }

    /**
     * @param float $lat
     * @param float $long
     * @return ?City
     */
    public function findByCoords(float $lat, float $long): ?City
    {
        $condition1 = ['like', 'latitude', bcdiv($lat, 1, 1)];
        $condition2 = ['like', 'longitude', bcdiv($long, 1, 1)];

        return City::find()->where($condition1)->andWhere($condition2)->one();
    }
}
