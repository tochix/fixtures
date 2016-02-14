<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
    	'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=fixtures',
            'username' => 'insert_user',
            'password' => 'insert_pass',
            'charset' => 'utf8',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
        ],
    ],
];
