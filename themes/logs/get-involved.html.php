<style>
.get-involved-category:not([data-category=<?php echo $category; ?>]) {
    display: none;
}
.get-involved-nav-item[data-category=<?php echo $category; ?>] {
    background-color: rgb(102, 102, 102);
}
.donation-wishlist {
	border-collapse: collapse;
}
.donation-wishlist th {
	padding: 2px 4px;
	text-align: center;
}
.donation-wishlist th, .donation-wishlist td {
	border-bottom: 1px solid #bbb;
}
.donation-wishlist td:nth-child(5):before, .donation-wishlist td:nth-child(6):before, .donation-wishlist td:nth-child(7):before, .donation-wishlist td:nth-child(8):before, .donation-wishlist td:nth-child(9):before {
	content: "$";
}
.donation-wishlist td:last-of-type {
	border-left: 1px solid #bbb;
}
.donation-wishlist tr:not(.donation-wishlist-first-row):hover {
	background: #f0f0f0;
	transition: 0.1s;
}
</style>
<div id="get-involved-splash">
    <div id="get-involved-splash-image-over"></div>
    <div id="get-involved-splash-wrapper" class="wrapper responsive" style="padding-top:40px">
        <h1 id="get-involved-splash-title">Get Involved<h1>
        <h1 id="get-involved-splash-title-sub"><?php echo ucfirst($category); ?></h1><h1>
    </div>
