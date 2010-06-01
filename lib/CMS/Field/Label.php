<?php

class CMS_Field_Label extends CMS_Field
{
    static function view($page_id, $field, $value)
    {
        print $value;
    }

    static function edit($page_id, $field, $value)
    {
        print <<<EOF
<div style="border: 1px dotted black; position: relative; overflow: visible; min-height: 25px;">
    <a style="
                display: block;
                position: absolute;
                top: 0;
                text-align: center;
                text-decoration: none;
                right: 0;
                width: 100px;
                height: 25px;
                background-color: #f3f3f3;
                color: black;
                font-weight: bold;
                line-height: 25px;
                font-size: 14px;
                font-family: sans-serif;
                border-left: 1px dotted black;
                border-bottom: 1px dotted black;
    " href="javascript:parent.CMS.edit('$page_id', '$field', 'label');">Edit</a>
EOF;
        print $value;
        print <<<EOF
</div>
EOF;
    }
}
