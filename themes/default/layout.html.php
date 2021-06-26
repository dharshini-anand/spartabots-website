<!DOCTYPE html>
<html>
<head>
	<?php echo $head_contents ?>
	<link href="<?php echo site_url() ?>themes/default/css/style.css" rel="stylesheet" />
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
	<?php if (publisher()):?><link href="<?php echo publisher() ?>" rel="publisher" /><?php endif;?>
	<!--[if lt IE 9]>
		<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
        <style>.ltIE9 { display: block; }</style>
	<![endif]-->
    <script>
    $(document).ready(function() {
        // Placeholder fix
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
        // End placeholder fix
    });
    </script>
</head>
<body class="<?php echo $bodyclass; ?>" itemscope="itemscope" itemtype="http://schema.org/Blog">
	<div class="hide">
		<meta content="<?php echo blog_title() ?>" itemprop="name"/>
		<meta content="<?php echo blog_description() ?>" itemprop="description"/>
	</div>
	<?php if(facebook()) { echo facebook();} ?>
	<?php if(login()) { toolbar();} ?>
	<div id="outer-wrapper">
		<div id="menu-wrapper">
			<div class="container">
				<nav id="menu">
					<?php echo menu() ?>
					<?php echo search() ?>
				</nav>
			</div>
		</div>
		<div id="header-wrapper">
			<div class="container">
				<header id="header">
					<section id="branding">
						<?php if(is_index()) {?>
							<h1 class="blog-title"><a rel="home" href="<?php echo site_url() ?>"><?php echo blog_title() ?></a></h1>
						<?php } else {?>
							<h2 class="blog-title"><a rel="home" href="<?php echo site_url() ?>"><?php echo blog_title() ?></a></h2>
						<?php } ?>
						<div class="blog-tagline"><p><?php echo blog_tagline() ?></p></div>
					</section>
				</header>
			</div>
		</div>
		<div id="content-wrapper">
			<div class="container">
				<section id="content">
					<?php echo content()?>
				</section>
			</div>
		</div>
		<div id="footer-wrapper">
			<div class="container">
				<footer id="footer">
					<div class="footer-column">
						<div class="archive column"><div class="inner"><?php echo archive_list()?></div></div>
						<div class="tagcloud column"><div class="inner"><?php echo tag_cloud()?></div></div>
						<div class="social column"><div class="inner"><h3>Follow</h3><?php echo social()?></div></div>
					</div>
					<div class="copyright"><?php echo copyright() ?></div>
				</footer>
			</div>
		</div>
	</div>
	<?php if (analytics()):?><?php echo analytics() ?><?php endif;?>
</body>
</html>