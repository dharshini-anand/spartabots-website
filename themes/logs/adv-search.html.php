<div class="wrapper responsive" id="blog-wrapper">
<?php if (!empty($breadcrumb)):?><div class="breadcrumb"><?php echo $breadcrumb ?></div><?php endif;?>
<section id="blog-content" role="main">
    <div class="blog-post post first">
        <div style="padding:20px">
        <h2 style="margin-top:0;">Advanced Search</h2>
        <form style="margin-top:20px" method="POST">
            <h3>Find posts with...</h3>
            
            <div class="advanced-search-field">
                <div class="fl adv-search-field-name">all these words</div>
                <div class="fl adv-search-field-input">
                    <input name="search-keywords-all" type="text"/>
                </div>
                <div class="clearfix"></div>
            </div>
            
            <div class="advanced-search-field">
                <div class="fl adv-search-field-name">this exact phrase</div>
                <div class="fl adv-search-field-input">
                    <input name="search-required-phrase" type="text"/>
                </div>
                <div class="clearfix"></div>
            </div>
            
            <div class="advanced-search-field">
                <div class="fl adv-search-field-name">any of these words</div>
                <div class="fl adv-search-field-input">
                    <input name="search-keywords-any" type="text"/>
                </div>
                <div class="clearfix"></div>
            </div>
            
            <div class="advanced-search-field">
                <div class="fl adv-search-field-name">none of these words</div>
                <div class="fl adv-search-field-input">
                    <input name="search-keywords-none" type="text"/>
                </div>
                <div class="clearfix"></div>
            </div>
            
            <div class="advanced-search-field">
                <div class="fl adv-search-field-name">this author</div>
                <div class="fl adv-search-field-input">
                    <input name="search-required-author" type="text"/>
                </div>
                <div class="clearfix"></div>
            </div>
            
            <div class="advanced-search-field">
                <div class="fl adv-search-field-name">this tag</div>
                <div class="fl adv-search-field-input">
                    <input name="search-required-tag" type="text"/>
                </div>
                <div class="clearfix"></div>
            </div>
            
            <h3 style="margin-top:20px;display:inline-block">And with titles with...</h3>
            
            <div class="advanced-search-field">
                <div class="fl adv-search-field-name">all these words</div>
                <div class="fl adv-search-field-input">
                    <input name="search-keywords-all-title" type="text"/>
                </div>
                <div class="clearfix"></div>
            </div>
            
            <div class="advanced-search-field">
                <div class="fl adv-search-field-name">this exact phrase</div>
                <div class="fl adv-search-field-input">
                    <input name="search-required-phrase-title" type="text"/>
                </div>
                <div class="clearfix"></div>
            </div>
            
            <div class="advanced-search-field">
                <div class="fl adv-search-field-name">any of these words</div>
                <div class="fl adv-search-field-input">
                    <input name="search-keywords-any-title" type="text"/>
                </div>
                <div class="clearfix"></div>
            </div>
            
            <div class="advanced-search-field">
                <div class="fl adv-search-field-name">none of these words</div>
                <div class="fl adv-search-field-input">
                    <input name="search-keywords-none-title" type="text"/>
                </div>
                <div class="clearfix"></div>
            </div>
            
            <div class="special-btn">
                <input type="submit" value="Search" />
                <i class="special-btn-icon fa fa-search"></i>
            </div>
        </form>
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
</section>