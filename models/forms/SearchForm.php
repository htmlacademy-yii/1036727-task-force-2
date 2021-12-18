<?php

namespace app\models\forms;

use yii\base\Model;

class SearchForm extends Model
{
    public $categories;
    public $without_performer;
    public $period;

    public function rules()
    {
        return [
            [['categories', 'without_performer', 'period'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'without_performer' => 'Без исполнителя'
        ];
    }
}
