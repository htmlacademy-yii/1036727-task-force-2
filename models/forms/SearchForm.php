<?php

namespace app\models\forms;

use yii\base\Model;
use app\models\Category;

class SearchForm extends Model
{
    public $categories;
    public $without_performer;
    public $period;

    const PERIOD_VALUES = [
        '0' => 'default',
        '1' => '1 час',
        '12' => '12 часов',
        '24' => '24 часа'
    ];

    public function rules()
    {
        return [
            ['categories', 'exist', 'targetClass' => Category::class, 'targetAttribute' => 'id', 'allowArray' => true],
            ['without_performer', 'boolean'],
            ['period', 'in', 'range' => array_keys(self::PERIOD_VALUES)]
        ];
    }

    public function attributeLabels()
    {
        return [
            'without_performer' => 'Без исполнителя'
        ];
    }
}
