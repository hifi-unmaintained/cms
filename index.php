<?php
    define('INCLUDE_ROOT', getcwd());
    define('HIPPO_MODE', 'view');

    set_include_path(get_include_path().":".INCLUDE_ROOT."/lib/");

    function __autoload($class)
    {
        $path = str_replace('_', '/', $class).".php";
        if(file_exists("lib/{$path}")) {
            require_once("lib/{$path}");
        } else if(file_exists("../lib/{$path}")) {
            require_once("../lib/{$path}");
        } else {
            throw new Exception('No such class: '.$path);
        }
    }

    require_once('config.inc.php');

    Hippo::initDb();

    try {
        $HIPPO_PAGE = new Hippo_Page($_REQUEST['uri']);
    } catch(Exception $e) {
        header('HTTP/1.1 404 Page Not Found');
        header('Content-type: text/plain');
        echo "404 Page Not Found\n\n";
        echo "Exception: ".$e->getMessage();
        exit;
    }

    require_once($HIPPO_PAGE->getTemplate());
?>
