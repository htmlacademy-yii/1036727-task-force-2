<?php

namespace app\services;

use app\models\Category;

class CategoryService
{
    public function getAllCategories(): array
    {
        return Category::find()->all();
    }

    public function getCategoryIds(): array
    {
        return Category::find()->column();
    }
}
