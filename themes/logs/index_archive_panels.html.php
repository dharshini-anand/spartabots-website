<!-- BEGIN Content for splash index -->
<div id="landing-splash">
    <div id="landing-splash-background-outer"></div>
    <div class="panel" id="landing-intro">
        <div id="landing-splash-background-inner"></div>
        <div class="wrapper responsive">
            <div id="landing-message">
                <center id="landing-message-title">We're the Spartabots</center>
                
                <center id="landing-message-content">A high school robotics club that competes in the FIRST Robotics Competition</center>
                
                <center><a class="button3" href="<?php echo site_url() ?>about-us">about us</a></center>
            </div>
        </div>
    </div>
</div>
<div id="landing-splash-over">
    <!-- ALL other panels go here -->
</div>
<!-- END Content for splash index -->

<div id="green-stripe-panel" class="panel">
    <div class="wrapper responsive">
        <section id="index-whatisfirst">
            <div class="fl w50p">
                <i class="fa fa-question"></i>
                <h1>So, what is the FIRST Robotics Competition?</h1>
                
                <p>The FIRST Robotics Competition is an international high school robotics competition operated by FIRST,
                an international youth organization created to inspire young people's interest in science and technology.</p>

                <p>Each year, FIRST robotics teams build robots that will compete in competitions. The game changes every
                year to keep the excitement fresh. The game details are announced at the beginning of January and teams
                have 6 weeks to build a robot.</p>
            </div>
            <div id="index-whatisfirst-image" class="fr w50p">
            </div>
            <div class="clearfix"></div>
        </section>
        <section id="index-whyjoin">
            <div class="fr w50p">
                <i class="fa fa-lightbulb-o"></i>
                <h1>Why should I join?</h1>
                <p>Being part of a FIRST robotics team is fun! You'll gain experiences with many other students with similar interests.</p>

                <p>It doesn't matter whether you are experienced with robots or not, you'll gain amazing experiences because your
                critical thinking, time management and reasoning are tested in a time-critical, team-based environment.
                You'll be able to carry these skills to college, and robotics club also looks great on college apps.</p>
            </div>
            <div id="index-whyjoin-image" class="fl w50p" title="Handshake designed by DEADTYPE from the thenounproject.com">
            </div>
            <div class="clearfix"></div>
        </section>
    </div>
</div>

<div class="panel" id="quick-links-panel-start">
    <div id="quick-links-panel" class="wrapper responsive">
        <div class="quick-links-col">
            <div class="card-shadow quick-links-card">
                <div class="quick-links-card-full-bleed quick-links-card-full-bleed-red">
                    <i class="fa fa-video-camera"></i>
                </div>
                <div class="quick-links-card-content">
                    <h2>Media</h2>
                    
                    <p>Check out Spartabots on media!</p>
                    
                    <p>See our picture gallery <a href="<?php site_url() ?>media/gallery">here.</a></p>
                    <p>See our videos <a href="<?php site_url() ?>media/videos">here.</a></p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="quick-links-col">
            <div class="card-shadow quick-links-card">
                <div class="quick-links-card-full-bleed quick-links-card-full-bleed-blue">
                    <i class="fa fa-group"></i>
                </div>
                <div class="quick-links-card-content">
                    <h2>Events/Meetings</h2>
                    
                    <p>Here's a list of our club meetings and events we're going to or hosting.</p>
                    
                    <p><a href="<?php site_url() ?>events-meetings">See our events & meetings here.</a></p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="quick-links-col">
            <div class="card-shadow quick-links-card">
                <div class="quick-links-card-full-bleed quick-links-card-full-bleed-purple">
                    <i class="fa fa-globe"></i>
                </div>
                <div class="quick-links-card-content">
                    <h2>Sponsors</h2>
                    
                    <p>Our sponsors very important to us in many ways from granting money to donating mechanical parts.</p>
                    <p>If you'd like to sponsor please <a href="<?php site_url() ?>contact-us">contact us</a>.</p>
                    
                    <p>A list of our sponsors can be seen <a href="<?php site_url() ?>sponsors">here</a>.</p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<div class="panel g3" id="events-panel-start">
    <div id="events-panel" class="wrapper responsive">
        <h2 class="panel-title" style="text-align:center">from the blog</h2>
        <div class="panel-content">
            <div id="landing-news-list">
            <?php $i = 0; $len = count($posts);?>
            <?php foreach($posts as $p):?>
                <?php 
                    if ($i == 0) {
                        $class = 'landing-news post first';
                    } 
                    elseif ($i == $len - 1) {
                        $class = 'landing-news post last';
                    }
                    else {
                        $class = 'landing-news post';
                    }
                    $i++;		
                ?>
                <div class="<?php echo $class ?>" itemprop="blogPost" itemscope="itemscope" itemtype="http://schema.org/BlogPosting">
                    <div class="main">
                        <h2 class="title-index" itemprop="name"><a href="<?php echo $p->url?>"><?php echo $p->title ?></a></h2>
                        <div style="padding:0 20px">
                            <span itemprop="datePublished"><?php echo date('d F Y', $p->date)?></span>
                        </div>
                        <div class="teaser-body" itemprop="articleBody">
                            <?php echo get_teaser($p->body, $p->url)?>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
            </div>
        </div>
    </div>
</div>


