<section>
<div id="form-status"></div>
<script>
function move_item(clicked, down) {
    var li = clicked.closest('li');
    //var id = parseInt(li.attr('id').split('-')[1]);
    
    var li_other = (down ? li.next() : li.prev());
    //var id_other = (id + (down ? 1 : -1) );
    
    if (down) {
        li.insertAfter(li_other);
    } else {
        li.insertBefore(li_other);
    }
    
    //li.attr('id', 'item-' + id_other);
    //li_other.attr('id', 'item-' + id);
}

function prepare_submit_data() {
    var content = '';
    
    var total = $('.nav-list .item').size();
    $(".nav-list .item").each(function(index) {
        content += ($(this).find('.item-text').val() + '->' + $(this).find('.item-anchor').val());
        
        var sub_content = '';
        var children_sub = $(this).find('.sub-item');
        var children_sub_total = children_sub.size();
        if (children_sub.size() != 0) {
            sub_content += '{';
            children_sub.each(function(index2) {
                sub_content += ($(this).find('.sub-item-text').val() + '->' + $(this).find('.sub-item-anchor').val());
                
                if (index2 != children_sub_total - 1) {
                    sub_content += '|';
                }
            });
            sub_content += '}';
        }
        
        content += sub_content;
        
        if (index != total - 1) {
            content += '|';
        }
    });
    
    return content;
}

function subAddAction(el) {
    var text_val = $(el).closest('.sub-nav-list-add-item').find('.sub-nav-list-add-text').val();
    var anchor_val = $(el).closest('.sub-nav-list-add-item').find('.sub-nav-list-add-anchor').val();
    
    if (!text_val || !anchor_val) {
        return;
    }
    
    $(el).closest('.item').find('.nav-sub-list').append('<li class="sub-item"> \
        <div class="nav-list-text"> \
            <input type="text" class="sub-item-text" value="' + text_val + '"/> \
        </div> \
        <div class="nav-list-anchor"> \
            <input type="text" class="sub-item-anchor" value="' + anchor_val + '" /> \
        </div> \
        <div class="nav-list-opts"> \
            <a class="item-move-down" onclick="move_item($(this), true);return false" href="#">down</a> \
            <span>&nbsp;|&nbsp;</span> \
            <a class="item-move-up" onclick="move_item($(this), false);return false" href="#">up</a> \
            <span>&nbsp;|&nbsp;</span> \
            <a class="item-delete" onclick="if (confirm(\'Delete item?\')) { $(this).closest(\'li\').remove()}return false" href="#">delete</a> \
        </div> \
        <div class="clearfix"></div> \
    </li>');
    
    $(el).closest('.sub-nav-list-add-item').find('.sub-nav-list-add-text').val('');
    $(el).closest('.sub-nav-list-add-item').find('.sub-nav-list-add-anchor').val('');
}

function navListSubmit() {
    $.post( "/admin/edit-nav", { content: prepare_submit_data() }, function(data) {
        $('#form-status').html(data);
    });
}

function navListAddSubmit() {
    var text_val = $('#nav-list-add-item .nav-list-add-text').val();
    var anchor_val = $('#nav-list-add-item .nav-list-add-anchor').val();
    
    if (!text_val || !anchor_val) {
        return;
    }
    
    $('#nav-list-main').append('<li class="item"> \
        <div class="item-main"> \
            <div class="nav-list-text"> \
                <input type="text" class="item-text" value="' + text_val + '" /> \
            </div> \
            <div class="nav-list-anchor"> \
                <input type="text" class="item-anchor" value="' + anchor_val + '" />  \
            </div> \
            <div class="nav-list-opts"> \
                <a class="item-move-down" onclick="move_item($(this), true);return false" href="#">down</a> \
                <span>&nbsp;|&nbsp;</span> \
                <a class="item-move-up" onclick="move_item($(this), false);return false" href="#">up</a> \
                <span>&nbsp;|&nbsp;</span> \
                <a class="item-delete" onclick="if (confirm(\'Delete item?\')) { $(this).closest(\'li\').remove()}return false" href="#">delete</a> \
                <span>&nbsp;|&nbsp;</span> \
                <a class="item-add-sub" onclick="$(this).closest(\'li\').find(\'.sub-nav-list-add-item\').show();return false" href="#">Add child</a> \
            </div> \
            <div class="clearfix"></div> \
        </div> \
        <ul class="nav-sub-list"></ul> \
        <div class="sub-nav-list-add-item" class="nav-list-row"> \
            <div class="nav-list-text"> \
                <input class="sub-nav-list-add-text" type="text" placeholder="Item text" /> \
            </div> \
            <div class="nav-list-anchor"> \
                <input class="sub-nav-list-add-anchor" type="text" placeholder="Item anchor" /> \
            </div> \
            <div class="nav-list-opts"> \
                <input class="sub-nav-list-add-submit" type="button" value="Add Item" onclick="subAddAction(this);$(this).closest(\'.sub-nav-list-add-item\').hide();return false" /> \
                <a class="sub-nav-list-add-cancel" onclick="$(this).closest(\'.sub-nav-list-add-item\').hide();return false">Cancel</a> \
            </div> \
            <div class="clearfix"></div> \
        </div> \
    </li>');
    
    $('#nav-list-add-item .nav-list-add-text').val('');
    $('#nav-list-add-item .nav-list-add-anchor').val('');
}
</script>

<h2><?php echo $heading?></h2>

<?php
if (!empty($error)) {
    echo("Error: $error");
}
?>
<p>
The order of these tabs from top to bottom is the same order it will be displayed on the site from left to right.<br/>
Use the <i>down</i> and <i>up</i> options to move the position of the tabs.
</p>

<p>
Nothing is actually changed until you click Submit. You can reload the page if you accidently delete an item.
</p>

<p>
<b>NB!</b> Do not put any vertical bars ('|'), arrows ('->'), or curly brackets ('{' or '}') in the text or anchors. Doing so
will break the menu. Use <a href="http://www.theukwebdesigncompany.com/articles/entity-escape-characters.php">HTML escape entities</a> instead.
</p>

<form id="nav-edit-form" method="POST" onsubmit="navListSubmit();return false">
    <input name="content" type="hidden" value="null" />
    
    <div id="nav-list-header" class="nav-list-row">
        <div class="nav-list-text">
            <h4>Text</h4>
        </div>
        <div class="nav-list-anchor">
            <h4>Anchor</h4>
        </div>
        <div class="nav-list-opts">
            <h4>Options</h4>
        </div>
        <div class="clearfix"></div>
    </div>
    
    <?php echo admin_nav_menu(); ?>
    
    <div id="nav-list-add-item" class="nav-list-row">
        <div class="nav-list-text">
            <input class="nav-list-add-text" type="text" placeholder="Item text" />
        </div>
        <div class="nav-list-anchor">
            <input class="nav-list-add-anchor" type="text" placeholder="Item anchor" />
        </div>
        <div class="nav-list-opts">
            <input class="nav-list-add-submit" type="button" value="Add Item" onclick="navListAddSubmit()" />
        </div>
        <div class="clearfix"></div>
    </div>
    
    <input type="submit" value="Submit" />
</form>
</section>