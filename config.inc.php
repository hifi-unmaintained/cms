<?php

Pupu::$config = (object)array(
    'db' => (object)array(
        'dsn' => 'sqlite:db/pupu.db',
        'username' => NULL,
        'password' => NULL,
        'options' => array(),
    ),
    'uri' => 'id' // 'prefix', id or pretty
);
