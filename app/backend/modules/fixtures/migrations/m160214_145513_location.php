<?php

use yii\db\Schema;
use yii\db\Migration;

class m160214_145513_location extends Migration
{
    public function safeUp()
    {
        $this->createTable('location', [
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'city' => 'varchar(255) NOT NULL',
            'created' => 'datetime DEFAULT NULL',
            'updated' => 'timestamp ON UPDATE CURRENT_TIMESTAMP',
            'PRIMARY KEY (id)'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function safeDown()
    {
        $this->dropTable('location');
    }

}