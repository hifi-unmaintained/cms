<?php

set_include_path(get_include_path().":".INCLUDE_ROOT."/lib/");

function __autoload($class)
{
    $path = str_replace('_', '/', $class).".php";
    if(file_exists(INCLUDE_ROOT."/lib/{$path}")) {
        require_once(INCLUDE_ROOT."/lib/{$path}");
    } else {
        throw new Exception('No such class: '.$path);
    }
}

CMS::$config = (object)array(
    'db' => (object)array(
        'dsn' => 'sqlite:'.INCLUDE_ROOT.'/db/cms.db',
        'username' => NULL,
        'password' => NULL,
        'options' => array(),
    ),
    'uri' => 'id', // 'prefix', id or pretty
    'users' => array(
        'cms' => sha1(''),
    ),
);

