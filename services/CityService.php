<?php

namespace app\services;

use app\models\City;

class CityService
{
    public function findAll(): array
    {
        return City::find()->limit(10)->all();
    }
}
