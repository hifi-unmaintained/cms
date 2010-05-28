<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title><?php echo $HIPPO_PAGE->title ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <base href="<?php echo Hippo::baseUri() ?>" />
        <link rel="stylesheet" type="text/css" href="styles/screen.css" media="screen" />
    </head>
    <body>
        <h1><?php $HIPPO_PAGE->field('header', 'text'); ?></h1>

        <?php $HIPPO_PAGE->field('body'); ?>

    </body>
</html>
