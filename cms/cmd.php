<?php
    define('INCLUDE_ROOT', getcwd().'/../');
    require_once('../config.inc.php');

    session_start();

    header('Content-type: application/json');

    $reply = array(
        'r' => 'error'
    );

    if(isset($_REQUEST['q']) && $_REQUEST['q'] == 'login') {
        if(isset(CMS::$config->users[$_REQUEST['d']['username']]) && CMS::$config->users[$_REQUEST['d']['username']] == sha1($_REQUEST['d']['password'])) {
            $_SESSION['logged_in'] = true;
            $reply['r'] = 'ok';
        }
    }

    if(isset($_REQUEST['q']) && $_REQUEST['q'] == 'logout') {
        unset($_SESSION['logged_in']);
        $reply['r'] = 'ok';
    }

    if(isset($_REQUEST['q']) && $_REQUEST['q'] == 'session') {
        if(isset($_SESSION['logged_in']))
            $reply['r'] = 'ok';
    }

    if(isset($_REQUEST['q']) && $_REQUEST['q'] == 'get') {
        if(isset($_SESSION['logged_in'])) {
            $page_id = (int)$_REQUEST['d']['page_id'];
            $field = $_REQUEST['d']['field'];

            CMS::initDb();

            try {
                $PAGE = new CMS_Page($page_id);
                $reply['r'] = 'ok';
                $reply['d'] = $PAGE->$field;
            } catch(Exception $e) {
                $reply['r'] = 'error';
            }
        }
    }

    if(isset($_REQUEST['q']) && $_REQUEST['q'] == 'set') {
        if(isset($_SESSION['logged_in'])) {
            $page_id = (int)$_REQUEST['d']['page_id'];
            $field = $_REQUEST['d']['field'];

            CMS::initDb();

            try {
                $PAGE = new CMS_Page($page_id);
                $reply['r'] = 'ok';
                $PAGE->$field = $_REQUEST['d']['value'];
                $PAGE->save();
            } catch(Exception $e) {
                $reply['r'] = 'error';
            }
        }
    }

    echo json_encode($reply);
?>
