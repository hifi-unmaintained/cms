<?php
    define('INCLUDE_ROOT', getcwd().'/../');
    require_once('../config.inc.php');

    header('Content-type: text/javascript');

    $files = scandir('../lib/CMS/Field/');

    print "CMS.onLoadJS = function() {\n";
    foreach($files as $file) {
        if(preg_match('/^([A-Z][a-z]+)\.php$/', $file, $m)) {
            $class = "CMS_Field_{$m[1]}";
            try {
                print "/* $class */\n";
                $class::onLoadJS();
                print "\n";
            } catch(Exception $e) {
                print "/* $class failed to load */\n";
            }
        }
    }
    print "};";
?>
