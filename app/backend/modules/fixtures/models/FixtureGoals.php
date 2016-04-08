<?php

namespace backend\modules\fixtures\models;

use Yii;

/**
 * This is the model class for table "fixture_goals".
 *
 * @property int $id
 * @property int $fixture_player_id
 * @property string $scored_at
 * @property string $created
 * @property string $updated
 * @property FixturePlayers $fixturePlayer
 */
class FixtureGoals extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fixture_goals';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fixture_player_id', 'scored_at'], 'required'],
            [['fixture_player_id'], 'integer'],
            [['scored_at', 'created', 'updated'], 'safe'],
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
            'scored_at' => Yii::t('app', 'Scored At'),
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
