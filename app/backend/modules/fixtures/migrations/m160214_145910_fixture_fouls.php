<?php

use yii\db\Migration;

class m160214_145910_fixture_fouls extends Migration
{
    public function safeUp()
    {
        $this->createTable('fixture_fouls', [
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'fixture_player_id' => 'int(10) unsigned NOT NULL',
            'offence' => 'varchar(255) NOT NULL',
            'fouled_at' => 'datetime NOT NULL',
            'created' => 'datetime DEFAULT NULL',
            'updated' => 'timestamp ON UPDATE CURRENT_TIMESTAMP',
            'PRIMARY KEY (id)',
            'FOREIGN KEY (fixture_player_id) REFERENCES fixture_players (id)',
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function safeDown()
    {
        $this->dropTable('fixture_fouls');
    }
}
