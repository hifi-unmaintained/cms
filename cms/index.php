<?php
    define('INCLUDE_ROOT', getcwd().'/../');
    require_once('../config.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title>Management</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <base href="<?php echo CMS::baseUri() ?>" />
        <link type="text/css" href="styles/themes/cupertino/jquery-ui-1.8.1.custom.css" rel="stylesheet" />	
        <link type="text/css" href="styles/cms.css" rel="stylesheet" />	
        <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.8.1.custom.min.js"></script>
        <script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
        <script type="text/javascript" src="js/CMS.js"></script>
    </head>
    <body>
        <div id="cms_message"></div>
        <div id="cms_login">
            <div class="ui-state-error ui-corner-all" style="padding: 0.5em">
                <span class="ui-icon ui-icon-alert"></span><span class="error"></span>
            </div>
            Username:<br />
            <input class="focus" type="text" name="username" /><br />
            Password:<br />
            <input type="password" name="password" />
        </div>
        <div id="cms_tools">
            <div id="cms_tree"></div>

            <div class="ui-state-default hr"><hr /></div>

            <ul class="menu">
                <li><span class="ui-icon ui-icon-carat-1-e"></span><a href="javascript:CMS.pageNew();">New page</a></li>
                <li><span class="ui-icon ui-icon-carat-1-e"></span><a href="javascript:CMS.pageDelete();">Delete page</a></li>
                <li><span class="ui-icon ui-icon-carat-1-e"></span><a href="javascript:CMS.pageSettings();">Page settings</a></li>
            </ul>

            <div class="ui-state-default hr"><hr /></div>

            <ul class="menu">
                <li><span class="ui-icon ui-icon-carat-1-e"></span><a href="javascript:CMS.logout();">Logout</a></li>
            </ul>
        </div>
        <div id="cms_page">
            <iframe src=""></iframe>
        </div>
        <div id="cms_page_new">
            <div class="ui-state-error ui-corner-all" style="padding: 0.5em">
                <span class="ui-icon ui-icon-alert"></span><span class="error"></span>
            </div>
            Parent:<br />
            <span class="parent"></span><br />
            Title:<br />
            <input class="text focus" type="text" name="title" /><br />
            Template:<br />
            <select name="template"></select>
        </div>
        <div id="cms_tinymce">
            <input type="hidden" name="page_id" />
            <input type="hidden" name="field" />
            <textarea name="value"></textarea>
        </div>
        <div id="cms_label">
            <input type="hidden" name="page_id" />
            <input type="hidden" name="field" />
            <input type="text" name="value" />
        </div>
    </body>
</html>
