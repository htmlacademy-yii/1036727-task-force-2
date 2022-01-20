<?php

namespace app\services;

use app\models\Category;

class CategoryService
{
    public function getAllCategories(): array
    {
        return Category::find()->all();
    }

    public function getByInnerName(string $inner_name): ?Category
    {
        return Category::findOne(['inner_name' => $inner_name]);
    }
}
