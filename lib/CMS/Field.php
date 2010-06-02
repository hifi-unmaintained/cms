<?php

abstract class CMS_Field
{
    abstract static function view($page_id, $field, $value, $options);
    abstract static function edit($page_id, $field, $value, $options);
}
