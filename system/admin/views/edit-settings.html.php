<script>
function submitSettingsForm() {
    var content = "";
    $('.settings-list-item').each(function() {
        content += $(this).attr('data-key') + ' = "' + $(this).find('.settings-list-value').val() + '"\n';
    });
    console.log(content);
    $.post( "/admin/settings", { content: content }, function(data) {
        $('#settings-form-status').html(data);
    });
}
</script>
<section>
<h2><?php echo $heading?></h2>
<?php
if (!empty($error)) {
    echo("Error: $error");
}

$settings_data = parse_ini_file('config/config.ini');
?>

<div id="settings-form-status"></div>

<form id="settings-form" onsubmit="submitSettingsForm();return false">
<div class="settings-list">
    <div class="settings-list-header">Website settings</div>
    
    <div class="settings-list-item" data-key="site.url">
        <div class="fl">
            <div class="settings-list-key">Website URL</div>
            <div class="settings-list-info">The URL of the website. Include the 'http://www.' or 'https://www.'</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['site.url'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="blog.title">
        <div class="fl">
            <div class="settings-list-key">Website Title</div>
            <div class="settings-list-info">Title of the website</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['blog.title'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="blog.tagline">
        <div class="fl">
            <div class="settings-list-key">Tagline</div>
            <div class="settings-list-info">Blog tagline (not in use)</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['blog.tagline'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="blog.description">
        <div class="fl">
            <div class="settings-list-key">Website description</div>
            <div class="settings-list-info">A brief description of the website (shouldn't be very long)</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['blog.description'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="blog.copyright">
        <div class="fl">
            <div class="settings-list-key">Website copyright</div>
            <div class="settings-list-info">The website copyright information</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['blog.copyright'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="blog.menu">
        <div class="fl">
            <div class="settings-list-key">Website menu</div>
            <div class="settings-list-info">Website menu</div>
        </div>
        <div class="fr">
            Please use <a target="_blank" href="http://www.spartabots.org/admin/edit-nav">this tool</a> instead to edit the menu.
            <input class="settings-list-value" type="hidden" value="<?php echo $settings_data['blog.menu'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="breadcrumb.home">
        <div class="fl">
            <div class="settings-list-key">Breadcrumb home</div>
            <div class="settings-list-info">Breadcrumb home text. Useful when installed on subfolder.</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['breadcrumb.home'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>
    <div class="settings-list-header">Social Links</div>

    <div class="settings-list-item" data-key="social.twitter">
        <div class="fl">
            <div class="settings-list-key">Twitter link</div>
            <div class="settings-list-info">Twitter social link</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['social.twitter'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="social.facebook">
        <div class="fl">
            <div class="settings-list-key">Facebook link</div>
            <div class="settings-list-info">Facebook social link</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['social.facebook'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="social.google">
        <div class="fl">
            <div class="settings-list-key">Google+ link</div>
            <div class="settings-list-info">Google+ social link</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['social.google'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="social.tumblr">
        <div class="fl">
            <div class="settings-list-key">Tumblr</div>
            <div class="settings-list-info">Tumblr social link</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['social.tumblr'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>
    <div class="settings-list-header">Comments</div>

    <div class="settings-list-item" data-key="comment.system">
        <div class="fl">
            <div class="settings-list-key">Comment system</div>
            <div class="settings-list-info">Comment system. Choose "facebook", "disqus", or "disable"</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['comment.system'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="fb.appid">
        <div class="fl">
            <div class="settings-list-key">Facebook App ID</div>
            <div class="settings-list-info">Facebook comments</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['fb.appid'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="fb.num">
        <div class="fl">
            <div class="settings-list-key">Facebook comment number</div>
            <div class="settings-list-info">Facebook comment number</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['fb.num'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="fb.color">
        <div class="fl">
            <div class="settings-list-key">Facebook color</div>
            <div class="settings-list-info">Facebook color</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['fb.color'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="disqus.shortname">
        <div class="fl">
            <div class="settings-list-key">Disqus short name</div>
            <div class="settings-list-info">Disqus short name</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['disqus.shortname'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="google.publisher">
        <div class="fl">
            <div class="settings-list-key">Google+ publisher</div>
            <div class="settings-list-info">Google+ publisher</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['google.publisher'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="google.analytics.id">
        <div class="fl">
            <div class="settings-list-key">Google analytics</div>
            <div class="settings-list-info">Google analytics</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['google.analytics.id'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>
    <div class="settings-list-header">Pagination, RSS, JSON</div>

    <div class="settings-list-item" data-key="posts.perpage">
        <div class="fl">
            <div class="settings-list-key">Posts per page</div>
            <div class="settings-list-info"></div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['posts.perpage'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="tag.perpage">
        <div class="fl">
            <div class="settings-list-key">Tags per page</div>
            <div class="settings-list-info"></div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['tag.perpage'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="archive.perpage">
        <div class="fl">
            <div class="settings-list-key">Archive per page</div>
            <div class="settings-list-info"></div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['archive.perpage'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="search.perpage">
        <div class="fl">
            <div class="settings-list-key">Search per page</div>
            <div class="settings-list-info"></div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['search.perpage'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="profile.perpage">
        <div class="fl">
            <div class="settings-list-key">Profiles per page</div>
            <div class="settings-list-info"></div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['profile.perpage'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="json.count">
        <div class="fl">
            <div class="settings-list-key">JSON count</div>
            <div class="settings-list-info"></div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['json.count'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="related.count">
        <div class="fl">
            <div class="settings-list-key">Related posts</div>
            <div class="settings-list-info">Related posts</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['related.count'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="author.info">
        <div class="fl">
            <div class="settings-list-key">Display author info</div>
            <div class="settings-list-info">Author info on blog post. Set "true" or "false"</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['author.info'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="teaser.type">
        <div class="fl">
            <div class="settings-list-key">Teaser type</div>
            <div class="settings-list-info">Teaser type: set "trimmed" or "full"</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['teaser.type'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="teaser.char">
        <div class="fl">
            <div class="settings-list-key">Teaser character count</div>
            <div class="settings-list-info"></div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['teaser.char'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="description.char">
        <div class="fl">
            <div class="settings-list-key">Description character count</div>
            <div class="settings-list-info"></div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['description.char'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="rss.count">
        <div class="fl">
            <div class="settings-list-key">RSS feed count</div>
            <div class="settings-list-info"></div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['rss.count'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="rss.char">
        <div class="fl">
            <div class="settings-list-key">RSS feed description length</div>
            <div class="settings-list-info">RSS feed description length. If left empty we will use full page</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['rss.char'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>
    <div class="settings-list-header">Teaser image thumbnail</div>

    <div class="settings-list-item" data-key="img.thumbnail">
        <div class="fl">
            <div class="settings-list-key">Image thumbnail</div>
            <div class="settings-list-info">Enable image thumbnail on teaser. Set to "true" or "false"</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['img.thumbnail'] ?>" placeholder="true/false" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="default.thumbnail">
        <div class="fl">
            <div class="settings-list-key">Default thumbnail</div>
            <div class="settings-list-info">If "Image thumbnail" is set to "true", you can specify the default thumbnail here</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['default.thumbnail'] ?>" placeholder="http://" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="lightbox">
        <div class="fl">
            <div class="settings-list-key">Lightbox</div>
            <div class="settings-list-info">This can slow down the website if on</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['lightbox'] ?>" placeholder="true/false" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>
    <div class="settings-list-header">Views</div>

    <div class="settings-list-item" data-key="views.root">
        <div class="fl">
            <div class="settings-list-key">Views root</div>
            <div class="settings-list-info">Set the theme here</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['views.root'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="settings-list-item" data-key="views.layout">
        <div class="fl">
            <div class="settings-list-key">Framework config (no need to edit)</div>
            <div class="settings-list-info">Framework config</div>
        </div>
        <div class="fr">
            <input class="settings-list-value" type="text" value="<?php echo $settings_data['views.layout'] ?>" />
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
</div>

<input type="submit" value="Submit" />
<input type="reset" value="Reset" />
</form>

</section>