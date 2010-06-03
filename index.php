<?php
    define('INCLUDE_ROOT', getcwd());
    define('CMS_MODE', 'view');

    require_once('config.inc.php');

    CMS::initDb();

    if(isset($_REQUEST['id'])) {
        $uri = intval($_REQUEST['id']);
    } else if(isset($_REQUEST['uri'])) {
        $uri = $_REQUEST['uri'];
    } else {
        $uri = NULL;
    }

    try {
        $PAGE = new CMS_Page($uri);
    } catch(Exception $e) {
        header('HTTP/1.1 404 Page Not Found');
        header('Content-type: text/plain');
        echo "404 Page Not Found\n\n";
        echo "Exception: ".$e->getMessage();
        exit;
    }

    unset($uri);

    if(strlen($PAGE->redirect) > 0) {
        header('HTTP/1.1 301 Moved Permanently');
        try {
            $redirect = new CMS_Page($PAGE->redirect);
            header("Location: {$redirect->uri}");
        } catch(Exception $e) {
            header("Location: {$PAGE->redirect}");
        }
        exit;
    }

    try {
        $PAGE->getTemplate();
    } catch(Exception $e) {
        header('HTTP/1.1 404 Page Not Found');
        header('Content-type: text/plain');
        echo "404 Page Not Found\n\n";
        echo "Exception: ".$e->getMessage();
        exit;
    }

    require_once($PAGE->getTemplate());
?>
