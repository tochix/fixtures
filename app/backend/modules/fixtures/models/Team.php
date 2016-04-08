<?php

namespace backend\modules\fixtures\models;

use Yii;

/**
 * This is the model class for table "team".
 *
 * @property int $id
 * @property string $name
 * @property string $created
 * @property string $updated
 * @property Fixture[] $fixtures
 * @property Fixture[] $fixtures0
 * @property FixturePlayers[] $fixturePlayers
 * @property TeamPlayers[] $teamPlayers
 */
class Team extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'team';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created', 'updated'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'created' => Yii::t('app', 'Created'),
            'updated' => Yii::t('app', 'Updated'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFixtures()
    {
        return $this->hasMany(Fixture::className(), ['home_team' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFixtures0()
    {
        return $this->hasMany(Fixture::className(), ['away_team' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFixturePlayers()
    {
        return $this->hasMany(FixturePlayers::className(), ['team_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeamPlayers()
    {
        return $this->hasMany(TeamPlayers::className(), ['team_id' => 'id']);
    }
}
