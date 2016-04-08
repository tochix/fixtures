<?php

namespace backend\modules\fixtures\models;

use Yii;

/**
 * This is the model class for table "fixture_players".
 *
 * @property int $id
 * @property int $player_id
 * @property int $team_id
 * @property int $fixture_id
 * @property string $created
 * @property string $updated
 * @property FixtureFouls[] $fixtureFouls
 * @property FixtureGoals[] $fixtureGoals
 * @property Team $team
 * @property Fixture $fixture
 */
class FixturePlayers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fixture_players';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['player_id', 'team_id', 'fixture_id'], 'required'],
            [['player_id', 'team_id', 'fixture_id'], 'integer'],
            [['created', 'updated'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'player_id' => Yii::t('app', 'Player ID'),
            'team_id' => Yii::t('app', 'Team ID'),
            'fixture_id' => Yii::t('app', 'Fixture ID'),
            'created' => Yii::t('app', 'Created'),
            'updated' => Yii::t('app', 'Updated'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFixtureFouls()
    {
        return $this->hasMany(FixtureFouls::className(), ['fixture_player_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFixtureGoals()
    {
        return $this->hasMany(FixtureGoals::className(), ['fixture_player_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFixture()
    {
        return $this->hasOne(Fixture::className(), ['id' => 'fixture_id']);
    }
}
