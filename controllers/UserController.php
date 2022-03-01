<?php

namespace app\controllers;

use Yii;
use yii\authclient\ClientInterface;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\forms\LoginForm;
use app\models\forms\SignupForm;
use app\services\AuthService;
use app\services\CityService;
use app\services\UserService;

class UserController extends Controller
{
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess(ClientInterface $client)
    {
        $attributes = $client->getUserAttributes();

        $email = ArrayHelper::getValue($attributes, 'email');
        $sourceId = ArrayHelper::getValue($attributes, 'id');
        $source = $client->getId();

        if ($auth = (new AuthService())->findOne($source, $sourceId)) {
            return $this->login($auth->user->email);
        }

        if ($email = ArrayHelper::getValue($attributes, 'email')) {

            if ($user = (new UserService())->findByEmail($email)) {
                (new AuthService())->create($user->id, $source, $sourceId);

                return $this->login($email);
            }

            $signupForm = new SignupForm();

            $signupForm->name = "{$attributes['first_name']} {$attributes['last_name']}";
            $signupForm->email = $email;
            $signupForm->city_id = (new CityService())->findByName($attributes['city']['title'])->id ?? 1;
            $signupForm->password = $passwd = Yii::$app->security->generateRandomString();
            $signupForm->password_repeat = $passwd;
            $signupForm->is_executor = 1;

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $user = (new UserService())->create($signupForm);
                (new AuthService())->create($user->id, $source, $sourceId);
                $transaction->commit();
                return $this->login($user->email);
            } catch (\Throwable $e) {
                $transaction->rollBack();
            }
        }

        return $this->goHome();
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['auth', 'login', 'signup'],
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

                return $this->goHome();
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
                $this->login($loginForm->email);
            }
        }

        return $this->goHome();
    }

    private function login(string $email)
    {
        $user = (new UserService())->getUser($email);
        Yii::$app->user->login($user);

        return $this->redirect(['tasks/index']);
    }
}
