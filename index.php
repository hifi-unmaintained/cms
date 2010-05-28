<?php
    define('INCLUDE_ROOT', getcwd());
    define('PUPU_MODE', 'view');

    require_once('config.inc.php');

    Pupu::initDb();

    if(isset($_REQUEST['id'])) {
        $uri = intval($_REQUEST['id']);
    } else if(isset($_REQUEST['uri'])) {
        $uri = $_REQUEST['uri'];
    } else {
        $uri = NULL;
    }

    try {
        $PAGE = new Pupu_Page($uri);
    } catch(Exception $e) {
        header('HTTP/1.1 404 Page Not Found');
        header('Content-type: text/plain');
        echo "404 Page Not Found\n\n";
        echo "Exception: ".$e->getMessage();
        exit;
    }

    unset($uri);

    require_once($PAGE->getTemplate());
?>
