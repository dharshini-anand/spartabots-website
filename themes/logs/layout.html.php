<!DOCTYPE html>
<html>
<head>
    <meta name=viewport content="width=device-width, initial-scale=1" />
	<meta name="theme-color" content="#24913E">
	<?php echo $head_contents ?>
	<link href="<?php echo site_url() ?>themes/logs/css/style.css" rel="stylesheet" />
	<link href="<?php echo site_url() ?>themes/logs/css/index-style.css" rel="stylesheet" />
    <link href="//fonts.googleapis.com/css?family=Roboto:400,100,300,700,900" rel="stylesheet" />
	<?php if (publisher()):?><link href="<?php echo publisher() ?>" rel="publisher" /><?php endif;?>
	<!--[if lt IE 9]>
		<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<script src="//ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
        <style>.ltIE9 { display: block; }</style>
	<![endif]-->
    <script>
    var jPM;
    var login_state = false;
    var loginStatePoll = function () {
        $.get('/api/is-logged-in', function(data) {
            var new_login_state = (data === 'true');
            changeLoginState(new_login_state);
        });
        
        setTimeout(loginStatePoll, 5000);
    };
	var dynamic_pages = false;

    $(document).ready(function() {
        $.get('/api/is-logged-in', function(data) { login_state = (data === 'true'); });
		if (dynamic_pages) {
			var replaceStateObj = { url: document.URL, path: window.location.pathname };
			history.replaceState(replaceStateObj, document.title, document.URL);
        }
		
        if (typeof String.prototype.startsWith != 'function') {
            String.prototype.startsWith = function (str){
                return this.slice(0, str.length) == str;
            };
        }
        if (typeof String.prototype.endsWith != 'function') {
            String.prototype.endsWith = function (str){
                return this.slice(-str.length) == str;
            };
        }
        
        $('.no-script').removeClass('no-script');
        
        // jPanelMenu stuff
        // --------------------------------------------------------------------------------
        jPM = $.jPanelMenu({
            menu: '#nav-menu-wrapper',
            trigger: '.fake-trigger',
            // the jPM library does not work properly when closing the menu on mobile, so here we're purposely giving it
            // the incorrect trigger and triggering the menu ourselves in the "onclick" attribute
            closeOnContentClick: true,
            keyboardShortcuts: false,
            duration: 350,
            openEasing: 'ease-out',
            closeEasing: 'ease-in'
        });
        jPM.on();
        
        // Anchor local or external
        // --------------------------------------------------------------------------------
		if (dynamic_pages) {
			$('a').each(function(index) {
				var url = $(this).attr('href');
				var path = $(this)[0].pathname;
				
				if (typeof url !== 'undefined' && typeof path !== 'undefined'){
					if (path.startsWith('/admin') || path.startsWith('/documents') || path.startsWith('/login') ||
							path.startsWith('/logout') || url.startsWith('#') || path.startsWith('/uploads') ||  path.startsWith('/documents') || 
							path.startsWith('/forgot-username') || path.startsWith('/forgot-password') || path.startsWith('/register-account') || 
							path.startsWith('/resend-verification') || path.startsWith('/verify-registration') || 
							url.startsWith('/#') || path.startsWith('/images') || url.startsWith('javascript:')) {
						// ...
					} else {
						if (isExternal(url)) {
							$(this).addClass('external');
						} else {
							$(this).addClass('local');
							$(this).click(function(event) {
								var e = event.originalEvent;
								if (!e.ctrlKey && !e.altKey && !e.shiftKey) {
									event.preventDefault();
									dynamic_page_load($(this).attr('href'), $(this)[0].pathname, true);
									$('html,body').scrollTop(0);
								}
							});
						}
					}
				};
			});
			
			// dynamic pages
			// --------------------------------------------------------------------------------
			$(window).bind("popstate", function(e) {
				var state = e.originalEvent.state;
				
				if (state) {
					// State: state.url
					dynamic_page_load(state.url, state.path, false);
				} else {
					// Initial state
				}
			});
		}
        
        // Menu close on resize
        // --------------------------------------------------------------------------------
        var prevWidth = $(window).width();
        $(window).resize(function () {
            var winWidth = $(window).width();
            if (prevWidth < 662 && winWidth >= 662) {
                $('#nav-menu').css({'margin-left':'-8%', 'opacity': '0'});
                $("#nav-menu").animate({'margin-left':'0px', 'opacity': '1'}, 500);
                if (jPM.isOpen()) {
                    closeMobileMenu(false);
                }
            }
            if (jPM.isOpen()) {
                updateMNavContentHeight();
            }
            
            prevWidth = winWidth;
        });
        
        // Placeholder fix
        // --------------------------------------------------------------------------------
        if ( !("placeholder" in document.createElement("input")) ) {
            $("input[placeholder], textarea[placeholder]").each(function() {
                var val = $(this).attr("placeholder");
                if ( this.value == "" ) {
                    this.value = val;
                }
                $(this).focus(function() {
                    if ( this.value == val ) {
                        this.value = "";
                    }
                }).blur(function() {
                    if ( $.trim(this.value) == "" ) {
                        this.value = val;
                    }
                })
            });

            // Clear default placeholder values on form submit
            $('form').submit(function() {
                $(this).find("input[placeholder], textarea[placeholder]").each(function() {
                    if ( this.value == $(this).attr("placeholder") ) {
                        this.value = "";
                    }
                });
            });
        }
        // Galleria
        // --------------------------------------------------------------------------------
        /*Galleria.loadTheme('<?php echo site_url() ?>system/plugins/galleria/themes/classic/galleria.classic.min.js');
        Galleria.configure({
            debug: false
        });
        Galleria.run('#main .galleria');
        $('#main .galleria').css('display', '');*/
        
        // Go to top
        // --------------------------------------------------------------------------------
        $("a[href='#top']").click(function(event) {
            $("html,body,.jPM-panel").animate({ scrollTop: 0 }, 'slow');
            event.preventDefault();
        });
        
        $("#main a[href^='#']").click(function(event) { // Fixes going to id with fixed menu
            event.preventDefault();
            
            if ($(this).attr("href") === '#top') {
                return;
            }
            
            try {
                var sT = $($(this).attr('href')).offset().top - $("#menu-wrapper").outerHeight(true); // get the fixedbar height
                // compute the correct offset and scroll to it.
                window.scrollTo(0, sT);
                
                history.pushState(null, document.title, $(this).attr("href"));
            } catch(err) {
                // Oh well, doesn't matter too much
            }
        });
        
        // Start login state poll [temporarily disabled]
        //loginStatePoll();
    });
    
    <?php menu_script() ?>
    
    function dynamic_page_load(url, path, shouldPushState) {
        $("#main-wrapper").load(path + " #main", function(response, status, xhr) {
        
            if (jPM.isOpen()) {
                closeMobileMenu(false);
            }
            
            playLoadingBar();
            var status_msg = xhr.status + " " + xhr.statusText;
            
            if (status == "success") {
                var dom = $(xhr.responseText);
				
                $('#title').load(path + ' #title', '', function(data) {
                    document.title = $(this).text();
                });
                $('#body-class-text-outer').load(path + ' #body-class-text', '', function(data) {
                    $('body').attr('class', $(this).text());
                });
                dom.find('script').each(function() {
                    $.globalEval(this.text || this.textContent || this.innerHTML || '');
                });
                
                if (shouldPushState) {
                    var stateObj = { url: url, path: path };
                    history.pushState(stateObj, document.title, url);
                }
                
                $('#main a').each(function(index) {
                    var url = $(this).attr('href');
                    var path = $(this)[0].pathname;
                    
                    if (typeof url !== 'undefined' && typeof path !== 'undefined'){
                        if (path.startsWith('/admin') || path.startsWith('/documents') || path.startsWith('/login') ||
                        path.startsWith('/logout') || url.startsWith('#') || path.startsWith('/uploads') ||  path.startsWith('/documents') || 
                        path.startsWith('/forgot-username') || path.startsWith('/forgot-password') || path.startsWith('/register-account') || 
                        path.startsWith('/resend-verification') || path.startsWith('/verify-registration') || 
                        url.startsWith('/#') || path.startsWith('/images') || url.startsWith('javascript:')) {
                            // ...
                        } else {
                            if (isExternal(url)) {
                                $(this).addClass('external');
                            } else {
                                $(this).addClass('local');
                                $(this).click(function(event) {
                                    var e = event.originalEvent;
                                    if (!e.ctrlKey && !e.altKey && !e.shiftKey) {
                                        event.preventDefault();
                                        dynamic_page_load($(this).attr('href'), $(this)[0].pathname, true);
                                        $('html,body').scrollTop(0);
                                    }
                                });
                            }
                        }
                    };
                });
                
                $('#main a.local').click(function(event) {
                    var e = event.originalEvent;
                    if (!e.ctrlKey && !e.altKey && !e.shiftKey) {
                        event.preventDefault();
                        dynamic_page_load($(this).attr('href'), $(this)[0].pathname, true);
                        $('html,body').scrollTop(0);
                    }
                });
                
                $("#main a[href='#top']").click(function(event) {
                    $("html,body,.jPM-panel").animate({ scrollTop: 0 }, 'slow');
                    event.preventDefault();
                });
                
                $("#main a[href^='#']").click(function(event) { // Fixes going to id with fixed menu
                    event.preventDefault();
                    
                    if ($(this).attr("href") === '#top') {
                        return;
                    }
                    
                    
                    try {
                        var o =  $( $(this).attr("href") ).offset();   
                        var sT = o.top - $("#menu-wrapper").outerHeight(true); // get the fixedbar height
                        // compute the correct offset and scroll to it.
                        window.scrollTo(0,sT);
                        
                        history.pushState(null, document.title, $(this).attr("href"));
                    } catch(err) {
                        // Oh well, doesn't matter too much
                    }
                });
                
                change_active_tab();
                /*
                Galleria.configure({
                    debug: false
                });
                Galleria.run('#main .galleria');
                $('#main .galleria').css('display', '');*/
                
                setTimeout(function() {
                    $.get('/api/is-logged-in', function(data) {
                        var new_login_state = (data === 'true');
                        changeLoginState(new_login_state);
                    });
                }, 0);
            } else if (status == "error") {
                $('#main').html('<div style="max-width:860px;width: 100%;margin:0 auto;padding-top:60px;box-sizing:border-box;-moz-box-sizing:border-box;"> \
                    <div style="font:60px/1.5 \'Segoe UI SemiLight\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; color: black">' + xhr.status + '</div> \
                    <div style="font:30px/1.5 \'Segoe UI SemiLight\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; color: rgb(153, 153, 153); margin-top: -18px">' + xhr.statusText + '</div> \
                    \
                    <p style="font:16px/1.5 \'Segoe UI SemiLight\', \'Helvetica Neue\', Helvetica, Arial, sans-serif;"> \
                    . \
                    </p> \
                    \
                    <p style="font:16px/1.5 \'Segoe UI SemiLight\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; margin-top: 25px;"> \
                    <a href="<?php echo site_url() ?>">Return to home</a> \
                    </p> \
                </div> \
                ');
            }
        });
    }
    
    function changeLoginState(new_login_state) {
        if (new_login_state != login_state) {
            login_state = new_login_state;
            if (new_login_state) {
                // Logged in
                site_alert('You\'ve been logged in', '<center style="margin-top:15px;"><p>You have logged in from another location (such as another browser tab).</p></center><center style="margin-top:70px;"><input value="Reload to see changes" type="button" onclick="window.location.reload()" /></center>');
            } else {
                // Logged out
                $('#user-box').remove();
                $('<a style="margin:8px" href="<?php echo site_url() ?>login">Member Login</a>').insertAfter('.m-user-box');
                $('.m-user-box').remove();
                
                $('<span><a href="<?php echo site_url() ?>login">Member Login</a></span>').insertAfter('#footer-logout-link');
                $('.footer-login-only').remove();
                site_alert('You\'ve been logged out', '<center style="margin-top:15px;"><p>You\'ve logged out from another location.</p></center><center style="margin-top:70px;"><input value="Reload to see changes" type="button" onclick="window.location.reload()" /></center>');
            }
        }
    }
    
    function isExternal(url) {
        if (url.startsWith('../') || url.startsWith('./')) {
            return true;
        }
        
        var match = url.match(/^([^:\/?#]+:)?(?:\/\/([^\/?#]*))?([^?#]+)?(\?[^#]*)?(#.*)?/);
        if (typeof match[1] === "string" && match[1].length > 0 && match[1].toLowerCase() !== location.protocol) return true;
        if (typeof match[2] === "string" && match[2].length > 0 && match[2].replace(new RegExp(":("+{"http:":80,"https:":443}[location.protocol]+")?$"), "") !== location.host) return true;
        return false;
    }
    
    function updateMNavContentHeight() {
        var content = $('#jPM-menu .m-nav-content');
        var contentInner = $('#jPM-menu .m-nav-content-inner');
        
        content.height($(window).height() - 50);
        if(content[0].offsetHeight < content[0].scrollHeight){
            // Has overflow
            if (!content.hasClass('m-nav-content-overflow')) {
                content.addClass('m-nav-content-overflow');
            }
        } else {
            // No overflow
            if (content.hasClass('m-nav-content-overflow')) {
                content.removeClass('m-nav-content-overflow');
            }
        }
    }
    
    function closeMobileMenu(anim) {
        //var viewport = $("meta[name=viewport]");
        //viewport.attr('content', 'width=device-width, initial-scale=1');
        jPM.close(anim);
    }
    
    function openMobileMenu(anim) {
        //var viewport = $("meta[name=viewport]");
        //viewport.attr('content', 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no');
        jPM.open(anim);
        updateMNavContentHeight();
    }
    
    function triggerMobileMenu(anim) {
        if (jPM.isOpen()) {
            closeMobileMenu(anim);
        } else {
            openMobileMenu(anim);
        }
    }
    function playLoadingBar() {
        $('#loading-bar').remove();
        $('#loading-bar-container').append('<div id="loading-bar"></div>');
    }
    
    function loadPage(page) {
        $('body').removeClass('page-loaded');
        playLoadingBar();
        $("#main").load(page, function() {
            $('body').addClass('page-loaded');
        });
    }
    
    function site_alert(title, content) {
        $('#site-alert #site-alert-title span').html(title);
        $('#site-alert #site-alert-content').html(content);
        $('#site-alert-outer').fadeIn('normal');
    }

    function site_alert_hide() {
        $('#site-alert-outer').fadeOut('normal');
    }
    
    </script>
</head>
<body class="<?php echo $bodyclass; ?>" itemscope="itemscope" itemtype="http://schema.org/WebPage">
    <div id="top"></div>
    <span id="body-class-text-outer" style="display:none"><span id="body-class-text"><?php echo $bodyclass; ?></span></span>
    <div class="hide" role="presentation">
        <meta content="<?php echo blog_title() ?>" itemprop="name"/>
        <meta content="<?php echo blog_description() ?>" itemprop="description"/>
    </div>
    <noscript style="position:fixed;z-index:9000;width:100%;bottom:0;">
        <div class="err-bar" style="padding:2px 8px;background:rgba(231, 58, 49, 0.7)">
            <b>Warning</b> This website may not work properly without JavaScript.
        </div>
        <style>.js-only { display: none; }</style>
    </noscript>
    <?php if(facebook()) { echo facebook();} ?>
    <div id="site-alert-outer">
        <div id="site-alert">
            <div id="site-alert-title"><span></span><i class="fa fa-times" onclick="site_alert_hide()"></i></div>
            <div id="site-alert-content"></div>
        </div>
    </div>
	<div id="cover">
		<div id="menu-wrapper" role="navigation">
			<div id="loading-bar-container"></div>
            <header id="header">
                <div id="header-logo"></div>
                <div class="fl">
                    <h1 id="header-site-title">Spartabots</h1>
                    <div id="header-sub-title">Team 2976</div>
                </div>
            </header>
            <div id="menu-main">
                <nav id="menu" class="fl">
                    <div id="m-nav-menu-bar">
                        <div class="fake-trigger" style="display:none"></div>
                        <div id="mobile-menu-main-trigger" class="menu-trigger" onclick="triggerMobileMenu(true); return false"></div>
                        <h2 class="m-nav-blog-title blog-title"><?php echo blog_title() ?></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div id="nav-menu-wrapper" class="nav-wrapper">
                        <div class="m-nav-header m-nav">
                            <h1 class="blog-title"><?php echo blog_title() ?></h1>
                        </div>
                        <div class="m-nav-content no-script mousescroll">
                            <?php echo menu() ?>
                            <div class="m-nav-extras m-nav">
                                <section class="social">
                                    <h3>Follow</h3>
                                    <?php echo social() ?>
                                </section>
                                <!--
                                <section class="archive">
                                    <h3>Archive</h3>
                                    <?php echo archive_list()?>
                                </section>
                                <section class="tagcloud">
                                    <h3>Tags</h3>
                                    <?php echo tag_cloud()?>
                                    <div class="clearfix"></div>
                                </section>
                                <section class="latesttweets">
                                    <?php echo latest_tweets() ?>
                                </section>-->
                                
                                <?php if (login()): ?>
                                <section class="m-user-box">
                                    <h3><i style="margin-right:10px" class="fa fa-user"></i><?php echo get_username() ?></h3>
                                    
                                    <ul class="m-user-box-main">
                                        <li><a href="<?php echo site_url() ?>member/<?php echo get_username() ?>">My Profile</a></li>
                                        <li><a href="<?php echo site_url() ?>my-account">My Account</a></li>
                                        <li><a href="<?php echo site_url() ?>my-preferences">My Preferences</a></li>
                                        <li style="border-top: 1px solid #f0f0f0;">
                                        <?php
                                        if (get_user_role() === 'admin') {
                                            echo '<a class="fl w50p" href="'. site_url() .'logout?continue='. current_url() .'">Logout</a>';
                                            echo '<a class="fr w50p" href="'. site_url() .'admin">Admin Panel</a>';
                                            echo '<div class="clearfix"></div>';
                                        } else if (get_user_role() === 'editor') {
                                            echo '<a class="fl w50p" href="'. site_url() .'logout?continue='. current_url() .'">Logout</a>';
                                            echo '<a class="fr w50p" href="'. site_url() .'admin">Editor Panel</a>';
                                            echo '<div class="clearfix"></div>';
                                        } else {
                                            echo '<a href="'. site_url() .'logout?continue='. current_url() .'">Logout</a>';
                                        }
                                        ?>
                                        </li>
                                    </ul>
                                </section>
                                
                                <?php else: echo '<a style="margin:8px" href="'. site_url() .'login?continue='. current_url() .'">Member Login</a>'; endif; ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </nav>
                <div id="nav-right-area" class="fr">
                    <?php if (login()):  ?>
                        <div id="user-box">
                            <?php if (!get_verified()): ?>
                            <div class="user-box-item" id="user-box-verify-notice">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span>Please verify your account</span>
                            </div>
                            <?php endif; ?>
                            <div class="user-box-item" id="user-box-main">
                                <i class="fa fa-user"></i>
                                <span id="user-box-username"><?php echo get_username() ?></span>
                                <i class="fa fa-caret-down"></i>
                                <ul id="user-box-main-dropdown">
                                    <li><a href="<?php echo site_url() ?>member/<?php echo get_username() ?>">My Profile</a></li>
                                    <li><a href="<?php echo site_url() ?>my-account">My Account</a></li>
                                    <li><a href="<?php echo site_url() ?>my-preferences">Preferences</a></li>
                                    <?php if (get_user_role() === 'admin'):
                                        echo '<li><a href="'. site_url() .'admin">Admin Panel</a></li>';
                                    elseif (get_user_role() === 'editor'):
                                        echo '<li><a href="'. site_url() .'admin">Editor Panel</a></li>';
                                    endif; ?>
                                    <li style="border-top: 1px solid #f0f0f0"><a href="<?php echo site_url() ?>logout?continue=<?php echo current_url()?>">Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    <?php else: ?>
                        <div id="user-box">
                            <a href="<?php echo site_url(); ?>login?continue=<?php echo current_url() ?>" class="user-box-item">
                                <span>Sign in</span>
                            </a>
                            <?php /*
                            <a href="<?php echo site_url(); ?>register-account" class="user-box-item">
                                <span>Register</span>
                            </a> */ ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="clearfix"></div>
            </div>
		</div>
		<div id="main-wrapper">
			<div id="main" <?php if (is_blog() || is_get_involved() || is_events_meetings()): ?>style="background:#f0f0f0"<?php endif; if (is_resource_page()): ?>style="background-image: -webkit-linear-gradient(38deg, #f2f2f2, #f2f2f2 75%, #f6f6f6 50%, #f6f6f6 100%);background-image: -moz-linear-gradient(38deg, #f2f2f2, #f2f2f2 75%, #f6f6f6 50%, #f6f6f6 100%);background-image: -ms-linear-gradient(38deg, #f2f2f2, #f2f2f2 75%, #f6f6f6 50%, #f6f6f6 100%);background-image: linear-gradient(38deg, #f2f2f2, #f2f2f2 75%, #f6f6f6 50%, #f6f6f6 100%);"<?php endif; ?>>
                <?php if (!empty($bar_crumb)):?>
                    <div class="bar-crumb">
                        <ul class="wrapper">
                        <?php
                        foreach ($bar_crumb as $crumb_item):
                            echo "<li>$crumb_item</li>";
                        endforeach;
                        ?>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                <?php endif;?>
                <?php echo content(); ?>
			</div>
		</div>
		<div id="footer-wrapper" role="contentinfo">
			<footer id="footer" class="">
                <div id="footer-row-1">
                    <div class="footer-blog-title"><?php echo blog_title() ?></div>
                </div>
                <div id="footer-row-2">
                    <span class="copyright"><?php echo copyright() ?></span>
                    <?php
                    if (login()) {
                        if (get_user_role() === 'admin') {
                            echo '<span class="footer-login-only"><a href="'. site_url() .'admin">Admin Panel</a></span>';
                            echo '<span id="footer-logout-link" class="footer-login-only"><a href="'. site_url() .'logout?continue='. current_url() .'">Logout</a></span>';
                        } else if (get_user_role() === 'editor') {
                            echo '<span class="footer-login-only"><a href="'. site_url() .'admin">Editor Panel</a></span>';
                            echo '<span id="footer-logout-link" class="footer-login-only"><a href="'. site_url() .'logout?continue='. current_url() .'">Logout</a></span>';
                        } else {
                            echo '<span id="footer-logout-link" class="footer-login-only"><a href="'. site_url() .'logout?continue='. current_url() .'">Logout</a></span>';
                        }
                    } else {
                        echo '<span><a href="'. site_url() .'login?continue='. current_url() .'">Member Login</a></span>';
                    }
                    ?>
                    <span><a href="<?php echo site_url() ?>about-us">About Us</a></span>
                    <span><a href="<?php echo site_url() ?>contact-us">Contact Us</a></span>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
			</footer>
		</div>
	</div>
	<?php if (analytics()):?><?php echo analytics() ?><?php endif;?>
    <!--<a href="#top" class="back-to-top"><i class="fa fa-chevron-up"></i></a>-->
</body>
</html>