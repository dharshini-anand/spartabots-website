$(document).ready(function() {
	// Replace select elements with custom select elements
	var custom_select = true;
	if (custom_select) {
        // Make container for all the select dropdown menus
        // ----------------------------------------------------------------------------------------------------
        var container = $(document.createElement("div"));
        container.attr("id", "select-opts-container");
        $("body").prepend(container);
        
        // Variables for right padding in options
        // ----------------------------------------------------------------------------------------------------
        var scrollbarWidth = customselect.getScrollbarWidth();
        
        // Initiate selects
        // ----------------------------------------------------------------------------------------------------
        $("select").each(function(index) {
            // Create select
            var select = $(document.createElement("div"));
			
            select.attr("class", $(this).attr("class"));
            select.addClass("select");
            select.attr("id", $(this).attr("id"));
            select.attr("data-select-opts", "select-" + index);
            select.attr("onchange", $(this).attr("onchange"));
            select.attr("onclick", $(this).attr("onclick"));
            select.attr("disabled", $(this).attr("disabled"));
            select.attr("hidden", $(this).attr("hidden"));
            select.attr("lang", $(this).attr("lang"));
            select.attr("spellcheck", $(this).attr("spellcheck"));
            select.attr("style", $(this).attr("style"));
            select.attr("translate", $(this).attr("translate"));
            select.attr("title", $(this).attr("title"));
            select.attr("required", $(this).attr("required"));
            
            // Create the "actual" select input containg value sent to form
            var realSelect = $(document.createElement("input"));
            realSelect.attr("class", "select-actual-input");
            realSelect.attr("type", "hidden");
            realSelect.attr("name", $(this).attr("name"));
            realSelect.attr("form", $(this).attr("form"));
            realSelect.attr("required", $(this).attr("required"));
            
            // Create down arrow in select
            var arrow = $(document.createElement("div"));
            arrow.addClass("select-arrow-down");
            arrow.css({"float" : "right", "margin" : "9px 4px"});
            select.append(arrow);
            
            // Current text/title of select and value
            var title = $(document.createElement('span'));
            title.addClass('select-title');
            var value;
            
            // Create dropdown menu for the options container and resize handle
            var opts = $(document.createElement('div'));
            opts.addClass('select-opts');
            opts.attr('id', 'select-opt-' + index);
            opts.css('opacity', '0');
            opts.click(function(e) {
                e.stopPropagation();
            });
            
            // Create container for options
            var opts_container = $(document.createElement('div'));
            opts_container.addClass('select-opts-container');
            opts_container.on('DOMMouseScroll mousewheel', function (event) {
                var moveBy = $(this).find('.opt').eq(0).outerHeight();
                if(event.originalEvent.detail > 0 || event.originalEvent.wheelDelta < 0) {
                    //scroll down
                    this.scrollTop += (moveBy);
                } else {
                    //scroll up
                    this.scrollTop -= (moveBy);
                }
                event.preventDefault();return false;
            });
            opts.append(opts_container);
            
            // Create resize handle
            var opts_resize_handle = $(document.createElement('div'));
            opts_resize_handle.addClass('select-opts-handle');
            opts_resize_handle.addClass('opt');
            opts_resize_handle.html('<center>&bull;&bull;&bull;&bull;</center>');
            opts_resize_handle.mousedown(function(e) {
                customselect.handleMouseDown = true;
                customselect.startY = e.pageY;
                customselect.optsResizing = $(this).parent().children('.select-opts-container');
                customselect.startHeight = customselect.optsResizing.outerHeight();
                $('body').addClass('select-resize-no-select'); // Make it so that nothing can be selected while resizing until mouse goes back up
            });
            opts_resize_handle.dblclick(function() {
                opts_container.css('height', '');
            });
            opts.append(opts_resize_handle);
            
            // Create required error
            var req_err = $(document.createElement('div'));
            req_err.addClass('select-req-err');
            req_err.html("This field is required.");
            
            // Title and value stuff
            var titleFound = false;
            var titleAttr = $(this).attr('data-title');
            if (typeof titleAttr !== 'undefined' && titleAttr !== false) {
                titleFound = true;
                title.html(titleAttr);
                value = "";
                select[0]['selectedIndex'] = -1;
            }
            
            // Create each select option
            $(this).children('option').each(function(index) {
                var opt = $(document.createElement("div"));
                opt.attr('value', $(this).attr('value'));
                opt[0]['value'] = $(this).attr('value');
                opt[0]['text'] = $(this).html();
                opt.attr('style', $(this).attr('style'));
                opt.attr('class', $(this).attr('class'));
                opt.attr('id', $(this).attr('id'));
                opt.addClass("opt");
                opt.css('margin-right', (scrollbarWidth+1) + 'px'); // Add right margin to accommodate for scrollbar width
                
                if ($(this).hasClass("opt-header")) {
                    opt.addClass("opt-header");
                }
                opt.html($(this).html());
                opts_container.append(opt);
                
                if (!titleFound) {
                    var attr = $(this).attr('selected');
                    if (typeof attr !== 'undefined' && attr !== false) {
                        titleFound = true;
                        title.html($(this).html());
                        value = $(this).attr('value');
                        select[0]['selectedIndex'] = index;
                    }
                }
            });
            // Title and value stuff
            if (!titleFound) {
                title.html($(this).find('option:first-child').html());
                value = $(this).find('option:first-child').attr('value');
                select[0]['selectedIndex'] = 0;
            }
            realSelect.attr('value', value);
            
            // Add the select dropdown menu to the dropdown menu container
            container.append(opts);
            
            // Add stuff to the select and set some variables
            select.prepend(title);
            select.append(realSelect);
            select.append(req_err);
            select.insertAfter(this);
            select[0]['form'] = select.closest('form');
			
            // Create helper functions for the options collection
			var options = $.extend($('#select-opt-' + index + ' .opt'), {
				add: function(obj, index) {
					customselect.attachOptionClickListener(obj);
					if (index === undefined) {
						$(this).closest('.select-opts-container').append(obj);
					} else {
						$(this).closest('.select-opts-container').children().eq(index).before(obj);
					}
				},
				remove: function(index) {
					$(this).closest('.select-opts-container').children().eq(index).remove();
				}
			});
            
            // Add some variables to the select
            select[0]['options'] = options;
            // todo: http://www.w3schools.com/jsref/dom_obj_select.asp
            
            // Remove the original select
            $(this).remove();
        });
        
        // Validate form to make sure required selects have been filled out
        // ----------------------------------------------------------------------------------------------------
        $('form').submit(function(event) {
            $('.select input').each(function(index) {
                if ($(this).parent().attr('required') !== undefined) {
                    if ($(this).val() === '') {
                        var err = $(this).parent().find('.select-req-err');
                        if (!err.is(':visible')) {
                            err.fadeIn(100).delay(3000).fadeOut(300);
                        }
                        event.preventDefault();
                    }
                }
            });
        });
        
        // Open/close select items
        // ----------------------------------------------------------------------------------------------------
        $('.select').click(function(event) {
            var select = $(this);
            
            if (select.attr('disabled') !== undefined) {
                return;
            }
            
            event.stopPropagation();
            var thisOpts = document.getElementById("select-opt-" + $(this).attr("data-select-opts").split("-")[1]);
            var opts = $(thisOpts);
            
            $(".select-opts").each(function() { // Close other selects
                if (this != thisOpts) {
                    var opts2 = $(this);
                    if (opts2.hasClass("open")) {
                        opts2.fadeTo(50, 0);
                        setTimeout(function(){ opts2.removeClass("open"); }, 45);
                    }
                }
            });
            
            if (opts.hasClass("open")) {
                opts.fadeTo(50, 0);
                setTimeout(function(){ opts.removeClass("open"); }, 50);
            } else {
                opts.css("top", select.offset().top + select.outerHeight() + 4);
                opts.css("left", select.offset().left);
                opts.addClass("open");
                opts.fadeTo(50, 1);
                
                setTimeout(function() {
                    // re-position dropdown if body has a scrollbar (in case body did not have scrollbar before opening
                    // dropdown but body has scrollbar after opening dropdown)
                    if ($('body').get(0).scrollHeight > $('body').get(0).clientHeight) {
                        opts.css("top", select.offset().top + 2);
                        opts.css("left", select.offset().left);
                    }
                }, 0);
            }
        });
        
        // Re-position open select options container if window is resized
        // ----------------------------------------------------------------------------------------------------
        $(window).resize(function() {
            $(".select-opts").each(function() {
                var opts = $(this);
                var select = $("[data-select-opts=select-" + opts.attr("id").split("-")[2]+"]");
                if (opts.hasClass("open")) {
                    opts.css("top", select.offset().top + select.outerHeight() + 4);
                    opts.css("left", select.offset().left);
                }
            });
        });
        
        // Attach click listener to options
        // ----------------------------------------------------------------------------------------------------
        customselect.attachOptionClickListener($(".select-opts .opt")); 
        
        // Close select options container when body clicked
        // ----------------------------------------------------------------------------------------------------
        $(document).click(function(event) {
            $(".select-opts").removeClass("open");
        });
        
        // Resizing Stuff
        // ----------------------------------------------------------------------------------------------------
        $(document).mousemove(function(e) {
            if (customselect.handleMouseDown) {
                var newHeight = customselect.startHeight + (e.pageY - customselect.startY);
                if (newHeight < customselect.globalMinHeight) {
                    return;
                }
                $(customselect.optsResizing).height(newHeight);
            }
        });

        $(document).mouseup(function(e) {
            if (customselect.handleMouseDown) {
                customselect.handleMouseDown = false;
                
                customselect.optsResizing = undefined;
                $('body').removeClass('select-resize-no-select');
            }
        });
	}
});


