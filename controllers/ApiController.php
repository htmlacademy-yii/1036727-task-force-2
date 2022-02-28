<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class ApiController extends Controller
{
    public function actionGeocoder(string $geocode)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return Yii::$app->geocoder->getCoords($geocode);
    }
}