</div>
<div id="get-involved-main">
    <div id="get-involved-nav">
        <div class="wrapper responsive" style="padding:0">
            <a href="<?php echo site_url() ?>get-involved/students" data-category="students" class="get-involved-nav-item">Students</a>
            <a href="<?php echo site_url() ?>get-involved/mentors" data-category="mentors" class="get-involved-nav-item">Mentors</a>
            <a href="<?php echo site_url() ?>get-involved/sponsors" data-category="sponsors" class="get-involved-nav-item">Sponsors</a>
            <a href="<?php echo site_url() ?>get-involved/donate" data-category="donate" class="get-involved-nav-item">Donating</a>
            <div class="clearfix"></div>
        </div>
        <?php if ($category === 'sponsors'): ?>
        <div class="get-involved-nav-extra">
            <div class="wrapper responsive" style="padding:0">
                <div class="slideIn">
                    <a href="#2019-sponsors">2019 Sponsors</a>
                    <a href="#2018-sponsors">2018 Sponsors</a>
                    <a href="#2017-sponsors">2017 Sponsors</a>
                    <a href="#2016-sponsors">2016 Sponsors</a>
                    <a href="#2015-sponsors">2015 Sponsors</a>
                    <a href="#2014-sponsors">2014 Sponsors</a>
                    <a href="#2013-sponsors">2013 Sponsors</a>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div class="wrapper responsive" style="min-height:510px">
        <div data-category="students" class="get-involved-category">
            <div class="get-involved-card card-shadow">
                <h2>Students</h3>

                <p>Students are provided with a unique opportunity to develop self-confidence, creative thinking, leadership, collaboration and teamwork. Robotics provides you with real-world experiences and robotics club looks great on resumes!</p>
			</div>
            <div class="get-involved-card card-shadow">
                <h2>Why join?</h3>

                <p>Being part of a FIRST robotics team is fun! You'll gain experiences with many other students with similar interests.</p>

                <p>It doesn't matter whether you are experienced with robots or not, you'll gain amazing experiences because your critical thinking, time management and reasoning are tested in a time-critical, team-based environment. You'll be able to carry these skills to college, and robotics club also looks great on college apps.</p>
            </div>

            <div class="get-involved-card card-shadow fl get-involved-fl-card">
                <h2>Steps to Join Spartabots</h2>
                <p>Please follow the steps below if you'd like to join our robotics club:</p>
                <ul style="font-size:15px">
                    <li>
						Fill out the club survey (<a target="_blank" href="https://docs.google.com/forms/d/e/1FAIpQLScYL8jUnVGq2g4ZdO98g7oR4UmV8LkLqkfrGv2RS7Qn703eRA/viewform">spartabots.org/survey</a>). The survey asks for contact information and (optionally) demographics for grants.
					</li>
					<li>
						Print out, fill, and bring the <a target="_blank" href="https://www.spartabots.org/documents/club/safety-media-release-form-r1.pdf">safety/media release form</a> to the next meeting.
					</li>
					<li>
						Pay club dues to the bookkeeper. Club dues are $40, and cover the cost of a t-shirt and food during the build season.
					</li>
					<li>
						Join our Discord server for team communication: <a target="_blank" href="https://spartabots.org/discord">spartabots.org/discord</a>.
					</li>
					<li>
						Join our Remind for meeting updates: <a target="_blank" href="https://www.remind.com/join/team2976">spartabots.org/remind</a>.
					</li>
                </ul>
            </div>
            <div class="get-involved-card card-shadow get-involved-fr-card">
                <h2>Come in any time!</h2>

                <p>Feel free to come to any <a href="<?php echo site_url() ?>meetings">meetings</a>. We strongly recommend you come to at least one meeting per week.</p>

                <p>However, we do expect all our members to regularly attend meetings and contribute frequently. We expect all our members to be self-directed, which will enable each member to learn more and contribute to the overall mission and vision of the club.</p>
            </div>
            <div class="clearfix"></div>
        </div>
        <div data-category="mentors" class="get-involved-category">
            <div class="get-involved-card card-shadow">
                <h2>Mentors</h2>
                <p>Membership in our club is not limited to just students. Adult mentors play a huge role in our club, from teaching new skills to ensuring the safety of the students. Mentors are not responsible for building the robots; they are responsible for passing on their knowledge of robotics to the rest of the team and/or supervising the room. They may participate in discussions and strategize with the members, but the goal is that only the students build the essence of the robots.</p>

		<p>Mentors may help in any part of the team: build, software, and/or business. The team that each mentor joins depends on the knowledge and experience he/she has.</p>

		<p>We welcome mentors of any skill level to join and help our club. The only requirements to become a mentor is that mentors are willing to spend their time helping students explore their interest in the fields of science and technology.</p>

		<p>Thanks to all of our current mentors. We couldn't function without you! <a href="https://www.spartabots.org/about-us/members">This page</a> contains the list of the mentors we owe a special thanks to:<br/>
		<a href="https://www.spartabots.org/about-us/members">List of mentors</a></p>

		<p>If you're interested in helping mentor our club, please contact <a href="mailto:skyline.spartabots@gmail.com">skyline.spartabots@gmail.com</a>.</p>

            </div>
        </div>
        <div data-category="sponsors" class="get-involved-category">
            <div id="sponsor-us" class="get-involved-card card-shadow">
                <h2>Sponsors</h2>
                <p>Skyline Robotics couldn't exist without the support of our sponsors, who help us in a variety of ways from granting money to donating parts.</p>

                <p>If you're interested in helping to sponsor our club, please contact
                <a href="mailto:skyline.spartabots@gmail.com">skyline.spartabots@gmail.com</a>.</p>
            </div>
            <div id="2019-sponsors" class="get-involved-card card-shadow">
                <h2>2019 Sponsors</h2>
                <ul>
                    <li>First Tech Credit Union</li>
                    <li>Boeing</li>
                    <li>ISF</li>
                    <li>Best Buy</li>
                    <li>Microsoft</li>
                    <li>T-Mobile</li>
                    <li>Skyline PTSA</li>
                    <li>Skyline ASB</li>
                    <li>Trader Joe's</li>
                    <li>Starbucks</li>
                    <li>Ben & Jerrys</li>
                    <li>Coho</li>
                    <li>RAM</li>
                    <li>Target</li>
                    <li>Bed Bath and Beyond</li>
                    <li>Nothing Bundt Cakes</li>
                    <li>Office Depot</li>
                    <li>QFC</li>
                    <li>HopsnDrops</li>
                    <li>Zeeks Pizza</li>
                    <li>Fred Meyer</li>
                    <li>Sports Clips</li>
                </ul>
            </div>
			<div id="2018-sponsors" class="get-involved-card card-shadow">
                <h2>2018 Sponsors</h2>
                <ul>
                    <li>ISF</li>
                    <li>Skyline High PTSA</li>
                    <li>Boeing</li>
					<li>SPEEA</li>
					<li>Microsoft</li>
					<li>FIRSTWA</li>
                    <li>Starbucks</li>
					<li>Jimmy Johns</li>
					<li>Trader Joe's</li>
					<li>Rams</li>
					<li>Papa Johns</li>
                </ul>
            </div>
			<div id="2017-sponsors" class="get-involved-card card-shadow">
                <h2>2017 Sponsors</h2>
                <ul>
                    <li>ISF</li>
					<li>Skyline High PTSA</li>
                    <li>Boeing</li>
					<li>SPEEA</li>
					<li>Microsoft</li>
					<li>FIRSTWA</li>
                    <li>Starbucks</li>
                </ul>
            </div>
            <div id="2016-sponsors" class="get-involved-card card-shadow">
                <h2>2016 Sponsors</h2>
                <div class="sponsors-list">
                    <a href="http://issaquahschoolsfoundation.org/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/isf.jpg" alt="" />
                        </div>
                    </a>
                    <a href="http://skylineptsa.ourschoolpages.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/shs_ptsa.png" alt="" />
                        </div>
                    </a>
                    <a href="http://www.solidworks.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/solidworks.png" alt="" />
                        </div>
                    </a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div id="2015-sponsors" class="get-involved-card card-shadow">
                <h2>2015 Sponsors</h2>
                <ul>
                    <li>ISF</li>
                    <li>Boeing</li>
                    <li>Chipotle</li>
                    <li>TCBY Yougurt</li>
                    <li>Papa Johns</li>
                    <li>Dominos</li>
                    <li>Target</li>
                    <li>Apple Specialist Store</li>
                    <li>Frankie's Pizza</li>
                    <li>Agave</li>
                    <li>Starbucks</li>
                    <li>Uncle's Games</li>
                    <li>Paint Away</li>
                    <li>Subway</li>
                    <li>Red Mango</li>
                    <li>Cassiope Games</li>
                </ul>
            </div>
            <div id="2014-sponsors" class="get-involved-card card-shadow">
                <h2>2014 Sponsors</h2>
                <div class="sponsors-list">
                    <a href="http://issaquahschoolsfoundation.org/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/isf.jpg" alt="" />
                        </div>
                    </a>
                    <a href="http://www.boeing.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/boeing.jpg" alt="" />
                        </div>
                    </a>
                    <a href="http://www.microsoft.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/msft.png" alt="" />
                        </div>
                    </a>
                    <div class="clearfix"></div>

                    <a href="http://skylineptsa.ourschoolpages.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/shs_ptsa.png" alt="" />
                        </div>
                    </a>
                    <a href="https://www.ryerson.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/ryerson.png" alt="" />
                        </div>
                    </a>
                    <a href="http://yoplateau.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/yo_plateau.png" alt="" />
                        </div>
                    </a>
                    <div class="clearfix"></div>

                    <a href="http://www.dominos.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/dominos.png" alt="" />
                        </div>
                    </a>
                    <a href="http://www.ristorantesimone.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/ristorante_simone.png" alt="" />
                        </div>
                    </a>
                    <a href="https://www.qfc.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/qfc.gif" alt="" />
                        </div>
                    </a>
                    <div class="clearfix"></div>

                    <a href="http://arco.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/arco.png" alt="" />
                        </div>
                    </a>
                    <a href="http://www.target.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/target.png" alt="" />
                        </div>
                    </a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div id="2013-sponsors" class="get-involved-card card-shadow">
                <h2>2013 Sponsors</h2>
                <div class="sponsors-list">
                    <a href="http://issaquahschoolsfoundation.org/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/isf.jpg" alt="" />
                        </div>
                    </a>
                    <a href="http://www.boeing.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/boeing.jpg" alt="" />
                        </div>
                    </a>
                    <a href="http://www.speea.org/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/speea.png" alt="" />
                        </div>
                    </a>
                    <div class="clearfix"></div>

                    <a href="https://www.ryerson.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/ryerson.png" alt="" />
                        </div>
                    </a>
                    <a href="https://www.platt.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/platt.png" alt="" />
                        </div>
                    </a>
                    <a href="http://www.skylineboosterclub.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/shs_booster_club.png" alt="" />
                        </div>
                    </a>
                    <div class="clearfix"></div>

                    <a href="http://www.juicybitssoftware.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/juicybits.png" alt="" />
                        </div>
                    </a>
                    <a href="http://www.lowes.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">
                            <img src="<?php echo site_url() ?>images/sponsor-logos-BnW/lowes.png" alt="" />
                        </div>
                    </a>
                    <a href="http://www.traderjoes.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">Trader Joe's</div>
                    </a>
                    <div class="clearfix"></div>

                    <a href="http://www.krispykreme.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">Krispy Kreme</div>
                    </a>
                    <a href="http://www.jambajuice.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">Jamba Juice</div>
                    </a>
                    <a href="http://www.acehardware.com/" class="sponsor-item">
                        <div class="sponsor-item-inner">Ace Hardware</div>
                    </a>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div data-category="donate" class="get-involved-category">
            <div class="get-involved-card card-shadow">
                <h2>Donate</h2>
                <p>The Spartabots is a non-profit organization that requires support from various sponsors to pursue its goals. You can help us achieve them! Please consider helping us continue spreading STEM interests by putting in a donation.</p>

                <p>To donate, please send us an email to <a href="mailto:skyline.spartabots@gmail.com">skyline.spartabots@gmail.com</a>.</p>
            </div>
        </div>
    </div>
</div>