<div class="wrapper responsive">
<?php if (!empty($breadcrumb)):?><div class="breadcrumb"><?php echo $breadcrumb ?></div><?php endif;?>

<div id="about-pages-wrapper">
    <div id="about-pages-nav">
        <ul>
            <li>
                <a href="<?php echo site_url() ?>about-us/overview">Club overview</a>
                <a href="<?php echo site_url() ?>about-us/who">Who we are</a>
                <ul>
                    <li><a href="<?php echo site_url() ?>about-us/school">Our school</a></li>
                    <li><a href="<?php echo site_url() ?>about-us/location">Location & nearby teams</a></li>
                    <li><a href="<?php echo site_url() ?>about-us/members">Members & Mentors</a></li>
                </ul>
            </li>
            <li><a href="<?php echo site_url() ?>about-us/what">What we do</a></li>
            <li><a href="<?php echo site_url() ?>about-us/community">Community</a></li>
            <li><a href="<?php echo site_url() ?>about-us/story">Our story</a></li>
            <li><a href="<?php echo site_url() ?>about-us/website">Website</a></li>
        </ul>
    </div>
    <div id="about-pages-article">
        <h2 id="about-pages-article-title"><?php echo $page_name ?></h2>
        <div id="about-pages-article-content"><?php echo $page_content ?></div>
    </div>
    <div class="clearfix"></div>
</div>
</div>