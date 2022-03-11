<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;
use app\models\forms\ProfileForm;
use app\models\forms\SecurityForm;
use app\services\CategoryService;
use app\services\UserService;

class ProfileController extends SecuredController
{
    public function actionSettings()
    {
        $tab = Yii::$app->request->get('tab');

        $profileForm = new ProfileForm();
        $profileForm->loadCurrentValues($this->user);

        $securityForm = new SecurityForm();
        $securityForm->loadCurrentValues($this->user);

        if (Yii::$app->request->isPost) {
            $profileForm->load(Yii::$app->request->post());
            $securityForm->load(Yii::$app->request->post());
            $profileForm->avatar = UploadedFile::getInstance($profileForm, 'avatar');

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($profileForm);
            }

            if ($profileForm->validate() && isset($tab) && $tab === 'profile') {
                (new UserService())->updateProfile($profileForm);
                $userId = Yii::$app->user->id;

                return match (true) {
                    !$this->user->is_executor => $this->refresh(),
                    default => $this->redirect(['profile/view', 'userId' => $userId])
                };
            }

            if ($securityForm->validate() && isset($tab) && $tab === 'security') {
                (new UserService())->updateSecurity($securityForm);
                $userId = Yii::$app->user->id;

                return match (true) {
                    !$this->user->is_executor => $this->refresh(),
                    default => $this->redirect(['profile/view', 'userId' => $userId])
                };
            }
        }

        $categories = (new CategoryService())->findAll();

        return $this->render('settings', [
            'tab' => $tab,
            'profileForm' => $profileForm,
            'securityForm' => $securityForm,
            'categories' => $categories,
        ]);
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
