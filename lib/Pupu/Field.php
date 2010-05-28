<?php

abstract class Pupu_Field
{
    abstract static function view($field, $value);
    abstract static function edit($field, $value);
}
