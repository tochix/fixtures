<?php

namespace backend\modules\fixtures\models;

use Yii;

/**
 * This is the model class for table "location".
 *
 * @property integer $id
 * @property string $city
 * @property string $created
 * @property string $updated
 *
 * @property Fixture[] $fixtures
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city'], 'required'],
            [['created', 'updated'], 'safe'],
            [['city'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'city' => Yii::t('app', 'City'),
            'created' => Yii::t('app', 'Created'),
            'updated' => Yii::t('app', 'Updated'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFixtures()
    {
        return $this->hasMany(Fixture::className(), ['location_id' => 'id']);
    }
}
