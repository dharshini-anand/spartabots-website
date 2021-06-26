<!DOCTYPE html>
<html style="background:#fbfbfb;">
<head>
	<?php echo $head_contents ?>
	<link href="<?php echo site_url() ?>themes/default/css/style.css" rel="stylesheet" />
	<link href="<?php echo site_url() ?>system/admin/admin.css" rel="stylesheet" />
	<link href="//fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet" type="text/css">
    <link href="//fonts.googleapis.com/css?family=Roboto:400,100,300,700,900" rel="stylesheet" />
	<?php if (publisher()):?><link href="<?php echo publisher() ?>" rel="publisher" /><?php endif;?>
	<!--[if lt IE 9]>
		<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
    <style>
    .blog-title a {
        color: #858585 !important;
        font-family: 'PT Serif', Georgia, Cambria, 'Times New Roman', Times, serif;
    }
    .blog-tagline {
        display: none;
    }
    </style>
    <script>
    function admin_alert(title, content) {
        $('#admin-alert #admin-alert-title').html(title);
        $('#admin-alert #admin-alert-content').html(content);
        $('#admin-alert').fadeIn('normal');
    }

    function admin_alert_hide() {
        $('#admin-alert').fadeOut('normal');
    }
    </script>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Source+Sans+Pro%3A300%2C400%2C600%2C700%2C300italic%2C400italic%2C600italic%2C700italic&amp;ver=4.0-alpha" type="text/css" media="all">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=PT+Serif%3A400%2C700%2C400italic%2C700italic&amp;ver=4.0-alpha" type="text/css" media="all">
</head>
<body class="admin <?php echo $bodyclass; ?>" itemscope="itemscope" itemtype="http://schema.org/Blog">
	<div class="hide">
		<meta content="<?php echo blog_title() ?>" itemprop="name"/>
		<meta content="<?php echo blog_description() ?>" itemprop="description"/>
	</div>
    <div id="overall-admin-header">
        <div class="fl">
            <span style="padding-left:10px">Spartabots Admin Panel</span>
            <span><a href="<?php echo site_url() ?>">Site Index</a></span>
        </div>
        <div class="fr">
            <span>Welcome, <?php echo get_username(); ?>!</span>
            <span><a href="<?php echo site_url() ?>my-account">My Account</a></span>
            <span><a href="<?php echo site_url() ?>logout">Logout</a></span>
        </div>
        <div class="clearfix"></div>
    </div>
	<div id="overall-admin">
        <?php if(login()) { admin_navigation();} else { ?>
		<style>
		#admin-panes {
			width: 100%;
		}
		.admin-pane section {
			width: 50%;
			min-width: 185px;
			margin: 30px auto;
		}
		</style>
		<?php } ?>
        
        <div id="admin-panes">
            <div class="admin-pane active">
                <?php echo content()?>
                <div class="clearfix"></div>
			</div>
		</div>
        <div class="clearfix"></div>
	</div>
    <div class="clearfix"></div>
    <div id="admin-alert">
        <h2 id="admin-alert-title"></h2>
        <div id="admin-alert-content"></div>
        <div id="admin-alert-options">
            <input type="button" value="Ok" onclick="admin_alert_hide()" />
        </div>
    </div>
	<?php if (analytics()):?><?php echo analytics() ?><?php endif;?>
</body>
</html>