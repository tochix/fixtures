<?php

use yii\db\Migration;

class m160214_145648_fixture_players extends Migration
{
    public function safeUp()
    {
        $this->createTable('fixture_players', [
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'player_id' => 'int(10) unsigned NOT NULL',
            'team_id' => 'int(10) unsigned NOT NULL',
            'fixture_id' => 'int(10) unsigned NOT NULL',
            'created' => 'datetime DEFAULT NULL',
            'updated' => 'timestamp ON UPDATE CURRENT_TIMESTAMP',
            'PRIMARY KEY (id)',
            'FOREIGN KEY (team_id) REFERENCES team (id)',
            'FOREIGN KEY (fixture_id) REFERENCES fixture (id)',
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function safeDown()
    {
        $this->dropTable('fixture_players');
    }
}
