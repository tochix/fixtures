<?php

namespace backend\modules\fixtures\models;

use Yii;

/**
 * This is the model class for table "fixture_fouls".
 *
 * @property int $id
 * @property int $fixture_player_id
 * @property string $offence
 * @property string $fouled_at
 * @property string $created
 * @property string $updated
 * @property FixturePlayers $fixturePlayer
 */
class FixtureFouls extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fixture_fouls';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fixture_player_id', 'offence', 'fouled_at'], 'required'],
            [['fixture_player_id'], 'integer'],
            [['fouled_at', 'created', 'updated'], 'safe'],
            [['offence'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fixture_player_id' => Yii::t('app', 'Fixture Player ID'),
            'offence' => Yii::t('app', 'Offence'),
            'fouled_at' => Yii::t('app', 'Fouled At'),
            'created' => Yii::t('app', 'Created'),
            'updated' => Yii::t('app', 'Updated'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFixturePlayer()
    {
        return $this->hasOne(FixturePlayers::className(), ['id' => 'fixture_player_id']);
    }
}
