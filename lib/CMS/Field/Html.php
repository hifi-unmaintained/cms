<?php

class CMS_Field_Html extends CMS_Field
{
    static function view($page_id, $field, $value, $options)
    {
        print $value;
    }

    static function edit($page_id, $field, $value, $options)
    {
        if(strlen($value) == '')
            $value = '<em>Click to edit</em>';
        print "<div class=\"CMS_Field_Html\" onclick=\"parent.CMS.edit('{$page_id}', '{$field}', 'Html')\">{$value}</div>\n";
    }

    static function onLoadJS()
    {
print <<<EOF
        CMS.Field.Html = {};
        CMS.Field.Html.edit = function(page_id, field, data)
        {
            $('#cms_tinymce input[name=page_id]').attr('value', page_id);
            $('#cms_tinymce input[name=field]').attr('value', field);
            CMS.Field.Html.buffer = data;
            $('#cms_tinymce').dialog('open');
        }

        CMS.Field.Html.inject = function(frame)
        {
            $(frame).contents().find('.CMS_Field_Html').css('border','1px dashed black').css('min-height', '1em');
        };

        $('body').append(
            '<div id="cms_tinymce">' +
                '<input type="hidden" name="page_id" />' +
                '<input type="hidden" name="field" />' +
                '<textarea name="value"></textarea>' +
            '</div>'
        );
        $('head').append(
            '<script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>'
        );

        $('#cms_tinymce').dialog({
            autoOpen : false,
            title : "HTML Editor",
            resizable : false,
            width : 'auto',
            height : 'auto',
            modal : true,
            minWidth : 800,
            minHeight : 500,
            dialogClass : 'cms_tinymce',
            zIndex : 1,
            buttons : {
                "Cancel" : function(ev, ui) { $('#cms_tinymce').dialog('close'); },
                "Save" : function(ev, ui) {
                    $('#cms_tinymce').dialog('close');
                    CMS.save($('#cms_tinymce input[name=page_id]').attr('value'), $('#cms_tinymce input[name=field]').attr('value'), $('#cms_tinymce textarea[name=value]').attr('value'));
                }
            },
            open : function() {
                $('#cms_tinymce textarea').tinymce({
                        // Location of TinyMCE script
                        script_url : 'js/tinymce/jscripts/tiny_mce/tiny_mce.js',

                        // General options
                        theme : "advanced",
                        plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",
                        width: '800px',
                        height: '450px',

                        // Theme options
                        theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
                        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
                        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
                        theme_advanced_toolbar_location : "top",
                        theme_advanced_toolbar_align : "left",
                        theme_advanced_statusbar_location : "bottom",
                        theme_advanced_resizing : true,
                        cleanup_on_startup : true,
                        cleanup : true,
                        setup : function(ed) {
                            ed.onInit.add(function() {
                                $('#cms_tinymce textarea').tinymce().setContent(CMS.Field.Html.buffer);
                            });
                        }
                });
            },
            close : function() {
                $('#cms_tinymce textarea').tinymce().remove();
            }
        });

EOF;
    }
}
