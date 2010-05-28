<?php

abstract class Pupu_Field
{
    abstract static function view($page_id, $field, $value);
    abstract static function edit($page_id, $field, $value);
}
