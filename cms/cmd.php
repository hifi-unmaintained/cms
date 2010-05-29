<?php
    define('INCLUDE_ROOT', getcwd().'/../');
    require_once('../config.inc.php');

    session_start();

    header('Content-type: application/json');

    $reply = array(
        'r' => 'error'
    );

    if(isset($_POST['q']) && $_POST['q'] == 'login') {
        if(isset(CMS::$config->users[$_POST['username']]) && CMS::$config->users[$_POST['username']] == sha1($_POST['password'])) {
            $_SESSION['logged_in'] = true;
            $reply['r'] = 'ok';
        }
    }

    if(isset($_POST['q']) && $_POST['q'] == 'logout') {
        unset($_SESSION['logged_in']);
        $reply['r'] = 'ok';
    }

    if(isset($_POST['q']) && $_POST['q'] == 'session') {
        if(isset($_SESSION['logged_in']))
            $reply['r'] = 'ok';
    }

    if(isset($_POST['q']) && $_POST['q'] == 'get') {
        if(isset($_SESSION['logged_in'])) {
            $page_id = (int)$_POST['page_id'];
            $field = $_POST['field'];

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

    if(isset($_POST['q']) && $_POST['q'] == 'set') {
        if(isset($_SESSION['logged_in'])) {
            $page_id = (int)$_POST['page_id'];
            $field = $_POST['field'];

            CMS::initDb();

            try {
                $PAGE = new CMS_Page($page_id);
                $reply['r'] = 'ok';
                $reply['page_id'] = $page_id;
                $PAGE->$field = $_POST['value'];
                $PAGE->save();
            } catch(Exception $e) {
                $reply['r'] = 'error';
            }
        }
    }

    echo json_encode($reply);
?>
