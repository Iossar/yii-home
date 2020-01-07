<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "day".
 *
 * @property int $id
 * @property string|null $date
 *
 * @property Time $id0
 * @property Time[] $times
 */
class Day extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'day';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => Time::className(), 'targetAttribute' => ['id' => 'day_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(Time::className(), ['day_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTime()
    {
        return $this->hasMany(Time::className(), ['day_id' => 'id']);
    }
}
