<div class="wrapper responsive">
<?php if (!empty($breadcrumb)):?><div class="breadcrumb"><?php echo $breadcrumb ?></div><?php endif;?>

<div class="card-shadow" style="background:white;margin-bottom:25px;padding:10px">
    <h1 style="margin-top:0;color: rgb(68,68,68);">Events/Meetings</h1>

    <p>Below is a list of meetings and events for Skyline Spartabots. <b>Please check for updates often.</b></p>
</div>

<div class="dtabs">
    <div class="dtabs-main">
        <a href="<?php site_url() ?>meetings" class="dtab <?php if ($view_mode == 'meetings'): ?>active<?php endif; ?>">Meetings</a>
        <a href="<?php site_url() ?>events" class="dtab <?php if ($view_mode == 'events'): ?>active<?php endif; ?>">Events</a>
        <!--<a href="<?php site_url() ?>calendar" class="dtab <?php if ($view_mode == 'calendar'): ?>active<?php endif; ?>">Calendar</a>-->
    </div>
    <div class="dtabs-sub">
    </div>
</div>

<?php if ($view_mode == 'meetings'): ?>
<div class="event-list-section" style="margin-top:10px">
    <div id="meetings-list-wrapper">
        <?php get_events('meetings'); ?>
    </div>
</div>
<?php endif; ?>

<?php if ($view_mode == 'events'): ?>
<div class="event-list-section" style="margin-top:10px">
    <div id="events-list-wrapper">
        <?php get_events('events'); ?>
    </div>
</div>
<?php endif; ?>


<?php if ($view_mode == 'calendar'): ?>
<div class="card-shadow" style="background:white;padding:10px;margin-top:10px">
    <iframe src="https://www.google.com/calendar/embed?src=skyline.spartabots%40gmail.com&ctz=America/Los_Angeles&title=Skyline%20Spartabots"
    style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
</div>
<?php endif; ?>
