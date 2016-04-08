<?php

use yii\db\Migration;

class m160214_145518_fixture extends Migration
{
    public function safeUp()
    {
        $this->createTable('fixture', [
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'home_team' => 'int(10) unsigned NOT NULL',
            'away_team' => 'int(10) unsigned NOT NULL',
            'location_id' => 'int(10) unsigned NOT NULL',
            'fixture_date' => 'datetime NOT NULL',
            'result' => 'varchar(255) NOT NULL',
            'created' => 'datetime DEFAULT NULL',
            'updated' => 'timestamp ON UPDATE CURRENT_TIMESTAMP',
            'PRIMARY KEY (id)',
            'FOREIGN KEY (home_team) REFERENCES team (id)',
            'FOREIGN KEY (away_team) REFERENCES team (id)',
            'FOREIGN KEY (location_id) REFERENCES location (id)',
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function safeDown()
    {
        $this->dropTable('fixture');
    }
}
