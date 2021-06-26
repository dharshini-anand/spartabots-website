/* ContextMenu.js Global Variables */

var ctx_funcData = {}
var ctx_open_menu_funcData = {}
var ctx_close_menu_funcData = {}
var ctx_menu_id = 0;
var ctx_item_id = 0;
var ctx_submenu_id = 0;
var ctx_first_creation = true;
var ctx_style = 'default';
var ctx_script_rel_path = undefined;
var click_target = undefined;

$('html').mousedown(function(e) {
    if (e.which == 1) {
        if (!$(e.target).closest('.ctx-menu').length) {
            ctx_close_menus();
        }
    } else if (e.which == 3) {
        if (!$(event.target).closest('.context-menu').length && !$(event.target).closest('.ctx-menu').length) {
            ctx_close_menus();
        }
    }
});

$('html').on('contextmenu', function(e) {
    if ($(event.target).is('.ctx-menu') || $(event.target).closest('.ctx-menu').length || $(event.target).closest('.context-menu').length) {
        return false;
    }
});

$(window).blur(function() {
    ctx_close_menus();
}).scroll(function() {
    ctx_close_menus();
}).resize(function() {
    ctx_close_menus();
});

function contextMenuInit(data, ctx_target) {
    var ctx_menu = document.createElement("div");
    $(ctx_menu).addClass('ctx-menu');
    var id = undefined;
    $(ctx_menu).attr('id', 'ctx-menu-' + (id = ctx_menu_id++));
    
    ctx_createMenu(ctx_menu, data, id);
    
    if (ctx_first_creation) {
        ctx_first_creation = false;
        var ctx_menu_container = document.createElement("div");
        $(ctx_menu_container).attr('id', 'ctx-menu-container');
        $('body').append(ctx_menu_container);
    }
    
    $('#ctx-menu-container').append(ctx_menu);
    
    $('#'+$(ctx_menu).attr('id') + ' .ctx-item, #' + $(ctx_menu).attr('id') + ' .ctx-submenu').click(function(e) {
        e.stopPropagation();
        var href = $(this).attr('href');
        if (href != undefined) {
            location.href = href;
            return;
        }
        var func = ctx_funcData[$(this).attr('id')];
        if (func != undefined)
            func(e, e.target, click_target, ctx_target);
        ctx_close_menus();
    });
    
    return id;
}

$.fn.contextMenu = function(data) { 
    contextMenu(data, this);
}

function contextMenu(data, element) {
    $(element).each(function() {
        $(this).addClass('context-menu');
        
        var menu_id = contextMenuInit(data, this);
        $(this).attr('data-ctx-menu-target', menu_id);
        $(this).mousedown(function(e) {
            if (e.which == 3) {
                if ($(e.target).closest('.ctx-menu').length) {
                    return;
                }
                
                ctx_close_menus();
                
                click_target = e.target;
                var ctx_target = this;
                $(ctx_target).addClass('ctx-menu-active');
                
                var mX = e.pageX;
                var mY = e.pageY;
                var menu = $('#ctx-menu-container #ctx-menu-' + menu_id);
                menu.css('top', mY+'px');
                menu.css('left', mX+'px');
                menu.show();
                
                var open_menu_func = ctx_open_menu_funcData[menu_id];
                if (open_menu_func != undefined) {
                    open_menu_func(menu, menu_id, click_target, ctx_target);
                }
            }
        });
    });
}

function ctx_close_menus() {
    $('#ctx-menu-container > .ctx-menu').each(function() {
        if ($(this).is(':visible')) {
            var menu_id = parseInt($(this).attr('id').replace('ctx-menu', ''));
            var close_menu_func = ctx_close_menu_funcData[menu_id];
            if (close_menu_func != undefined) {
                close_menu_func(this, menu_id);
            }
        }
    });
    $('#ctx-menu-container > .ctx-menu').hide();
    $('.ctx-menu-active').removeClass('ctx-menu-active');
}

function ctx_createMenu(parentMenu, data, parent_menu_id) {
    for (var i = 0; i < data.length; i++) {
        var data2 = data[i];
        var label = data2['label'];
        
        var event_handler_name = data2['event'];
        if (event_handler_name != undefined) {
            if (event_handler_name == 'open_menu') {
                ctx_open_menu_funcData[parent_menu_id] = data2['handler'];
            } else if (event_handler_name == 'close_menu') {
                ctx_close_menu_funcData[parent_menu_id] = data2['handler'];
            }
            continue;
        }
        
        var add_hr = data2['hr'];
        if (label == undefined)
            label = "Undefined";
        if (add_hr == undefined)
            add_hr = false;
        var children = data2['children'];
        
        var ctx_item = document.createElement("div");
        $(ctx_item).addClass('ctx-item');
        $(ctx_item).attr('id', 'ctx-item-'+(ctx_item_id++));
        $(ctx_item).html(label);
        $(ctx_item).attr('href', data2['href']);
        ctx_funcData[$(ctx_item).attr('id')] = data2['onclick'];
        $(ctx_item).appendTo(parentMenu);
        
        if (add_hr) {
            var hrule = document.createElement("hr");
            $(hrule).appendTo(parentMenu);
        }
        
        if (children != undefined) {
            var ctx_submenu = document.createElement("div");
            $(ctx_submenu).addClass('ctx-submenu');
            $(ctx_submenu).attr('id', 'ctx-submenu-'+(ctx_submenu_id++));
            ctx_funcData[$(ctx_submenu).attr('id')] = data2['onclick'];
            $(ctx_submenu).appendTo(ctx_item);
            ctx_createMenu(ctx_submenu, children, parent_menu_id);
        }
    }
}

function ctx_loadCSS(filename) {
    var fileref = document.createElement("link");
    fileref.setAttribute("rel", "stylesheet");
    fileref.setAttribute("type", "text/css");
    fileref.setAttribute("href", filename);
    document.getElementsByTagName("head")[0].appendChild(fileref);
}