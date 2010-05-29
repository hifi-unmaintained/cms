$().ready(function() {

    /* make windows behave like windows */
    $('div.cms_window').draggable();
    $('div.cms_window').resizable();
    $('div.cms_window_nofade').draggable();
    $('div.cms_window_nofade').resizable();

    /* handle hover opacity */
    $('div.cms_window').mouseover(function() { $(this).fadeTo(0, 1); });
    $('div.cms_window').mouseout(function() { $(this).fadeTo(0, 0.5); });

    /* fix stacking when clicking/moving */
    $('div.cms_window').mousedown(function() {
        $('div.cms_window').css('z-index', 2);
        $(this).css('z-index', 3);
    });

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
            theme_advanced_resizing : false // breaks resizable() 
    });

    $('#cms_tinymce').hide();

    var win_height = $(window).height();
    var win_width = $(window).width();
    var el_height = $('#cms_login').height();
    var el_width = $('#cms_login').width();
    $('#cms_login').css('left', win_width/2 - el_width/2);
    $('#cms_login').css('top', win_height/2 - el_height/2);

    //$('#cms_login').show();

    cms_load();
});

function CMS_Field(page_id, field, type)
{
    cms_message('Getting data...');
    $.ajax({ url : "cmd.php", type : "POST", data : { q : "get", page_id : page_id, field : field }, success: function(data) {
        cms_done();
        if(data.r == 'ok') {
            if(type == 'html')
                cms_tinymce(page_id, field, data.d);
            if(type == 'label')
                alert('not implemented');
        }
    }});
}

function CMS_Save(page_id, field, value)
{
    cms_message('Saving data...');
    $.ajax({ url : "cmd.php", type : "POST", data : { q : "set", page_id : page_id, field : field, value : value }, success: function(data) {
        cms_done();
        if(data.r == 'ok') {
            $('#cms_page iframe').attr('src', 'page.php?id='+data.page_id);
        }
    }});
}

function cms_tinymce(page_id, field, data)
{
    var win_height = $(window).height();
    var win_width = $(window).width();
    var el_height = $('#cms_tinymce').height();
    var el_width = $('#cms_tinymce').width();

    if(data === null)
        data = '';

    $('#cms_tinymce textarea').html(data);
    $('#cms_tinymce input[name=page_id]').attr('value', page_id);
    $('#cms_tinymce input[name=field]').attr('value', field);

    $('#cms_tinymce').css('left', win_width/2 - el_width/2);
    $('#cms_tinymce').css('top', win_height/2 - el_height/2);

    $('#cms_tinymce').show();
}

function cms_message(msg)
{
    if(typeof msg == 'undefined')
        msg = 'Pondering...';
    var win_height = $(window).height();
    var win_width = $(window).width();
    var el_height = $('#cms_ajax').height();
    var el_width = $('#cms_ajax').width();
    $('#cms_ajax').css('left', win_width/2 - el_width/2);
    $('#cms_ajax').css('top', win_height/2 - el_height/2);
    $('#cms_ajax').css('z-index', '999');
    $('#cms_ajax').html(msg);
    $('#cms_ajax').show();
}

function cms_done()
{
    $('#cms_ajax').hide();
}

function cms_showui()
{
    $('#cms_page iframe').attr('src', 'page.php');
    $('#cms_tools').fadeTo(2000, 0.5);
    $('#cms_options').fadeTo(2000, 0.5);
}

function cms_load()
{
    cms_message('Checking session...');
    $.ajax({ url : "cmd.php", type : "POST", data : { q : "session" }, success: function(data) {
        if(data.r == 'ok') {
            cms_showui();
        } else {
            $('#cms_login').show();
        }
        cms_done();
    }});
}

function cms_login(username, password)
{
    $('#cms_login').hide();
    cms_message('Logging in...');
    $.ajax({ url : "cmd.php", type : "POST", data : { q : "login", username : username, password : password }, success: function(data) {
        if(data.r == 'ok') {
            cms_showui();
        } else {
            $('#cms_login').show();
        }
        cms_done();
    }});
}

function cms_logout()
{
    cms_message('Logging out...');
    $.ajax({ url : "cmd.php", type : "POST", data : { q : "logout" }, success: function(data) {
        window.location.reload();
    }});
}
