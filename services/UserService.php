<?php

namespace app\services;

use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use app\models\City;
use app\models\Task;
use app\models\User;
use app\models\UserIdentity;
use app\models\UserProfile;
use app\models\forms\SignupForm;
use app\services\AuthService;
use anatolev\service\Task as Task2;

class UserService
{
    /**
     * @param ClientInterface $client
     * @return void
     */
    public function authHandler(ClientInterface $client): void
    {
        $attributes = $client->getUserAttributes();
        $source = $client->getId();

        $sourceId = ArrayHelper::getValue($attributes, 'id');

        if ($auth = (new AuthService())->findOne($source, $sourceId)) {
            $this->login($auth->user->email);
        } elseif ($email = ArrayHelper::getValue($attributes, 'email')) {

            if ($user = $this->findByEmail($email)) {
                (new AuthService())->create($user->id, $source, $sourceId);
                $this->login($email);
            } else {
                $signupForm = new SignupForm();

                $signupForm->name = "{$attributes['first_name']} {$attributes['last_name']}";
                $signupForm->email = $attributes['email'];
                $signupForm->city_id = (new CityService())->findByName($attributes['city']['title'])->id ?? 1;
                $signupForm->password = $passwd = Yii::$app->security->generateRandomString();
                $signupForm->password_repeat = $passwd;
                $signupForm->is_executor = 1;

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $user = $this->create($signupForm);
                    (new AuthService())->create($user->id, $source, $attributes['id']);
                    $transaction->commit();
                    $this->login($email);
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                }
            }
        }
    }

    /**
     * @param SignupForm $model
     * @return void
     */
    public function create(SignupForm $model): ?User
    {
        $hash = Yii::$app->getSecurity()->generatePasswordHash($model->password);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user = new User();
            $user->attributes = $model->attributes;
            $user->password = $hash;
            $user->save();

            $profile = new UserProfile();
            $profile->user_id = $user->id;
            $profile->save();

            $transaction->commit();

            return $user;

        } catch (\Throwable $e) {
            $transaction->rollBack();
        }

        return null;
    }

    /**
     * @param string $email
     * @return ?User
     */
    public function findByEmail(string $email): ?User
    {
        return User::findOne(['email' => $email]);
    }

    /**
     * @param int $user_id
     * @return ?User
     */
    public function findOne(int $user_id): ?User
    {
        return User::findOne($user_id);
    }

    /**
     * @param int $user_id
     * @return ?User $user
     */
    public function getExecutor(int $user_id): ?User
    {
        $user = User::findOne(['id' => $user_id, 'is_executor' => 1]);

        if (isset($user)) {
            $user->is_busy = $this->isBusy($user_id);
            $user->place_in_rating = $this->getPlaceInRating($user_id);
        }

        return $user;
    }

    /**
     * @param string $email
     * @return ?UserIdentity
     */
    public function getUser(string $email): ?UserIdentity
    {
        return UserIdentity::findOne(['email' => $email]);
    }

    /**
     * @param int $user_id
     * @return bool
     */
    public function isCustomer(int $user_id): bool
    {
        $query = User::find()
            ->where(['id' => $user_id, 'is_executor' => 0]);

        return $query->exists();
    }

    /**
     * @param int $user_id
     * @return bool
     */
    public function isExecutor(int $user_id): bool
    {
        $query = User::find()
            ->where(['id' => $user_id, 'is_executor' => 1]);

        return $query->exists();
    }

    /**
     * @param string $email
     * @return void
     */
    public function login(string $email): void
    {
        Yii::$app->user->login($this->getUser($email));
    }

    /**
     * @param int $user_id
     * @param int $status_id
     * @return void
     */
    public function updateTaskCounter(int $user_id, int $status_id): void
    {
        $doneStatusId = Task2::STATUS_DONE_ID;
        $counter = $status_id === $doneStatusId ? 'done' : 'failed';

        $user = UserProfile::findOne(['user_id' => $user_id]);
        $user->updateCounters(["{$counter}_task_count" => 1]);
        $user->save();
    }

    /**
     * @param int $user_id
     * @return int
     */
    private function getPlaceInRating(int $user_id): int
    {
        $query = User::find()
            ->joinWith('profile p')
            ->where(['user.is_executor' => 1])
            ->orderBy('p.current_rate DESC');

        $users = $query->asArray()->all();

        return array_search($user_id, array_column($users, 'id')) + 1;
    }

    public function signupVKUser(array $attributes, string $source): bool
    {
        $signupForm = new SignupForm();

        $signupForm->name = "{$attributes['first_name']} {$attributes['last_name']}";
        $signupForm->email = $attributes['email'];
        $signupForm->city_id = (new CityService())->findByName($attributes['city']['title'])->id ?? 1;
        $signupForm->password = $passwd = Yii::$app->security->generateRandomString();
        $signupForm->password_repeat = $passwd;
        $signupForm->is_executor = 1;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user = $this->create($signupForm);
            (new AuthService())->create($user->id, $source, $attributes['id']);
            $transaction->commit();

            return true;
        } catch (\Throwable $e) {
            $transaction->rollBack();

            return false;
        }
    }

    /**
     * @param int $user_id
     * @return bool
     */
    private function isBusy(int $user_id): bool
    {
        $condition = ['executor_id' => $user_id, 'status_id' => Task2::STATUS_WORK_ID];
        
        return Task::find()->where($condition)->exists();
    }

    // public function getUserCurrentRate(int $user_id): float
    // {
    //     $query = Review::find()->where(['user_id' => $user_id]);
    //     $overall_rating = $query->sum('rate');
    //     $review_count = $query->count();

    //     $query = UserProfile::find()->where(['id' => $user_id]);
    //     $failed_task_count = $query->one()->failed_task_count;

    //     $devider = $review_count + $failed_task_count;
    //     $current_rate = $devider ? $overall_rating / $devider : 0;

    //     return round($current_rate, 2);
    // }
}
