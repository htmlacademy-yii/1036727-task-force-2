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
                        'actions' => ['logout'],
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
        $signupForm = new SignupForm();

        if (Yii::$app->request->isPost) {
            $signupForm->load(Yii::$app->request->post());

            if ($signupForm->validate()) {
                (new UserService())->create($signupForm);
                $this->goHome();
            }
        }

        $cities = (new CityService())->findAll();

        return $this->render('signup', [
            'model' => $signupForm,
            'cities' => $cities
        ]);
    }

    public function actionLogin()
    {
        $loginForm = new LoginForm();

        if (Yii::$app->request->isPost) {
            $loginForm->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($loginForm);
            }

            if ($loginForm->validate()) {
                Yii::$app->user->login((new UserService())->getUser($loginForm->email));

                return $this->redirect(['tasks/index']);
            }
        }

        return $this->goHome();
    }
}
