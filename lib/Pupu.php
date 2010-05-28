<?php

class Pupu
{
    static public $db = false;
    static public $config = false;

    static function initDb()
    {
        if(isset(Pupu::$config->db)) {
            try {
                Pupu::$db = new PDO(Pupu::$config->db->dsn, Pupu::$config->db->username, Pupu::$config->db->password, Pupu::$config->db->options);
            } catch(PDOException $e) {
                die('PupuCMS: DB connection failed: '.$e->getMessage());
            }
        } else {
            die('PupuCMS: No DB config.');
        }

        if(strncmp('sqlite:', $config->db->dsn, 7) != 0) {
            Pupu::$db->query('PRAGMA foreign_keys = 1');
        }
    }

    static function baseUri()
    {
        $proto = 'http';
        if($_SERVER['HTTP_PORT'] == 443)
            $proto = 'https';

        return "{$proto}://{$_SERVER['HTTP_HOST']}".dirname($_SERVER['SCRIPT_NAME'])."/";
    }

    function __construct() { throw new Exception('You should not construct Pupu, ever!'); }
}
