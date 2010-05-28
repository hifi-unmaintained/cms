<?php

class Hippo
{
    static public $db = false;
    static public $config = false;

    static function initDb()
    {
        if(isset(Hippo::$config->db)) {
            try {
                Hippo::$db = new PDO(Hippo::$config->db->dsn, Hippo::$config->db->username, Hippo::$config->db->password, Hippo::$config->db->options);
            } catch(PDOException $e) {
                die('HippoCMS: DB connection failed: '.$e->getMessage());
            }
        } else {
            die('HippoCMS: No DB config.');
        }

        if(strncmp('sqlite:', $config->db->dsn, 7) != 0) {
            Hippo::$db->query('PRAGMA foreign_keys = 1');
        }
    }

    static function baseUri()
    {
        $proto = 'http';
        if($_SERVER['HTTP_PORT'] == 443)
            $proto = 'https';

        return "{$proto}://{$_SERVER['HTTP_HOST']}".dirname($_SERVER['SCRIPT_NAME'])."/";
    }

    function __construct() { throw new Exception('You should not construct Hippo, ever!'); }
}
