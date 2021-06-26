<?php
if (!empty($error)) {
    echo('<div class="error-message">'.$error.'</div>');
}

if (!empty($info)) {
    echo('<div class="success-message">'.$info.'</div>');
}

echo '<h2>'. $heading .'</h2>'
?>


<div class="tab-header">
    <span id="meetings-tab" class="tab active" onclick="$('#meetings-tab').addClass('active');$('#events-tab').removeClass('active');$('#meetings-tab-content').show();$('#events-tab-content').hide()">Meetings</span>
    <span id="events-tab" class="tab" onclick="$('#meetings-tab').removeClass('active');$('#events-tab').addClass('active');$('#meetings-tab-content').hide();$('#events-tab-content').show()">Events</span>
</div>
<div id="meetings-tab-content">
    <h3 style="margin-top:10px;display:inline-block">Edit Meetings</h3>
    <a href="#" onclick="event.preventDefault();edit_delete_all('meetings');return false" style="float:right;line-height:23px">Delete every meeting</a>
    <div class="events-header-row">
        <section class="events-date-col">Date</section>
        <section class="events-time-col">Start Time</section>
        <section class="events-time-col">End Time</section>
        <section class="events-desc-col">Description</section>
        <section class="events-tools-col">Operations</section>
        <div class="clearfix"></div>
    </div>

    <div id="meetings-rows-wrapper">
        <div class="event-status-row">Loading, please wait...</div>
    </div>

    <div class="section-shadow" style="margin-top: 30px;padding:10px;">
        <h3>Create Meeting</h3>
        <form action="POST" class="events-row-2 events-edit-mode event-create-area">
            <input type="hidden" name="type" value="meetings" />
            <section class="events-date-col">
                <input class="event-edit-input" type="text" placeholder="Date" name="date">
                <div class="events-edit-mode events-tools-desc">
                    Format: <i>mm/dd/yyyy</i><br>
                    Year must be full year (2014 instead of 14). Example: <i>7/26/2014</i><br>
                </div>
            </section>
            <section class="events-time-col">
                <input class="event-edit-input" type="text" placeholder="Start time" name="time_start">
                <div class="events-edit-mode events-tools-desc">
                    Format: <i>hh:mm aa</i><br>
                    Example: <i>3:25 pm</i><br>
                    <b>Optional</b>
                </div>
            </section>
            <section class="events-time-col">
                <input class="event-edit-input" type="text" placeholder="End time" name="time_end">
                <div class="events-edit-mode events-tools-desc">
                    Format: <i>hh:mm aa</i><br>
                    Example: <i>9:30 am</i><br>
                    <b>Optional</b>
                </div>
            </section>
            <section class="events-desc-col">
                <input class="event-edit-input" type="text" placeholder="Description" name="description">
                <div class="events-edit-mode events-tools-desc">
                    One line description of meeting. Enter anything here.
                </div>
            </section>
            <section class="events-tools-col">
                <input type="submit" class="events-save-btn events-edit-mode" value="Create">
            </section>
            <div class="clearfix"></div>
            <div class="events-details-col events-edit-mode">
                <textarea name="details" placeholder="Details (ex. links to meeting notes). You may use HTML."></textarea>
            </div>
        </form>
    </div>
</div>
<div id="events-tab-content" style="display:none">
    <h3 style="margin-top:10px;display:inline-block">Edit Events</h3>
    <a href="#" onclick="event.preventDefault();edit_delete_all('events');return false" style="float:right;line-height:23px">Delete every event</a>
    <div class="events-header-row">
        <section class="events-date-col">Date</section>
        <section class="events-time-col">Start Time</section>
        <section class="events-time-col">End Time</section>
        <section class="events-desc-col">Description</section>
        <section class="events-tools-col">Operations</section>
        <div class="clearfix"></div>
    </div>

    <div id="events-rows-wrapper">
        <div class="event-status-row">Loading, please wait...</div>
    </div>

    <div class="section-shadow" style="margin-top: 30px;padding:10px;">
        <h3>Create Event</h3>
        <form action="POST" class="events-row-2 events-edit-mode event-create-area">
            <input type="hidden" name="type" value="events" />
            <section class="events-date-col">
                <input class="event-edit-input" type="text" placeholder="Date" name="date">
                <div class="events-edit-mode events-tools-desc">
                    Format: <i>mm/dd/yyyy</i><br>
                    Year must be full year (2014 instead of 14). Example: <i>7/26/2014</i><br>
                </div>
            </section>
            <section class="events-time-col">
                <input class="event-edit-input" type="text" placeholder="Start time" name="time_start">
                <div class="events-edit-mode events-tools-desc">
                    Format: <i>hh:mm aa</i><br>
                    Example: <i>3:25 pm</i><br>
                    <b>Optional</b>
                </div>
            </section>
            <section class="events-time-col">
                <input class="event-edit-input" type="text" placeholder="End time" name="time_end">
                <div class="events-edit-mode events-tools-desc">
                    Format: <i>hh:mm aa</i><br>
                    Example: <i>9:30 am</i><br>
                    <b>Optional</b>
                </div>
            </section>
            <section class="events-desc-col">
                <input class="event-edit-input" type="text" placeholder="Description" name="description">
                <div class="events-edit-mode events-tools-desc">
                    One line description of meeting. Enter anything here.
                </div>
            </section>
            <section class="events-tools-col">
                <input type="submit" class="events-save-btn events-edit-mode" value="Create">
            </section>
            <div class="clearfix"></div>
            <div class="events-details-col events-edit-mode">
                <textarea name="details" placeholder="Details (ex. links to meeting notes). You may use HTML."></textarea>
            </div>
        </form>
    </div>
