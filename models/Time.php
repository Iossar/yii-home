<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "time".
 *
 * @property int $id
 * @property string|null $time
 * @property int|null $day_id
 * @property int|null $is_reserved
 *
 * @property Day $day
 * @property Day $day0
 */
class Time extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'time';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['day_id', 'is_reserved'], 'integer'],
            [['time', 'name', 'phone'], 'string', 'max' => 255],
            [['day_id'], 'exist', 'skipOnError' => true, 'targetClass' => Day::className(), 'targetAttribute' => ['day_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'time' => 'Time',
            'day_id' => 'Day ID',
            'is_reserved' => 'Is Reserved',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDay()
    {
        return $this->hasOne(Day::className(), ['id' => 'day_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDay0()
    {
        return $this->hasOne(Day::className(), ['id' => 'day_id']);
    }
}
