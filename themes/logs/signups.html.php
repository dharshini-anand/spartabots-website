<div class="wrapper responsive event-list">
    <?php if (!empty($breadcrumb)):?><div class="breadcrumb"><?php echo $breadcrumb ?></div><?php endif;?>
    <?php if (!empty($realID)): ?>
    <section class="card-shadow" style="padding:10px;font-size:15px">
        <div class="signup-title-wrapper">
            <h2 class="signup-title"><?php echo urldecode($meeting_data['name']) ?></h2>
            <div class="clearfix"></div>
        </div>
    </section>
    <?php if (login()): ?>
    <section class="card-shadow" style="padding:0;font-size:15px">
		<div class="signup-attend-choices">
            <a href="#" onclick="meeting_attend(0);return false" title="Say that I can attend"><i style="margin-right:5px" class="fa fa-check"></i>Attend</a>
            <a href="#" onclick="meeting_attend(1);return false" title="Say that you might be able to attend"><i style="margin-right:5px" class="fa fa-question"></i>Maybe attend</a>
            <a href="#" onclick="meeting_attend(2);return false" title="Say that you cannot attend"><i style="margin-right:5px" class="fa fa-times"></i>Not attend</a>
            <a href="#" onclick="meeting_attend(3);return false" title="Remove me from the attendents list"><i style="margin-right:5px" class="fa fa-square-o"></i>Undecided</a>
        </div>
	</section>
    <?php endif; ?>
    <?php echo $event_item_content; ?>
    <section class="card-shadow" style="padding:10px;font-size:15px">
        <b>Attendents</b>
        <noscript>Cannot load attendents list without JavaScript.</noscript>
        <div id="signup-attendents" style="display:none"></div>
    </section>
    <?php else: ?>
    <section class="card-shadow" style="padding:10px;font-size:17px">
        <h2 style="margin:0">Meeting or event not found.</h2>
        <hr style="margin:10px 0" />
        <a href="<?php echo site_url(); ?>meetings">Back to meetings</a><br/>
        <a href="<?php echo site_url(); ?>events">Back to events</a>
    </section>
    <?php endif; ?>
</div>
<?php if (!empty($realID)): ?>
<script>
function meeting_attend(status) {
    $.post("/s", {realID: <?php echo $realID; ?>, attend_status: status}, function(data) {
        dynamic_page_load('<?php echo site_url() ?>s/<?php echo $alphaID; ?>', '/s/<?php echo $alphaID; ?>', false);
    });
}
function populate_attendents_list() {
    $.get("/s", {realID: <?php echo $realID; ?>}, function(data) {
        $('#signup-attendents').html(data);
        $('#signup-attendents').fadeIn();
    });
}
$(document).ready(function() {
    populate_attendents_list();
});
</script>
<?php endif; ?>