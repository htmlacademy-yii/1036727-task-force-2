<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class GeoController extends Controller
{
    public function actionIndex(string $geocode)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return Yii::$app->geocoder->getCoords($geocode);
    }
}
