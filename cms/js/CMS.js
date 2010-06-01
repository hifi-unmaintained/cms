$().ready(function() {

    $('#cms_login').dialog({
        autoOpen : false,
        title : "Login",
        buttons : {
            "Go" : CMS.login
        },
        draggable : false,
        resizable : false,
        modal : true,
        dialogClass : 'cms_login',
        width : 250,
        open : function(ev, ui) {
            CMS.hideUI();
            $(this).children('input').attr('value', '');
            $(this).children('.focus').focus();
        },
    });
    $('#cms_login input').keyup(function(ev) {
        if(ev.keyCode == 13)
            CMS.login();
    });

    $('#cms_message').dialog({
        autoOpen : false,
        title : "Working...",
        draggable : false,
        resizable : false,
        modal : true,
        dialogClass : 'cms_message',
        width: 200,
        height: 100,
    });

    $('#cms_tools').dialog({
        autoOpen : false,
        title : "Management",
        resizable : false,
        width : 'auto',
        height : 'auto',
        position : [ 20, 20 ],
        dialogClass : 'cms_tools',
        open : function(ev, ui) {
            $('.cms_tools').trigger('mouseout');
        },
    });
    $('.cms_tools').mouseover(function(ev) { $(this).fadeTo(0, 1); });
    $('.cms_tools').mouseout(function(ev) { $(this).fadeTo(0, 0.7); });

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
            },
        },
        open : function() {
            $(this).children('input[name=value]').focus();
        },
    });
    $('#cms_label input[name=value]').keyup(function(ev) {
        if(ev.keyCode == 13) {
            $('#cms_label').dialog('close');
            CMS.save($('#cms_label input[name=page_id]').attr('value'), $('#cms_label input[name=field]').attr('value'), $('#cms_label input[name=value]').attr('value'));
        }
    });

    $('#cms_tinymce').dialog({
        autoOpen : false,
        title : "HTML Editor",
        resizable : false,
        width : 'auto',
        height : 'auto',
        modal : true,
        dialogClass : 'cms_tinymce',
        zIndex : 1,
        buttons : {
            "Cancel" : function(ev, ui) { $('#cms_tinymce').dialog('close'); },
            "Save" : function(ev, ui) {
                $('#cms_tinymce').dialog('close');
                CMS.save($('#cms_tinymce input[name=page_id]').attr('value'), $('#cms_tinymce input[name=field]').attr('value'), $('#cms_tinymce textarea[name=value]').attr('value'));
            },
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
                    theme_advanced_resizing : true
            });
        },
        close : function() {
            $('#cms_tinymce textarea').tinymce().remove();
        },
    });

    CMS.checkSession();
});

var CMS = new Object();
{
    CMS.test = function() {
        var foo = $('#cms_page iframe')[0].contentWindow.location.href;
        alert(foo);
    };

    CMS.hideUI = function() {
        $('#cms_tools').dialog('close');
    };

    CMS.showUI = function() {
        $('#cms_page iframe').attr('src', 'page.php');
        $('#cms_tools').dialog('open');
    };

    CMS.checkSession = function()
    {
        CMS.hideUI();
        CMS.query('session', {}, CMS.checkSessionCallback);
    };

    CMS.checkSessionCallback = function(success, data)
    {
        if(success && data.r == 'ok')
            CMS.showUI();
        else {
            $('#cms_login').dialog('open');
        }
    };

    CMS.login = function()
    {
        var username = $('#cms_login input[name=username]').attr('value');
        var password = $('#cms_login input[name=password]').attr('value');

        if(username.length == 0) {
            $('#cms_login .error').html('Please input a username.');
            $('#cms_login .ui-state-error').show();
            $('#cms_login .focus').focus();
            return;
        }

        $('#cms_login').dialog('close');
        CMS.query('login', { username : username, password : password }, CMS.loginCallback);
    };

    CMS.loginCallback = function(success, data)
    {
        if(success && data.r == 'ok')
            CMS.showUI();
        else {
            $('#cms_login').dialog('open');
            $('#cms_login .error').html('Username or password incorrect.');
            $('#cms_login .ui-state-error').show();
        }
    };

    CMS.logout = function()
    {
        CMS.hideUI();
        CMS.query('logout', {}, CMS.logoutCallback);
    };

    CMS.logoutCallback = function(success, data)
    {
        $('#cms_page iframe').attr('src', '');
        $('#cms_login input').attr('value', '');
        $('#cms_login').dialog('open');
        CMS.hideUI();
        /* bug in jQuery? */
        setTimeout(CMS.hideUI, 300);
    };

    CMS.query = function(q, data, cb)
    {
        var cbSuccess = function(data) {
            $('#cms_message').dialog('close');
            cb(true, data);
        };
        var cbError = function(data) {
            $('#cms_message').dialog('close');
            cb(false, data);
        };
        $('#cms_message').dialog('open');
        $.ajax({
            url : "cmd.php",
            type : "POST",
            data : {
                q : q,
                d : data
            },
            success : cbSuccess,
            error : cbError,
        });
    };

    CMS.edit = function(page_id, field, type)
    {
        CMS.query('get', { page_id : page_id, field : field }, function(success, data) { CMS.editCallback(page_id, field, type, success, data); });
    };

    CMS.editCallback = function(page_id, field, type, success, data)
    {
        if(success && data.r == 'ok') {
            if(type == 'label') {
                $('#cms_label input[name=page_id]').attr('value', page_id);
                $('#cms_label input[name=field]').attr('value', field);
                $('#cms_label input[name=value]').attr('value', data.d);
                $('#cms_label').dialog('open');
            }
            if(type == 'html') {
                $('#cms_tinymce input[name=page_id]').attr('value', page_id);
                $('#cms_tinymce input[name=field]').attr('value', field);
                $('#cms_tinymce textarea[name=value]').text(data.d);
                $('#cms_tinymce').dialog('open');
            }
        }
    };

    CMS.save = function(page_id, field, value)
    {
        CMS.query('set', { page_id : page_id, field : field, value : value }, function(success, data) { CMS.saveCallback(success, data, page_id); });
    };

    CMS.saveCallback = function(success, data, page_id)
    {
        $('#cms_page iframe').attr('src', 'page.php?id='+page_id);
    };

    CMS.Field = new Object();
    {
        CMS.Field.edit = function(page_id, field, type)
        {

        };

        CMS.Field.getCb = function(success, data)
        {

        };

        CMS.Field.set = function(page_id, field, value)
        {

        };

        CMS.Field.setCb = function(success, data)
        {

        };
    };
};
