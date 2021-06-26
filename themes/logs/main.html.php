<div class="wrapper responsive" id="blog-wrapper">
<?php if (!empty($breadcrumb)):?><div class="breadcrumb"><?php echo $breadcrumb ?></div><?php endif;?>
<section id="blog-content" role="main">
    <?php $i = 0; $len = count($posts);?>
    <?php foreach($posts as $p):?>
        <?php
            if ($i == 0) {
                $class = 'blog-post post first';
            } 
            elseif ($i == $len - 1) {
                $class = 'blog-post post last';
            }
            else {
                $class = 'blog-post post';
            }
            $i++;	
        ?>
        <div class="<?php echo $class ?>" itemprop="blogPost" itemscope="itemscope" itemtype="http://schema.org/BlogPosting">
            <div class="main">
                <div class="post-info">
                    <div class="fl w25p">
                        <div class="fa fa-user fl"></div>
                        <div class="fl">
                            <div class="post-info1">Posted by</div>
                            <div class="post-info2" itemprop="author"><a href="<?php echo $p->authorurl ?>"><?php echo $p->author ?></a></div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="fl w25p">
                        <div class="fa fa-clock-o fl"></div>
                        <div class="fl">
                            <div class="post-info1">Posted on</div>
                            <div class="post-info2" itemprop="datePublished"><?php echo date('d F Y', $p->date)?></div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="fl w25p">
                        <div class="fa fa-tag fl"></div>
                        <div class="fl">
                            <div class="post-info1">Posted under</div>
                            <div class="post-info2" itemprop="articleSection"><?php echo $p->tag ?></div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="fl w25p">
                        <div class="fa fa-comment fl"></div>
                        <div class="fl">
                            <?php if (disqus_count()): ?>
                                <div class="post-info1"><a href="<?php echo $p->url?>#disqus_thread">Comments</a></div>
                            <?php elseif (facebook()): ?>
                                <div class="post-info1"><a href="<?php echo $p->url ?>#comments">
                                    <span><fb:comments-count href=<?php echo $p->url ?>></fb:comments-count> Comments</span></a></div>
                            <?php endif; ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <h2 class="title-index" itemprop="name"><a href="<?php echo $p->url?>"><?php echo $p->title ?></a></h2>
                <div class="teaser-body" itemprop="articleBody">
                    <?php echo get_thumbnail($p->body)?>
                    <?php echo get_teaser($p->body, $p->url)?>
                </div>
            </div>
        </div>
    <?php endforeach;?>
    <?php if (!empty($pagination['prev']) || !empty($pagination['next'])):?>
        <div class="pager">
            <?php if (!empty($pagination['prev'])):?>
                <span class="newer" >&laquo; <a href="?page=<?php echo $page-1?>" rel="prev">Newer</a></span>
            <?php endif;?>
            <?php if (!empty($pagination['next'])):?>
                <span class="older" ><a href="?page=<?php echo $page+1?>" rel="next">Older</a> &raquo;</span>
            <?php endif;?>
        </div>
    <?php endif;?>
    <?php if (disqus_count()):?>
        <?php echo disqus_count() ?>
    <?php endif;?>
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