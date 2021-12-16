<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $dt_add
 * @property string $name
 * @property string $description
 * @property int|null $budget
 * @property string|null $expire
 * @property string|null $address
 * @property float|null $latitude
 * @property float|null $longitude
 * @property int|null $city_id
 * @property int $status_id
 * @property int $category_id
 * @property int $executor_id
 * @property int $customer_id
 *
 * @property Category $category
 * @property City $city
 * @property User $customer
 * @property User $executor
 * @property Reply[] $replies
 * @property TaskStatus $status
 * @property TaskFile[] $taskFiles
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dt_add', 'expire'], 'safe'],
            [['name', 'description', 'category_id', 'executor_id', 'customer_id'], 'required'],
            [['budget', 'city_id', 'status_id', 'category_id', 'executor_id', 'customer_id'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['name', 'address'], 'string', 'max' => 128],
            [['description'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['executor_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => TaskStatus::className(), 'targetAttribute' => ['status_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dt_add' => 'Dt Add',
            'name' => 'Name',
            'description' => 'Description',
            'budget' => 'Budget',
            'expire' => 'Expire',
            'address' => 'Address',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'city_id' => 'City ID',
            'status_id' => 'Status ID',
            'category_id' => 'Category ID',
            'executor_id' => 'Executor ID',
            'customer_id' => 'Customer ID',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::className(), ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::className(), ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Replies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReplies()
    {
        return $this->hasMany(Reply::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(TaskStatus::className(), ['id' => 'status_id']);
    }

    /**
     * Gets query for [[TaskFiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskFiles()
    {
        return $this->hasMany(TaskFile::className(), ['task_id' => 'id']);
    }
}
