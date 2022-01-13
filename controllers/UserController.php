<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\forms\LoginForm;
use app\models\forms\SignupForm;
use app\services\CityService;
use app\services\UserService;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'signup'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions' => ['logout', 'view'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ]
            ]
        ];
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        $model = new SignupForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            if ($model->validate()) {
                (new UserService())->signup($model);
                $this->goHome();
            }
        }

        $cities = (new CityService())->getAllCities();

        return $this->render('signup', [
            'model' => $model,
            'cities' => $cities
        ]);
    }

    public function actionLogin()
    {
        $model = new LoginForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($model);
            }

            if ($model->validate()) {
                Yii::$app->user->login((new UserService())->getUser($model->email));

                return $this->redirect(['tasks/index']);
            }
        }

        return $this->goHome();
    }

    public function actionView(int $id)
    {
        if (!$user = (new UserService())->getExecutor($id)) {
            throw new NotFoundHttpException;
        }

        return $this->render('view', [
            'user' => $user,
        ]);
    }
}
