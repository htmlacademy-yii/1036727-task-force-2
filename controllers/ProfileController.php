<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\controllers\SecuredController;
use app\services\UserService;

class ProfileController extends SecuredController
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