<div id="mission-panel" class="panel">
    <div class="wrapper responsive">
        <h1>Our mission</h1>
        
        <p>The Spartabot's mission is to inspire students in the fields of science, technology, engineering and math.</p>
        
        <div style="margin-top:30px">
            <div class="fl w25p">
                <img src="<?php echo site_url() ?>images/dean-kamen-bnw-soft-effect-whiter.png" style="max-height:275px" alt="" />
            </div>
            <div class="fl w75p">
                <blockquote>
                "To transform our culture by creating a world where science and technology are celebrated and where young people
                dream of becoming science and technology leaders."
                </blockquote>
                <cite>- Dean Kamen, FIRST&reg; founder</cite>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="panel" style="padding-bottom: 45px">
    <div id="get-in-touch-panel" class="wrapper responsive">
        <div class="panel-content">
            <h3>Get in touch</h3>
            <ul>
                <li><span>E</span><a href="mailto:skyline.spartabots@gmail.com">skyline.spartabots@gmail.com</a></li>
                <li><span>F</span><a href="https://www.facebook.com/spartabots">https://www.facebook.com/spartabots</a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="panel carousel-panel" id="frc-panel-start">
    <div id="frc-panel" class="wrapper responsive">
        <div id="FIRST-bg-carousel-wrapper" class="landing-bg-carousel-wrapper">
            <div id="FIRST-bg-carousel" class="landing-bg-carousel carousel">
                <div class="carousel-content transition">
                    <div class="carousel-item" style="background:white">
                        <div class="carousel-item-content">
                            <img src="<?php echo site_url() ?>images/FIRST-logo.jpg" alt="FIRST logo" style="max-height:400px;width:auto;margin-top:50px;margin-left:60px;" />
                        </div>
                    </div>
                    <div class="carousel-item" style="background:white">
                        <div class="carousel-item-content">
                            <img src="<?php echo site_url() ?>images/frc-logo.jpg" alt="FRC logo" style="max-height:400px;width:auto;margin-top:50px;margin-left:60px;" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="landing-carousel-wrapper fr" id="FIRST-carousel-wrapper">
            <h2 class="landing-carousel-title">About FIRST</h2>
            <div id="FIRST-carousel" class="landing-carousel carousel">
                <div class="carousel-content transition">
                    <div class="carousel-item">
                        <div class="carousel-item-content">
                            <h2>Mission of FIRST</h2>
                            
                            <p>
                            The mission of FIRST is to inspire young people's interests and participation in technology and science.
                            Through the help of mentors and teamwork, students build science, engineering and technology skills, and
                            build self-confidence and leadership.
                            </p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="carousel-item-content">
                            <h2>Mission of FIRST</h2>
                            
                            <p>FIRST was created to</p>
                            <blockquote>
                                <p>...transform our culture by creating a world where science and technology are celebrated
                                and where young people dream of becoming science and technology heroes.</p><br/>
                                <cite>- Dean Kamen, Founder of FIRST</cite>
                            </blockquote>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="carousel-item-content">
                            <h2>FIRST Programs</h2>
                            
                            <p>
                            FIRST organizes several robotics competitions for students. These competitions are:
                            </p>
                            
                            <ul>
                                <li><b>FIRST Robotics Competition</b> (FRC)</li>
                                <li><b>Junior FIRST Lego League</b> (Jr.FLL)</li>
                                <li><b>FIRST Lego League</b> (FLL)</li>
                                <li><b>FIRST Tech Challenge</b> (FTC)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="landing-carousel-control carousel-control">
                <div class="fr">
                    <span class="carousel-control-nav-next" onclick="$('.FIRST-carousel-bulllet').removeClass('active');carouselFIRST.next();if (carouselFIRST.index == 0){carouselFIRSTbg.select_move_to(0);$('#FIRST-carousel-bullet-1').addClass('active');} else if (carouselFIRST.index == 1){carouselFIRSTbg.select_move_to(0);$('#FIRST-carousel-bullet-2').addClass('active');} else if (carouselFIRST.index == 2){carouselFIRSTbg.select_move_to(1);$('#FIRST-carousel-bullet-3').addClass('active');}"></span>
                </div>
                <div class="fr carousel-control-nav-bullet-area">
                    <span id="FIRST-carousel-bullet-1" class="FIRST-carousel-bulllet carousel-control-nav-bullet active" onclick="carouselFIRST.select_move_to(0);carouselFIRSTbg.select_move_to(0);$('.FIRST-carousel-bulllet').removeClass('active');$(this).addClass('active')"></span>
                    <span id="FIRST-carousel-bullet-2" class="FIRST-carousel-bulllet carousel-control-nav-bullet" onclick="carouselFIRST.select_move_to(1);carouselFIRSTbg.select_move_to(0);$('.FIRST-carousel-bulllet').removeClass('active');$(this).addClass('active')"></span>
                    <span id="FIRST-carousel-bullet-3" class="FIRST-carousel-bulllet carousel-control-nav-bullet" onclick="carouselFIRST.select_move_to(2);carouselFIRSTbg.select_move_to(1);$('.FIRST-carousel-bulllet').removeClass('active');$(this).addClass('active')"></span>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<script>
var carouselFIRST;
var carouselFIRSTbg;
    
$(document).ready(function() {
    carouselFIRST = new Carousel('#FIRST-carousel');
    carouselFIRSTbg = new Carousel('#FIRST-bg-carousel');
});
</script>