var CMS = {};
/*{*/
    CMS.currentPage = 0;
    CMS.Field = {};

    CMS.updateTree = function() {
        CMS.query('tree', {}, CMS.updateTreeCallback);
    };

    CMS.updateTreeCallback = function(success, data) {
        if(success) {
            var pages = data.d;
            var str = '<ul class="root">';
            var open = false;
            if(CMS.currentPage === 0) {
                open = true;
            }
            for(var i in pages) {
                if(pages.hasOwnProperty(i)) {
                    var page = pages[i];
                    if(page.parent_id === null) {
                        CMS.currentPage = page.id;
                    }
                    str += CMS.addTreeNode(page);
                }
            }
            $('#cms_tree').html(str+"</ul>");
            if(open) {
                if(CMS.currentPage !== 0) {
                    CMS.pageOpen(CMS.currentPage);
                } else {
                    $('#cms_page iframe').attr('src', '');
                }
            }
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
                if(page.children.hasOwnProperty(i)) {
                    str += CMS.addTreeNode(page.children[i]);
                }
            }
            str += '</ul>';
        }
        return (str += '</li>');
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
        if(success && data.r == 'ok') {
            CMS.showUI();
        } else {
            $('#cms_login').dialog('open');
        }
    };

    CMS.login = function()
    {
        var username = $('#cms_login input[name=username]').attr('value');
        var password = $('#cms_login input[name=password]').attr('value');

        if(username.length === 0) {
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
        if(success && data.r == 'ok') {
            CMS.showUI();
        } else {
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
            error : cbError
        });
    };

    CMS.confirm = function(msg, okCb, cancelCb)
    {
        if(typeof cancelCb == 'undefined') {
            cancelCb = function() { };
        }

        $('#cms_confirm').html(msg);
        $('#cms_confirm').dialog({
            buttons : {
                'Cancel' : function() { $(this).dialog('close'); cancelCb(); },
                'Ok' : function() { $(this).dialog('close'); okCb(); }
            }
        });

        $('#cms_confirm').dialog('open');
    };

    CMS.edit = function(page_id, field, type)
    {
        CMS.query('get', { page_id : page_id, field : field }, function(success, data) { CMS.editCallback(page_id, field, type, success, data); });
    };

    CMS.editCallback = function(page_id, field, type, success, data)
    {
        if(success && data.r == 'ok') {
            if(typeof CMS.Field[type] != 'undefined') {
                CMS.Field[type].edit(page_id, field, data.d);
                return;
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
        if(typeof no_change == 'undefined') {
            $('#cms_page iframe').attr('src', 'page.php?id='+page_id);
        }
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
                    if(data.d.hasOwnProperty(i)) {
                        $('#cms_page_new select').append('<option value="'+data.d[i]+'">'+data.d[i]+'</option>');
                    }
                }
                $('#cms_page_new').dialog('open');
            }
        });
    };

    CMS.pageDelete = function()
    {
        if(CMS.currentPage !== 0) {
            CMS.confirm("Do you really want to delete this page?", CMS.pageDeleteConfirmed);
        }
    };

    CMS.pageDeleteConfirmed = function()
    {
        CMS.query('page_delete', { page_id : CMS.currentPage }, CMS.pageDeleteCallback);
    };

    CMS.pageDeleteCallback = function(success, data)
    {
        if(success && data.r == 'ok') {
            CMS.currentPage = 0;
            CMS.updateTree();
        }
    }

    CMS.pageSettings = function()
    {
        if(CMS.currentPage !== 0) {
            //$('#cms_settings').dialog('open');
        }
    }
/*}*/

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
        }
    });
    $('#cms_login input').keyup(function(ev) {
        if(ev.keyCode == 13) {
            CMS.login();
        }
    });

    $('#cms_message').dialog({
        autoOpen : false,
        title : "Working...",
        draggable : false,
        resizable : false,
        modal : true,
        dialogClass : 'cms_message',
        width: 200,
        height: 100
    });

    $('#cms_confirm').dialog({
        autoOpen : false,
        title : "Are you sure?",
        draggable : false,
        resizable : false,
        modal : true,
        width: 'auto',
        height: 120
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
        }
    });
    $('.cms_tools').mouseover(function(ev) { $(this).fadeTo(0, 1); });
    $('.cms_tools').mouseout(function(ev) { $(this).fadeTo(0, 0.7); });

    $('#cms_page_new').dialog({
        autoOpen : false,
        title : "New page",
        resizable : false,
        width : 400,
        height : 'auto',
        modal : true,
        buttons : {
            "Cancel" : function(ev, ui) { $('#cms_page_new').dialog('close'); },
            "Create" : function(ev, ui) {
                var title = $('#cms_page_new input[name=title]').attr('value');
                var template = $('#cms_page_new select[name=template]').val();
                var parent_id = CMS.currentPage ? CMS.currentPage : null;

                if(title.length === 0) {
                    $('#cms_page_new .error').html("Title can't be empty.");
                    $('#cms_page_new .ui-state-error').show();
                    $('#cms_page_new .focus').focus();
                    return;
                } else if(template.length === 0) {
                    $('#cms_page_new .error').html("You must select a template.");
                    $('#cms_page_new .ui-state-error').show();
                    $('#cms_page_new .focus').focus();
                    return;
                }

                $('#cms_page_new').dialog('close');
                CMS.query('page_new', { parent_id : parent_id, title : title, template : template }, function() { CMS.currentPage = 0; CMS.updateTree(); } );
            }
        },
        open : function() {
            var parent = CMS.currentPage;
            if(parent === 0) {
                parent = '<em>root</em>';
            }
            $('#cms_page_new span.parent').html(parent);
            $(this).children('input[name=title]').attr('value', '');
            $(this).children('.ui-state-error').hide();
            $(this).children('.focus').focus();
        }
    });

    $('#cms_page iframe').load(function() {
        var uri = $(this)[0].contentWindow.location.href;
        if(uri) {
            var m = uri.match(/id=(\d+)$/);
            if(m) {
                CMS.pageOpen(m[1], true);
            }
        }

        /* inject current jquery ui theme */
        $(this).contents().find('head').append('<link type="text/css" href="styles/themes/cupertino/jquery-ui-1.8.1.custom.css" rel="stylesheet" />');

        for(var i in CMS.Field) {
            CMS.Field[i].inject(this);
        }
    });

    $('#cont').show();

    /* this will initialize all Field stuff */
    if(typeof CMS.onLoadJS == 'function') {
        CMS.onLoadJS();
    }

    CMS.checkSession();
});

