<?php
    define('INCLUDE_ROOT', getcwd().'/../');
    require_once('../config.inc.php');

    session_start();

    header('Content-type: application/json');

    if(!isset($_REQUEST['q']))
        exit;
    if(!isset($_REQUEST['d']))
        $_REQUEST['d'] = array();

    $q = $_REQUEST['q'];
    $d = $_REQUEST['d'];
    $logged = isset($_SESSION['logged_in']);

    $reply = array(
        'r' => 'error'
    );

    if($q == 'login') {
        if(isset(CMS::$config->users[$_REQUEST['d']['username']]) && CMS::$config->users[$_REQUEST['d']['username']] == sha1($_REQUEST['d']['password'])) {
            $_SESSION['logged_in'] = true;
            $reply['r'] = 'ok';
        }
    }

    if($q == 'logout') {
        unset($_SESSION['logged_in']);
        $reply['r'] = 'ok';
    }

    if($q == 'session') {
        if($logged)
            $reply['r'] = 'ok';
    }

    if($q == 'tree') {
        if($logged) {
            CMS::initDb();

            function &findParent(&$root, $search_id) {
                foreach($root['children'] as &$page) {
                    if($page['id'] == $search_id) {
                        return $page;
                    }
                    if(sizeof($page['children']) > 0) {
                        $parent = &findParent($page, $search_id);
                        if($parent) {
                            return $parent;
                        }
                    }
                }
                return false;
            }

            $reply['r'] = 'ok';
            $reply['d'] = array();

            $stmt = CMS::$db->query('SELECT * FROM page ORDER BY parent_id ASC,title ASC');
            $stmt->execute();
            $root = array('id' => 'root', 'children' => array());
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $parent = &findParent($root, $row['parent_id']);
                if(!$parent)
                    $parent = &$root;

                $data = array();
                foreach($row as $key => $value) {
                    $data[$key] = $value;
                }
                //$reply['d'][] = $data;
                $data['children'] = array();
                $parent['children'][] = $data;
            }

            $reply['d'] = $root['children'];
        }
    }

    if($q == 'templates') {
        if($logged) {
            $reply['r'] = 'ok';
            $reply['d'] = array();
            $files = scandir('../site/');
            foreach($files as $file) {
                $filename = basename($file);
                if(preg_match('/(.+)\.php$/', $filename, $m))
                    $reply['d'][] = $m[1];
            }
        }
    }

    if($q == 'get') {
        if($logged) {
            $page_id = (int)$d['page_id'];
            $field = $d['field'];

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

    if($q == 'set') {
        if($logged) {
            $page_id = (int)$d['page_id'];
            $field = $d['field'];

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

    if($q == 'page_new') {
        if($logged) {
            $parent_id = $d['parent_id'];
            if($parent_id < 1)
                $parent_id = NULL;
            $title = $d['title'];
            $template = $d['template'];

            CMS::initDb();

            $ok = true;
            if($parent_id == NULL) {
                $ok = false;
                try {
                    new CMS_Page(NULL);
                } catch(Exception $e) {
                    $ok = true;
                }
            }

            if($ok) {
                $stmt = CMS::$db->prepare('INSERT INTO page(parent_id, title, template) VALUES(?,?,?)');
                $stmt->execute(array($parent_id, $title, $template));
                $stmt->closeCursor();

                $reply['r'] = 'ok';
            }
        }
    }

    if($q == 'page_delete') {
        if($logged) {
            $page_id = (int)$d['page_id'];

            CMS::initDb();

            try {
                $page = new CMS_Page($page_id);

                if(sizeof($page->children) == 0) {
                    if($page_id == 0 || strlen($page_id) < 1) {
                        $stmt = CMS::$db->prepare('DELETE FROM page WHERE id IS NULL');
                        $stmt->execute();
                    } else {
                        $stmt = CMS::$db->prepare('DELETE FROM page WHERE id = ?');
                        $stmt->execute(array($page_id));
                    }
                    $stmt->closeCursor();

                    $reply['r'] = 'ok';
                }
            } catch(Exception $e) { }
        }
    }

    if($q == 'page_meta_get') {
        if($logged) {
            $page_id = (int)$d['page_id'];

            CMS::initDb();

            try {
                $page = new CMS_Page($page_id);

                $reply['r'] = 'ok';
                $reply['d']['templates'] = array();
                $files = scandir('../site/');
                foreach($files as $file) {
                    $filename = basename($file);
                    if(preg_match('/(.+)\.php$/', $filename, $m))
                        $reply['d']['templates'][] = $m[1];
                }

                $reply['d']['uri'] = $page->realUri;
                $reply['d']['redirect'] = $page->redirect;
                $reply['d']['title'] = $page->title;
                $reply['d']['description'] = $page->description;
                $reply['d']['keywords'] = $page->keywords;
                $reply['d']['template'] = $page->template;

                $reply['r'] = 'ok';
            } catch(Exception $e) { }
        }
    }

    if($q == 'page_meta_set') {
        if($logged) {
            $page_id = (int)$d['page_id'];

            CMS::initDb();

            try {
                $page = new CMS_Page($page_id);

                $keys = array('uri', 'redirect', 'title', 'description', 'keywords', 'template');

                $upd = array();
                $sql = 'UPDATE page SET ';
                $first = true;
                foreach($keys as $key) {
                    if(isset($d[$key])) {
                        if($first)
                            $first = false;
                        else
                            $sql .= ', ';
                        $sql .= " $key = ".CMS::$db->quote($d[$key]);
                    }
                }
                $sql .= " WHERE id = {$page->id}";

                CMS::$db->query($sql);

                $reply['r'] = 'ok';

            } catch(Exception $e) { }
        }
    }

    echo json_encode($reply);
?>
