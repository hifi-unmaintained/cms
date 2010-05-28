<?php
    define('INCLUDE_ROOT', getcwd().'/..');
    define('PUPU_MODE', 'edit');

    session_start();

    require_once('../config.inc.php');

    Pupu::initDb();

    if(!isset($_SESSION['logged_in']))
        header('Location: login.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title>PupuCMS</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <base href="<?php echo Pupu::baseUri() ?>" />
        <link rel="stylesheet" type="text/css" href="screen.css" media="screen" />
    </head>
    <body>
        <div id="cms">
            <h1>PupuCMS</h1>
        </div>
        <div id="page">
            <iframe src="page.php" />
        </div>
    </body>
</html>
