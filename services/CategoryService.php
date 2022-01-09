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
        $query = Category::find()
            ->where(['inner_name' => $inner_name]);

        return $query->one();
    }
}
