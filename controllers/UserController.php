<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\services\UserService;

class UserController extends Controller
{
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
