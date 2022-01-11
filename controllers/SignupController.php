<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\forms\SignupForm;
use app\services\CityService;
use app\services\UserService;

class SignupController extends Controller
{
    public function actionIndex()
    {
        $model = new SignupForm();

        if (Yii::$app->request->getIsPost()) {
            $model->load(Yii::$app->request->post());

            if ($model->validate()) {
                (new UserService())->addNewUser($model);
                $this->goHome();
            }
        }

        $cities = (new CityService())->getAllCities();

        return $this->render('index', [
            'model' => $model,
            'cities' => $cities
        ]);
    }
}
