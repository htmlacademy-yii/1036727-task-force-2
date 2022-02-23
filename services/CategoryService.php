<?php

namespace app\services;

use app\models\Category;

class CategoryService
{
    /**
     * @return Category[]
     */
    public function findAll(): array
    {
        return Category::find()->all();
    }

    /**
     * @param string $innerName
     * @return ?Category
     */
    public function getByInnerName(string $innerName): ?Category
    {
        return Category::findOne(['inner_name' => $innerName]);
    }
}
