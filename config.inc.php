<?php

Hippo::$config = (object)array(
    'db' => (object)array(
        'dsn' => 'sqlite:db/hippo.db',
        'username' => NULL,
        'password' => NULL,
        'options' => array(),
    ),
    'uri' => 'id' // 'prefix', id or pretty
);
