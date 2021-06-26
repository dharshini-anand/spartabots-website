<div class="wrapper responsive" id="blog-wrapper">
<?php if (!empty($breadcrumb)):?><div class="breadcrumb"><?php echo $breadcrumb ?></div><?php endif;?>
<section id="blog-content" role="main">
    <?php /*if(login()) { echo editing_tabs($p);}*/ ?>
    <div class="blog-post post" itemprop="blogPost" itemscope="itemscope" itemtype="http://schema.org/BlogPosting">
        <div class="main">
            <a name="more"></a>
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
            <h1 class="title-post" itemprop="name"><?php echo $p->title ?></h1>
            <div class="post-body" itemprop="articleBody">
                <?php echo $p->body; ?>
            </div>
        </div>
        <!-- <div class="separator">&rarr;</div> -->
        <div class="share-box-outer">
            <div class="share-box">
                <?php echo $authorinfo ?>
                <div class="share">
                    <h4>Share this post</h4>
                    <a class="twitter" target="_blank" href="https://twitter.com/share?url=<?php echo $p->url ?>&text=<?php echo $p->title?>">Twitter</a>
                    <a class="facebook" target="_blank" href="https://www.facebook.com/sharer.php?u=<?php echo $p->url ?>&t=<?php echo $p->title?>">Facebook</a>
                    <a class="googleplus" target="_blank" href="https://plus.google.com/share?url=<?php echo $p->url ?>">Google+</a>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <?php echo get_related($p->tag)?>
        <hr class="post-body-separator" />
        <div id="comments" class="comments">
            <?php if (facebook()):?>
                <div class="fb-comments" data-href="<?php echo $p->url ?>" data-numposts="<?php echo config('fb.num') ?>" data-colorscheme="<?php echo config('fb.color') ?>"></div>
            <?php endif;?>
            <?php if (disqus()):?>
                <div id="disqus_thread"></div>
            <?php endif;?>
        </div>
        <div class="postnav-outer">
            <div class="postnav">
                <?php if (!empty($next)):?>
                    <span class="newer">&laquo; <a href="<?php echo ($next['url']);?>" rel="next"><?php echo ($next['title']);?></a></span>
                <?php endif;?>

                <?php if (!empty($prev)):?>
                    <span class="older" ><a href="<?php echo ($prev['url']); ?>" rel="prev"><?php echo ($prev['title']); ?></a> &raquo;</span>
                <?php endif;?>
            </div>
        </div>
        <?php if (disqus()):?>
            <div id="disqus-message"><?php echo disqus($p->title, $p->url) ?></div>
        <?php endif;?>
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