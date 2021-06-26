<script>
var c;
$(document).ready(function() {
    c = new Carousel('#carousel-1');
});
</script>
<div class="panel" id="landing-intro">
    <div id="carousel-1" class="carousel" style="
            height: 100%; position: absolute; z-index: 5; top: 0; bottom: 0; left: 0; right: 0;">
        <div class="carousel-content transition" style="height:100%;">
			<div class = "carousel-item" style="background-image:url(//www.spartabots.org/images/gallery/2019%20Gallery/Team%20picture.jpg);
                background-repeat:no-repeat;background-size:cover;background-position:center;height:100%;">
                <div class="carousel-item-content"></div>
            </div>
            <div class="carousel-item" style="background-image:url(//www.spartabots.org/images/gallery/2018%20Gallery/Team%20picture.jpg);
                background-repeat:no-repeat;background-size:cover;background-position:center;height:100%;">
                <div class="carousel-item-content"></div>
            </div>
            <div class="carousel-item" style="background-image:url(//www.spartabots.org/images/gallery/2017%20Gallery/Team%20picture.jpg);
                background-repeat:no-repeat;background-size:cover;background-position:center;height:100%;">
                <div class="carousel-item-content"></div>
            </div>
            <div class="carousel-item" style="background-image:url(//www.spartabots.org/images/gallery/2015%20Gallery/Mentors%20picture.jpg);
                background-repeat:no-repeat;background-size:cover;background-position:center;height:100%;">
                <div class="carousel-item-content"></div>
            </div>
        </div>
    </div>
    <div id="landing-message" style="max-width: initial; background: rgba(0,0,0,.35); position: relative; z-index: 10;">
        <center id="landing-message-title" style="border:0">We're the Spartabots</center>
        <hr style="border-top-color:white;max-width:500px;margin:0 auto;" />
        <center id="landing-message-content">FRC Team 2976</center>

        <center><a class="button3" href="<?php echo site_url() ?>about-us">about us</a></center>
    </div>
</div>
<div class="panel" id="about-panel">
    <div id="about-panel-inner" class="wrapper">
        <section class="about-panel-card" id="about-main-card" style="z-index: 20">
			<div id="about-main-card-intro">
				The Spartabots are dedicated to inspiring students in the fields of science, technology, engineering and math.
			</div>
			<div id="about-main-card-btn-group">
				<a href="<?php echo site_url() ?>about-us"><span>About Us</span></a>
				<a href="<?php echo site_url() ?>blog"><span>Blog</span></a>
				<a href="<?php echo site_url() ?>meetings"><span>Calendar</span></a>
				<a href="<?php echo site_url() ?>media"><span>Media</span></a>
			</div>
            <div id="carousel-1-tabs" class="clearfix">
				<div onclick="c.move_to(0);$('.carousel-1-tab').removeClass('selected');$(this).addClass('selected')" class="carousel-1-tab selected w25p">
                    <div class="carousel-1-tab-inner"></div>
                    <div class="carousel-1-tab-label">2019 Team Picture</div>
                </div>
                <div onclick="c.move_to(1);$('.carousel-1-tab').removeClass('selected');$(this).addClass('selected')" class="carousel-1-tab w25p">
                    <div class="carousel-1-tab-inner"></div>
                    <div class="carousel-1-tab-label">2018 Team Picture</div>
                </div>
                <div onclick="c.move_to(2);$('.carousel-1-tab').removeClass('selected');$(this).addClass('selected')" class="carousel-1-tab w25p">
                    <div class="carousel-1-tab-inner"></div>
                    <div class="carousel-1-tab-label">2017 Team Picture</div>
                </div>
                <div onclick="c.move_to(3);$('.carousel-1-tab').removeClass('selected');$(this).addClass('selected')" class="carousel-1-tab w25p">
                    <div class="carousel-1-tab-inner"></div>
                    <div class="carousel-1-tab-label">2015 Mentor Picture</div>
                </div>
            </div>
		</section>
        <section class="about-panel-card">
			<div class="property">
				<div class="property-thumb" style="background-image: url(<?php echo site_url() ?>images/gallery/2014%20Gallery/Team%20Photo%20-%20Glacier%20Peak%20Competition.jpg);"></div>
				<div class="property-summary">
					<h2>We are the Spartabots</h2>

					<p>Created in 2008, our team participates in the FIRST Robotics Competition as <a href="https://www.thebluealliance.com/team/2976">team 2976</a> and the FIRST Tech Challenge as team 15468 Spartabots Green. We are a high school club operating in Skyline High School, Sammamish, WA.</p>

					<p><a href="<?php echo site_url() ?>about-us/who">Read more</a></p>
				</div>
			</div>

			<div class="property">
				<div class="property-thumb" style="background-image: url(<?php echo site_url() ?>images/gallery/2012%20Gallery/The%20pits.jpg);"></div>
				<div class="property-summary">
					<h2>What is the FIRST Robotics Competition?</h2>

					<p>The FIRST Robotics Competition is an international high school robotics competition operated by FIRST. The game of the competition changes each year to keep the excitement fresh.</p>

					<p><a href="<?php echo site_url() ?>first">Read more</a></p>
				</div>
			</div>
			<div class="property">
				<div class="property-thumb" style="background-image: url(<?php echo site_url() ?>images/match-arena.png);"></div>
				<div class="property-summary">
					<h2>Why should I join?</h2>

					<p>Being part of a FIRST robotics team is fun! It doesn't matter whether you have experience with robots or not, you'll gain amazing experiences and valuable skills you can carry to college, and robotics club also looks great on college apps. <a href="http://machinedesign.com/robotics/changing-lives-one-robot-time">An article on why robotics matters by team 4073.</a></p>

					<p><a href="<?php echo site_url() ?>students">Read more</a></p>
				</div>
			</div>
		</section>
    </div>
