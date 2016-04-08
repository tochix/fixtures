<?php

use yii\db\Migration;

class m160214_145510_team_players extends Migration
{
    public function safeUp()
    {
        $this->createTable('team_players', [
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'player_name' => 'varchar(255) NOT NULL',
            'team_id' => 'int(10) unsigned NOT NULL',
            'created' => 'datetime DEFAULT NULL',
            'updated' => 'timestamp ON UPDATE CURRENT_TIMESTAMP',
            'PRIMARY KEY (id)',
            'FOREIGN KEY (team_id) REFERENCES team (id)',
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function safeDown()
    {
        $this->dropTable('team_players');
    }
}
