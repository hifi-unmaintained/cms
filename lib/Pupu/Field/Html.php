<?php

class Pupu_Field_Html extends Pupu_Field
{
    static function view($field, $value)
    {
        print $value;
    }

    static function edit($field, $value)
    {
        print $value;
    }
}
