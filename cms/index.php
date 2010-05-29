<?php
    define('INCLUDE_ROOT', getcwd().'/../');
    require_once('../config.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title>CMS</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <base href="<?php echo CMS::baseUri() ?>" />
        <link rel="stylesheet" type="text/css" href="styles/cms.css" media="screen" />
        <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.8.1.custom.min.js"></script>
        <script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
        <script type="text/javascript" src="js/CMS.js"></script>
    </head>
    <body>
        <div id="cms_tools" class="cms_window">
            <h2>CMS</h2>
            <ul>
                <li><a href="javascript:cms_logout();">&raquo; Logout</a></li>
            </ul>
        </div>
        <div id="cms_options" class="cms_window">
            <h2>Options</h2>
        </div>
        <div id="cms_tinymce" class="cms_window_nofade">
            <h2>HTML Editor</h2>
            <div class="buttons">
                <div class="close" onclick="javascript:$('#cms_tinymce').hide()">X</div>
            </div>
            <form>
            <input type="hidden" name="page_id" />
            <input type="hidden" name="field" />
            <textarea name="value"></textarea>
            <hr />
            <input type="button" value="Save" onclick="javascript:$('#cms_tinymce').hide(); CMS_Save(form.page_id.value, form.field.value, $('#cms_tinymce textarea').html());" />
            </form>
        </div>
        <div id="cms_label" class="cms_window_nofade">
            <h2>Label Editor</h2>
            <div class="buttons">
                <div class="close" onclick="javascript:$('#cms_label').hide()">X</div>
            </div>
            <form>
            <input type="hidden" name="page_id" />
            <input type="hidden" name="field" />
            <input style="width: 440px" name="value" value="" />
            <hr />
            <input class="save" type="button" value="Save" onclick="javascript:$('#cms_label').hide(); CMS_Save(form.page_id.value, form.field.value, form.value.value);" />
            </form>
        </div>
        <div id="cms_page">
            <iframe src=""></iframe>
        </div>
        <div id="cms_login" class="cms_window_nofade">
            <h2>CMS Login</h2>
            <div class="padding">
                <form>
                Username:<br />
                <input type="text" name="username" /><br />
                Password:<br />
                <input type="password" name="password" /><br />
                <input type="button" value="Login" onclick="javascript:cms_login(form.username.value, form.password.value);"/>
                </form>
            </div>
        </div>
        <div id="cms_ajax">
            Pondering...
        </div>
    </body>
</html>
