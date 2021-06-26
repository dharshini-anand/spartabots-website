<div class="wrapper responsive">
<?php if (!empty($breadcrumb)):?><div class="breadcrumb" style="position:absolute"><?php echo $breadcrumb ?></div><?php endif;?>
<section id="blog-content" role="main">
    <div class="blog-post post first">
        <div style="padding:20px">
            <span style="font-size:18px">No search results found.</span>
        </div>
    </div>
</section>
<aside id="sidebar" role="complementary">
    <div id="sidebar-inner">
        <div class="sidebar-search">
            <?php echo search() ?>
        </div>
        <div class="social">
            <h3>Follow</h3>
            <?php echo social() ?>
        </div>
        <div class="archive">
            <h3>Archive</h3>
            <?php echo archive_list()?>
        </div>
        <?php if(disqus()):?>
            <!--<div class="comments" style="display:none">
                <?php echo recent_comments() ?>
            </div>-->
        <?php endif;?>
        <div class="tagcloud">
            <h3>Tags</h3>
            <?php echo tag_cloud()?>
        </div>
        <div class="latesttweets">
            <?php echo latest_tweets() ?>
        </div>
    </div>
</aside>
<div class="clearfix"></div>
</div>