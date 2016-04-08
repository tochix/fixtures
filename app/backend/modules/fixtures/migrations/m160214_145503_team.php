<?php

use yii\db\Migration;

class m160214_145503_team extends Migration
{
    public function safeUp()
    {
        $this->createTable('team', [
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'name' => 'varchar(255) NOT NULL',
            'created' => 'datetime DEFAULT NULL',
            'updated' => 'timestamp ON UPDATE CURRENT_TIMESTAMP',
            'PRIMARY KEY (id)',
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function safeDown()
    {
        $this->dropTable('team');
    }
}
