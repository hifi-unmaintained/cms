<?php

class Hippo_Field_Html extends Hippo_Field
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
