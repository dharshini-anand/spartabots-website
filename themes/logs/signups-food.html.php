<div class="wrapper responsive event-list">

<?php if (!empty($breadcrumb)):?><div class="breadcrumb"><?php echo $breadcrumb ?></div><?php endif;?>

<script>
function foodSignupsFormSubmit() {
    if ($('#signups-food-what').val().length == 0 || $('#signups-food-when').val().length == 0) {
        $('#sform-warning').show();
        return;
    }
    
    submit_signup($('#signups-food-when').val(), $('#signups-food-what').val());
    document.getElementById('foodSignupForm').reset();
    
    $('#signupThanks').fadeIn('fast', function () {
        $(this).delay(4500).fadeOut('fast');
    });
}
</script>

<div class="event-list">
    <section class="card-shadow" style="padding: 10px;">
        <h2 class="sform-title">Food Signups</h2>
        <form id="foodSignupForm" class="sform" onsubmit="foodSignupsFormSubmit();return false">
            <div id="sform-warning" class="notify-message" style="display:none;margin-left:32px;margin-bottom:10px;max-width:700px;">You have not filled out one or more inputs. Please fill everything in
            before submitting.</div>
            <div class="sform-item">
                <label for="signups-food-what"><i class="fa fa-question"></i></label>
                <div class="sform-item-input">
                    <input required id="signups-food-what" type="text" placeholder="What will you bring?">
                </div>
            </div>
            <div class="sform-item">
                <label for="signups-food-when" ><i class="fa fa-clock-o"></i></label>
                <div class="sform-item-input" style="position:relative">
                    <input required id="signups-food-when" type="text" placeholder="Which meeting day(s) will you bring it? (MM/DD, MM/DD, ...)">
                </div>
            </div>
            <input class="dbtnsq sform-submit" type="submit" value="Submit">
            <div id="signupThanks" style="display:none">Thank you for submitting!</div>
        </form>
    </section>
    <section class="card-shadow" style="padding: 10px;">
        <b>Signups Data</b>
        <div id="signupsFoodDataList" style="display:none">
        </div>
    </section>
</div>

</div>

<script>
function populate_foodsignups_list() {
    $.get("/sfood", {}, function(data) {
        $('#signupsFoodDataList').html(data);
        $('#signupsFoodDataList').fadeIn();
    });
}
function submit_signup(when, what) {
    $.post("/sfood", {when: when, what: what}, function(data) {
        dynamic_page_load('<?php echo site_url() ?>s/food', '/s/food', false);
    });
}
$(document).ready(function() {
    populate_foodsignups_list();
});
</script>