</div>


<script>
function populate_meetings_list() {
    $.get("/events/get_admin", {type: 'meetings'}, function(data) {
        $('#meetings-rows-wrapper').fadeOut("normal", function(){
            $('#meetings-rows-wrapper').html(data);
            $('#meetings-rows-wrapper').fadeIn("normal");
        });
    });
}
function populate_events_list() {
    $.get("/events/get_admin", {type: 'events'}, function(data) {
        $('#events-rows-wrapper').fadeOut("normal", function(){
            $('#events-rows-wrapper').html(data);
            $('#events-rows-wrapper').fadeIn("normal");
        });
    });
}

function edit_delete_all(type) {
    if (confirm('Are you sure you want to delete ALL '+type+'?')) {
        $.post( "/events/delete", { type: type }, function(data) {
            admin_alert('Event/meeting deletion', data);
            populate_meetings_list();
            populate_events_list();
        });
    }
}
function edit_enter(form) {
    $(form).removeClass('events-view-mode');
    $(form).addClass('events-edit-mode');
    $(form).find('.event-edit-input').prop('disabled', '');
}
function edit_delete(form) {
    if (confirm('Are you sure you want to delete this meeting or event?')) {
        var file_name = $(form).attr('data-file-name');
        $.post( "/events/delete", { file_name: file_name }, function(data) {
            admin_alert('Event/meeting deletion', data);
            populate_meetings_list();
            populate_events_list();
        });
    }
}
function edit_cancel(form) {
    $(form).removeClass('events-edit-mode');
    $(form).addClass('events-view-mode');
    $(form).find('.event-edit-input').prop('disabled', 'true');
}

function edit_action(form) {
    edit_cancel($(form));
    
    console.log($(form).find('[name=details]').val());
    
    var type_check = $(form).find('[name=type]').val();
    
    $.post( "/events/change", {
        file_name: $(form).attr('data-file-name'),
        date: $(form).find('[name=date]').val(),
        time_start: $(form).find('[name=time_start]').val(),
        time_end: $(form).find('[name=time_end]').val(),
        description: $(form).find('[name=description]').val(),
        type: $(form).find('[name=type]').val(),
        details: $(form).find('[name=details]').val()
    }, function(data) {
        admin_alert('Event/meeting edit', data);
        if (type_check === 'meetings') {
            populate_meetings_list();
        } else if (type_check === 'events') {
            populate_events_list();
        } else {
            populate_meetings_list();
            populate_events_list();
        }
    });
}

$(document).ready(function() {
    populate_meetings_list();
    populate_events_list();
    
    $('.event-create-area').submit(function(event) {
        event.preventDefault();
        
        var type_check = $(this).find('[name=type]').val();
        
        $.post( "/events/change", {
            date: $(this).find('[name=date]').val(),
            time_start: $(this).find('[name=time_start]').val(),
            time_end: $(this).find('[name=time_end]').val(),
            description: $(this).find('[name=description]').val(),
            type: $(this).find('[name=type]').val(),
            details: $(this).find('[name=details]').val()
        }, function(data) {
            admin_alert('Event/meeting creation', data);
            if (type_check === 'meetings') {
                populate_meetings_list();
            } else if (type_check === 'events') {
                populate_events_list();
            } else {
                populate_meetings_list();
                populate_events_list();
            }
        });
        $(this)[0].reset();
    });
});
</script>