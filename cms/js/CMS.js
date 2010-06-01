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

    $('#cms_page_new').dialog({
        autoOpen : false,
        title : "New page",
        resizable : false,
        width : 400,
        height : 'auto',
        buttons : {
            "Cancel" : function(ev, ui) { $('#cms_page_new').dialog('close'); },
            "Create" : function(ev, ui) {
                var title = $('#cms_page_new input[name=title]').attr('value');
                var template = $('#cms_page_new select[name=template]').val();
                var parent_id = CMS.currentPage ? CMS.currentPage : null;

                if(title.length == 0) {
                    $('#cms_page_new .error').html("Title can't be empty.");
                    $('#cms_page_new .ui-state-error').show();
                    $('#cms_page_new .focus').focus();
                    return;
                } else if(template.length == 0) {
                    $('#cms_page_new .error').html("You must select a template.");
                    $('#cms_page_new .ui-state-error').show();
                    $('#cms_page_new .focus').focus();
                    return;
                }

                $('#cms_page_new').dialog('close');
                CMS.query('page_new', { parent_id : parent_id, title : title, template : template }, CMS.updateTree);
            },
        },
        open : function() {
            var parent = CMS.currentPage;
            if(parent == 0)
                parent = '<em>root</em>';
            $('#cms_page_new span.parent').html(parent);
            $(this).children('input[name=title]').attr('value', '');
            $(this).children('.ui-state-error').hide();
            $(this).children('.focus').focus();
        },
    });

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
        minWidth : 800,
        minHeight : 500,
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

    $('#cms_page iframe').load(function() {
        var uri = $('#cms_page iframe')[0].contentWindow.location.href;
        if(uri) {
            var m = uri.match(/id=(\d+)$/);
            if(m) {
                CMS.pageOpen(m[1], true);
            }
        }
    });

    CMS.checkSession();
});

var CMS = new Object();
{
    CMS.currentPage = 0;

    CMS.test = function() {
        var foo = $('#cms_page iframe')[0].contentWindow.location.href;
        alert(foo);
    };

    CMS.updateTree = function() {
        CMS.query('tree', {}, CMS.updateTreeCallback);
    };

    CMS.updateTreeCallback = function(success, data) {
        if(success) {
            var pages = data.d;
            var str = '<ul class="root">';
            var open = false;
            if(CMS.currentPage == 0)
                open = true;
            for(var i in pages) {
                var page = pages[i];
                if(page.parent_id == null)
                    CMS.currentPage = page.id;
                str += CMS.addTreeNode(page);
            }
            $('#cms_tree').html(str+"</ul>");
            if(open && CMS.currentPage != 0)
                CMS.pageOpen(CMS.currentPage);
        }
    };

    CMS.addTreeNode = function(page) {
        var str = '<li>';

        if(page.children.length > 0) {
            str += '<span class="ui-icon ui-icon-folder-open"></span>';
        } else {
            str += '<span class="ui-icon ui-icon-document"></span>';
        }

        str += '<a class="title" class="CMS_Tree_Page" id="CMS_Tree_Page_'+page.id+'" href="javascript:CMS.pageOpen('+page.id+')">'+page.title+'</a>';

        if(page.children.length > 0) {
            str += '<ul>';
            for(var i in page.children) {
                str += CMS.addTreeNode(page.children[i]);
            }
            str += '</ul>';
        }
        return str += '</li>';
    };

    CMS.hideUI = function() {
        $('#cms_tools').dialog('close');
    };

    CMS.showUI = function() {
        CMS.updateTree();
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
        window.location = '';
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
        CMS.pageOpen(page_id);
    };

    CMS.pageOpen = function(page_id, no_change)
    {
        if(typeof no_change == 'undefined')
            $('#cms_page iframe').attr('src', 'page.php?id='+page_id);
        $('#CMS_Tree_Page_'+CMS.currentPage).css('font-weight', 'normal');
        CMS.currentPage = page_id;
        $('#CMS_Tree_Page_'+page_id).css('font-weight', 'bold');
    };

    CMS.pageNew = function()
    {
        CMS.query('templates', { }, function(success, data) {
            if(success && data.r == 'ok') {
                $('#cms_page_new select').text('').append('<option value=""></option>');
                for(var i in data.d) {
                    $('#cms_page_new select').append('<option value="'+data.d[i]+'">'+data.d[i]+'</option>');
                }
                $('#cms_page_new').dialog('open');
            }
        });
    };
};
