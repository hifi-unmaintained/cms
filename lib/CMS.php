<?php

class CMS
{
    static public $db = false;
    static public $config = false;

    static function initDb()
    {
        if(isset(CMS::$config->db)) {
            try {
                CMS::$db = new PDO(CMS::$config->db->dsn, CMS::$config->db->username, CMS::$config->db->password, CMS::$config->db->options);
            } catch(PDOException $e) {
                die('CMSCMS: DB connection failed: '.$e->getMessage());
            }
        } else {
            die('CMSCMS: No DB config.');
        }

        if(strncmp('sqlite:', $config->db->dsn, 7) != 0) {
            CMS::$db->query('PRAGMA foreign_keys = 1');
        }
    }

    static function baseUri()
    {
        $proto = 'http';
        if($_SERVER['HTTP_PORT'] == 443)
            $proto = 'https';

        return "{$proto}://{$_SERVER['HTTP_HOST']}".dirname($_SERVER['SCRIPT_NAME'])."/";
    }

    function __construct() { throw new Exception('You should not construct CMS, ever!'); }
}
