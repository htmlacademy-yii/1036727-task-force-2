<?php

namespace app\controllers;

use yii\web\NotFoundHttpException;
use app\services\UserService;

class ProfileController extends SecuredController
{
    public function actionUpdate(int $userId)
    {
        
    }

    public function actionView(int $userId)
    {
        if (!$user = (new UserService())->getExecutor($userId)) {
            throw new NotFoundHttpException();
        }

        return $this->render('view', [
            'user' => $user,
        ]);
    }
}
