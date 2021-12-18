<?php

namespace app\services;

use app\models\Category;

class CategoryService
{
    public function getAllCategories(): array
    {
        return Category::find()->all();
    }
}
