<div class="wrapper responsive">
<style>
.resource-panel:not([data-mode=<?php echo $view_mode ?>]) {
    display: none;
}
</style>

<script>
function getSearchResults() {
    if ($('[name=search]').val() !== '') { // Prevent searching for nothing
        $.get('/docs/get_docs', {
            file_path: $('[name=file_path]').val(),
            dir_name: $('[name=dir_name]').val(),
            search_filter: $('[name=search]').val()
        }, function(data) {
            $('.files-list').hide();
            $('.files-list-search-results').html(data);
            $('.files-list-search-results').show();
            
            $('.files-list-search .fa-search').hide();
            $('.files-list-search .fa-times').show();
            
            $('.files-list-breadcrumb > *:not(.files-list-breadcrumb-search)').hide();
            $('.files-list-breadcrumb .files-list-breadcrumb-search').html('Search results in ' + $('[name=dir_name]').val());
            $('.files-list-breadcrumb .files-list-breadcrumb-search').show();
        });
    }
}
function getDocsProps(file) {
    $.get('/docs/get_docs_props', {
        file: file
    }, function(data) {
        site_alert('File Properties',data);
    });
}
function searchResultsHide() {
    $('.files-list-search-results').hide();
    $('.files-list-search-results').html('');
    $('.files-list').show();
    
    $('.files-list-search .fa-times').hide();
    $('.files-list-search .fa-search').show();
    
    $('[name=search]').val('');
    
    $('.files-list-breadcrumb .files-list-breadcrumb-search').hide();
    $('.files-list-breadcrumb .files-list-breadcrumb-search').html('');
    $('.files-list-breadcrumb > *:not(.files-list-breadcrumb-search)').show();
}
</script>

<div class="resource-pane-wrapper">
<div class="resource-pane">
    <div class="resource-panel" data-mode="docs">
        <h2>Documents</h2>
        <?php if ($view_mode === 'docs') get_docs($file_path); ?>
        
        <script>
        var lastFileRowMadeActive;
        
        $(document).ready(function() {
            var docs_ctx_menu = [
                {
                    label: 'Open',
                    onclick: function (event, item, target, ctx_target) {
                        window.location = $(ctx_target).attr('href');
                    }
                },
                {
                    label: 'Open in new tab',
                    onclick: function (event, item, target, ctx_target) {
                        window.open($(ctx_target).attr('href'), '_blank');
                    },
                    hr: true
                },
                {
                    label: 'Download',
                    onclick: function (event, item, target, ctx_target) {
                        var dl_str = '';
                        var ctx_target_included = false;
                        $('.files-list-row.file.active, .files-list-row.dir.active').each(function() {
                            dl_str += 'files[]=' + $(this).attr('data-path') + '&';
                            console.log('test');
                            if (this == ctx_target) {
                                ctx_target_included = true;
                            }
                        });
                        if (!ctx_target_included)
                            dl_str += 'files[]=' + $(ctx_target).attr('data-path') + '&';
                        $('<iframe>').attr('src', '<?php echo site_url() ?>download/?'+dl_str).appendTo('body').css({
                            'visibility' : 'hidden',
                            'height' : '1px',
                            'width' : '1px',
                            'position' : 'absolute',
                            'top' : '-9999px',
                            'left' : '-9999px',
                            'border' : '0'
                        }).load(function() {
                            $(this).remove();
                        });
                    }
                },
                {
                    label: 'Share Link',
                    onclick: function (event, item, target, ctx_target) {
                        site_alert('Share Link',
                              '<div style="width:85%;margin:0 auto;margin-bottom:15px;margin-top:20px">'
                            + '<input id="alert-share-link" type="text" readonly value="'+$(ctx_target).attr('href')+'" style="width:100%;padding:6px 10px;border:1px solid #e3e3e3;font-size:16px;box-sizing:border-box;-moz-box-sizing:border-box" />'
                            + '</div><center style="width:85%;margin:0 auto">'
                            + '<input type="button" onclick="$(\'#alert-share-link\').focus();$(\'#alert-share-link\').select()" value="Select all"/></center>');
                        $('#alert-share-link').focus();
                        $('#alert-share-link').select();
                    },
                    hr: true
                },
                {
                    label: 'Properties',
                    onclick: function (event, item, target, ctx_target) {
                       getDocsProps($(ctx_target).attr('data-path'));
                    }
                },
                {
                    event: 'open_menu',
                    handler: function(menu, menu_id, click_target, ctx_target) {
                    }
                },
                {
                    event: 'close_menu',
                    handler: function(menu, menu_id) {
                    }
                }
            ];
            
            lastFileRowMadeActive = $('.files-list-row[data-id=0]');
            
            $('#cover').click(function(){
                $('.breadcrumb-dir').removeClass('active');
                $('.files-list-row.file, .files-list-row.dir').removeClass('active');
            });
            
            $('.files-list-row.file, .files-list-row.dir').contextMenu(docs_ctx_menu);
            
            $('.files-list-row.file, .files-list-row.dir').click(function(e) {
                e.stopPropagation();
                
                if (e.ctrlKey) {
                    $(this).toggleClass('active');
                    lastFileRowMadeActive = $(this);
                } else if (e.shiftKey) {
                    if (typeof lastFileRowMadeActive !== 'undefined') {
                        var this_data_id = parseInt($(this).attr('data-id'));
                        var other_data_id = parseInt(lastFileRowMadeActive.attr('data-id'));
                        var lower_id = Math.min(this_data_id, other_data_id);
                        var higher_id = Math.max(this_data_id, other_data_id);
                        
                        if (this_data_id == other_data_id) {
                            $(this).addClass('active');
                            return false;
                        }
                        
                        $('.files-list-row.file, .files-list-row.dir').removeClass('active');
                        for (var i = lower_id; i <= higher_id; i++) {
                            $('.files-list-row[data-id='+i+']').addClass('active');
                        }
                        
                        lastFileRowMadeActive = $(this);
                    };
                } else {
                    $('.files-list-row.file, .files-list-row.dir').removeClass('active');
                    $(this).addClass('active');
                    lastFileRowMadeActive = $(this);
                }
                
                return false;
            }).dblclick(function(e) {
                if (this.target == '_blank' || e.ctrlKey) {
                    window.open(this.href,'_blank');
                } else {
                    window.location = this.href;
                }
                return false;
            }).keydown(function(event) {
                switch (event.which) {
                    case 13: // Enter
                    case 32: // Space
                        window.location = this.href;
                        return false;
                }
            });
        });
        </script>
    </div>
    
    <div class="resource-panel" data-mode="search-docs-get">
        <h2>Search Documents</h2>
        
        <form method="POST" action="/docs/search-docs" class="resources-docs-search">
            <input name="search" type="search" placeholder="Search all documents" />
            <input name="file_path" type="hidden" value="" />
            <input name="dir_name" type="hidden" value="Documents" />
            <input name="resources_dir_url" type="hidden" value="<?php echo site_url() ?>docs/" />
            
            <div class="special-btn">
                <input type="submit" value="Search">
                <i class="special-btn-icon fa fa-search"></i>
            </div>
        </form>
    </div>
    
    <div class="resource-panel" data-mode="search-docs-post">
        <h2>Documents Search Results</h2>
        <a href="<?php echo $dir_url ?>">Back to <?php echo $dir_name ?></a>
        
        <?php if ($view_mode === 'search-docs-post') get_docs($file_path, $search_text) ?>
    </div>
    <div class="clearfix"></div>
</div>
</div>
</div>