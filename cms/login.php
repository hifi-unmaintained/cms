<?php
    define('INCLUDE_ROOT', getcwd().'/..');
    define('CMS_MODE', 'edit');

    session_start();

    require_once('../config.inc.php');

    CMS::initDb();

    if($_POST) {
        $users = CMS::$config->users;
        if(isset($users[$_POST['username']])) {
            if($users[$_POST['username']] == sha1($_POST['password'])) {
                $_SESSION['logged_in'] = true;
            }
        }
    }

    if(isset($_SESSION['logged_in']))
        header('Location: index.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title>CMSCMS</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <base href="<?php echo CMS::baseUri() ?>" />
        <link rel="stylesheet" type="text/css" href="layout/screen.css" media="screen" />
    </head>
    <body>
        <h1>CMS</h1>
        <h2>Login</h2>
        <form action="login.php" method="post">
        <p>
            Username:<br />
            <input type="text" name="username" /><br />
            Password: <br />
            <input type="password" name="password" /><br />
            <input type="submit" value="Login" />
        </p>
        </form>
    </body>
</html>
