<?php

class CMS_Field_Label extends CMS_Field
{
    static function view($page_id, $field, $value)
    {
        print $value;
    }

    static function edit($page_id, $field, $value)
    {
        print "<div class=\"CMS_Field_Label\" onclick=\"parent.CMS.edit('{$page_id}', '{$field}', 'Label')\">{$value}</div>\n";
    }

    static function onLoadJS()
    {
print <<<EOF
        CMS.Field.Label = {};
        CMS.Field.Label.edit = function(page_id, field, data)
        {
            $('#cms_label input[name=page_id]').attr('value', page_id);
            $('#cms_label input[name=field]').attr('value', field);
            $('#cms_label input[name=value]').attr('value', (data === null ? '' : data) );
            $('#cms_label').dialog('open');
        };

        CMS.Field.Label.inject = function(frame)
        {
            $(frame).contents().find('.CMS_Field_Label').css('border','1px dashed black').css('min-height', '1em');
        };

        $('body').append(
            '<div id="cms_label">' +
                '<input type="hidden" name="page_id" />' +
                '<input type="hidden" name="field" />' +
                '<input type="text" name="value" />' +
            '</div>'
        );

        $('#cms_label').dialog({
            autoOpen : false,
            title : "Label Editor",
            resizable : true,
            width : 400,
            minHeight : 110,
            height : 'auto',
            modal : true,
            dialogClass : 'cms_label',
            buttons : {
                "Cancel" : function(ev, ui) { $('#cms_label').dialog('close'); },
                "Save" : function(ev, ui) {
                    $('#cms_label').dialog('close');
                    CMS.save($('#cms_label input[name=page_id]').attr('value'), $('#cms_label input[name=field]').attr('value'), $('#cms_label input[name=value]').attr('value'));
                }
            },
            open : function() {
                $(this).children('input[name=value]').focus();
            }
        });
        $('#cms_label input[name=value]').keyup(function(ev) {
            if(ev.keyCode == 13) {
                $('#cms_label').dialog('close');
                CMS.save($('#cms_label input[name=page_id]').attr('value'), $('#cms_label input[name=field]').attr('value'), $('#cms_label input[name=value]').attr('value'));
            }
        });
EOF;
    }
}