</div>
<div class="panel" style="padding-bottom: 10px;">
    <div class="wrapper responsive">
        <center class="social" id="landing-social"><?php social() ?></center>
    </div>
</div>
<div id="new-member-panel" class="panel">
    <div class="wrapper responsive">
        <div id="new-member-l-col">
            <h1>New to the club?</h1>
            <span>Make sure you complete the following after your first meeting:</span>

            <ul style="padding-left: 20px;">
				<li>
                    Fill out the club survey (<a target="_blank" href="https://spartabots.org/survey">spartabots.org/survey</a>). The survey asks for contact information and (optionally) demographics for grants.
                </li>
                <li>
					Print out, fill, and bring the <a target="_blank" href="https://www.spartabots.org/documents/club/safety-media-release-form-r1.pdf">safety/media release form</a> to the next meeting.
				</li>
                <li>
                    Pay club dues to the bookkeeper <strong>only once you decide you want to stay</strong>. Club dues are $70, and cover the cost of a t-shirt and food during the build season.
                </li>
				<li>
                    Join our Discord server for team communication: <a target="_blank" href="https://spartabots.org/discord">spartabots.org/discord</a>.
                </li>
				<li>
                    Join our Remind for meeting updates: <a target="_blank" href="https://www.remind.com/join/team2976">spartabots.org/remind</a>.
                </li>
            </ul>
        </div>
        <div id="new-member-r-col">
            <i class="etl-documents" style="font-size:180px;width:100%;text-align:center;margin-top:40px;"></i>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<div id="quick-links-2" class="panel">
    <div id="quick-links-2-panel" class="wrapper responsive">
        <div class="about-panel-3-col">
            <h3>Our Sponsors</h3>

            <p>The Spartabots couldn't exist without the support of our sponsors, who help us in a variety of ways from granting money to donating parts.</p>

            <center><a class="button2" href="<?php echo site_url() ?>sponsors">read more</a></center>
        </div>
        <div class="about-panel-3-col">
            <h3>Community</h3>

            <p>Our goal is to raise awareness and interest among the people of our community, about our club as well as the field of science and technology.</p>

            <center><a class="button2" href="<?php echo site_url() ?>about-us/community">read more</a></center>
        </div>
        <div class="about-panel-3-col">
            <h3>Get Involved</h3>

            <p>We are always looking for any help within the club, whether it be new students to join us, new mentors, or new sponsors to support our team.</p>

            <center><a class="button2" href="<?php echo site_url() ?>get-involved">read more</a></center>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<div class="panel" style="padding:0">
    <div id="get-in-touch-panel" class="wrapper responsive" style="
            padding:40px 0;
            padding-bottom:45px;
            border-top:1px solid #f4f4f4;">
        <div class="panel-content">
            <h3>Get in touch</h3>
            <ul>
                <li><span>E</span><a href="mailto:skyline.spartabots@gmail.com">skyline.spartabots@gmail.com</a></li>
                <li><span>F</span><a href="https://www.facebook.com/spartabots">facebook.com/spartabots</a></li>
                <li><span>T</span><a href="https://twitter.com/spartabots">twitter.com/spartabots</a></li>
				<li><span>I</span><a href="https://www.instagram.com/spartabots2976/">instagram.com/spartabots2976</a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>
</div>