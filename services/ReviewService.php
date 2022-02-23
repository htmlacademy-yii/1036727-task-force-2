<?php

namespace app\services;

use app\models\Review;
use app\models\forms\CompleteForm;

class ReviewService
{
    public function create(CompleteForm $model)
    {
        $review = new Review();
        $review->attributes = $model->attributes;

        $review->save();
    }
}
