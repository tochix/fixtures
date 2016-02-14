<?php

namespace backend\modules\fixtures\models;

use Yii;

/**
 * This is the model class for table "team_players".
 *
 * @property integer $id
 * @property string $player_name
 * @property integer $team_id
 * @property string $created
 * @property string $updated
 *
 * @property Team $team
 */
class TeamPlayers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'team_players';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['player_name', 'team_id'], 'required'],
            [['team_id'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['player_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'player_name' => Yii::t('app', 'Player Name'),
            'team_id' => Yii::t('app', 'Team ID'),
            'created' => Yii::t('app', 'Created'),
            'updated' => Yii::t('app', 'Updated'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }
}
