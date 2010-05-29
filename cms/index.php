<?php
    define('INCLUDE_ROOT', getcwd().'/..');
    define('CMS_MODE', 'edit');

    session_start();

    require_once('../config.inc.php');

    CMS::initDb();

    if(!isset($_SESSION['logged_in']))
        header('Location: login.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title>CMSCMS</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <base href="<?php echo CMS::baseUri() ?>" />
        <link rel="stylesheet" type="text/css" href="screen.css" media="screen" />
        <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
        <script type="text/javascript" src="js/CMS.js"></script>
    </head>
    <body>
        <div id="cms">
            <h1>CMS</h1>
        </div>
        <div id="page">
            <iframe src="page.php"></iframe>
            <div id="pupu_overlay"></div>
            <div id="pupu_tools"></div>
        </div>
    </body>
</html>
