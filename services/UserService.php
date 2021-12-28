<?php

namespace app\services;

use app\models\Review;
use app\models\Task;
use app\models\User;
use app\models\UserProfile;

class UserService
{
    const STATUS_WORK_ID = 3;

    public function getExecutor(int $user_id): ?User
    {
        $query = User::find()
            ->where(['id' => $user_id])
            ->andWhere(['is_executor' => 1]);

        if ($user = $query->one()) {
            $user->busy_status = $this->getBusyStatus($user_id);
            $user->place_in_rating = $this->getPlaceInRating($user_id);
        }

        return $user;
    }

    private function getPlaceInRating(int $user_id): int
    {
        $query = User::find()
            ->joinWith('profile p')
            ->where(['user.is_executor' => 1])
            ->orderBy('p.current_rate DESC');

        $users = $query->asArray()->all();

        return array_search($user_id, array_column($users, 'id')) + 1;
    }

    private function getBusyStatus(int $user_id): string
    {
        $query = Task::find()
            ->where(['executor_id' => $user_id])
            ->andWhere(['status_id' => self::STATUS_WORK_ID]);

        return $query->count() ? 'Занят' : 'Открыт для новых заказов';
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
