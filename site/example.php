<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title><?php echo $PAGE->title ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <base href="<?php echo Pupu::baseUri() ?>" />
        <link rel="stylesheet" type="text/css" href="styles/screen.css" media="screen" />
    </head>
    <body>
        <h1><?php $PAGE->field('header', 'text'); ?></h1>
        <hr />
            <ul>
                <li><a href="<?php echo Pupu_Page::get()->uri ?>"><?php echo Pupu_Page::get()->title ?></a></li>
                <?php foreach(Pupu_Page::get()->children as $child) { ?>
                <li><a href="<?php echo $child->uri ?>"><?php echo $child->title ?></a></li>
                <?php } ?>
            </ul>
        <hr />

        <?php $PAGE->field('body'); ?>

    </body>
</html>
