<?php

namespace backend\modules\fixtures\models;

use Yii;

/**
 * This is the model class for table "fixture".
 *
 * @property int $id
 * @property int $home_team
 * @property int $away_team
 * @property int $location_id
 * @property string $fixture_date
 * @property string $result
 * @property string $created
 * @property string $updated
 * @property Team $homeTeam
 * @property Team $awayTeam
 * @property Location $location
 * @property FixturePlayers[] $fixturePlayers
 */
class Fixture extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fixture';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['home_team', 'away_team', 'location_id', 'fixture_date', 'result'], 'required'],
            [['home_team', 'away_team', 'location_id'], 'integer'],
            [['fixture_date', 'created', 'updated'], 'safe'],
            [['result'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'home_team' => Yii::t('app', 'Home Team'),
            'away_team' => Yii::t('app', 'Away Team'),
            'location_id' => Yii::t('app', 'Location ID'),
            'fixture_date' => Yii::t('app', 'Fixture Date'),
            'result' => Yii::t('app', 'Result'),
            'created' => Yii::t('app', 'Created'),
            'updated' => Yii::t('app', 'Updated'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHomeTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'home_team']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAwayTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'away_team']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['id' => 'location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFixturePlayers()
    {
        return $this->hasMany(FixturePlayers::className(), ['fixture_id' => 'id']);
    }
}
