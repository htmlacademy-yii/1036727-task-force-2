<?php

namespace app\controllers;

use yii\web\NotFoundHttpException;
use app\services\UserService;

class ProfileController extends SecuredController
{
    public function actionView(int $id)
    {
        if (!$user = (new UserService())->getExecutor($id)) {
            throw new NotFoundHttpException();
        }

        return $this->render('view', [
            'user' => $user,
        ]);
    }
}