// Class for providing useful functions and storing needed variables
// ----------------------------------------------------------------------------------------------------
function CustomSelect(){
    // Don't need to have these variables for each select because there usually aren't multiple mouses resizing multiple
    // selects at the same time.
    this.handleMouseDown = false;
    this.handleStart = undefined;
    this.optsResizing = undefined;
    this.startY;
    this.startHeight;
    this.globalMinHeight = 0; // Not including padding
}
CustomSelect.prototype.getScrollbarWidth = function() {
    // From http://stackoverflow.com/questions/13382516/getting-scroll-bar-width-using-javascript
    var outer = document.createElement("div");
    outer.style.visibility = "hidden";
    outer.style.width = "100px";
    outer.style.msOverflowStyle = "scrollbar"; // needed for WinJS apps

    document.body.appendChild(outer);

    var widthNoScroll = outer.offsetWidth;
    // force scrollbars
    outer.style.overflow = "scroll";

    // add innerdiv
    var inner = document.createElement("div");
    inner.style.width = "100%";
    outer.appendChild(inner);        

    var widthWithScroll = inner.offsetWidth;

    // remove divs
    outer.parentNode.removeChild(outer);

    return widthNoScroll - widthWithScroll;
}
CustomSelect.prototype.attachOptionClickListener = function(obj) {
	obj.click(function() {
		if ($(this).hasClass("opt-header") || $(this).hasClass("select-opts-handle")) {
			// Do nothing
		} else {
            console.log($(this).html());
			var id = "[data-select-opts=select-" + $(this).closest(".select-opts").attr("id").split("-")[2]+"]";
			$(id + " .select-title").html($(this).html());
			$(id + " input").attr('value', $(this).attr('value'));
			$(id)[0]['selectedIndex'] = Array.prototype.indexOf.call($(this)[0].parentNode.childNodes, $(this)[0]);
			
			var err = $(id + ' .select-req-err');
			if (err.is(':visible')) {
				err.hide();
			}
			
			$(id).trigger("change");
            $(".select-opts").removeClass("open");
		}
	});
}
CustomSelect.prototype.createOption = function(text, value){
	var opt = $(document.createElement("div"));
	opt.addClass("opt");
	opt.attr('value', value);
	opt.html(text);
	opt[0]['value'] = value;
	opt[0]['text'] = text;
	return opt;
}

CustomSelect.prototype.createOptionHeader = function(text){
	var opt = $(document.createElement("div"));
	opt.addClass("opt");
	opt.addClass("opt-header");
	opt.attr('value', '');
	opt.html(text);
	opt[0]['value'] = '';
	opt[0]['text'] = text;
	return opt;
}

var customselect = new CustomSelect();