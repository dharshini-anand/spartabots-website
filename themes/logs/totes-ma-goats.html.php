<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name=viewport content="width=device-width, initial-scale=1">
    <link href="//www.spartabots.org/favicon.ico?v=3" rel="shortcut icon">
    <title>TOTES MA GOATS</title>
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="<?php echo site_url() ?>system/resources/jquery-canvas-sparkles.min.js"></script>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,400,300,600&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic" type="text/css" media="all">
    <style>
    * {
        margin: 0;
        padding: 0;
        outline: 0;
    }
    
    html, body {
        height: 100%;
    }

    body {
        font-family: 'open sans', arial, sans-serif;
        font-size: 17px;
        line-height: 22px;
        font-style: normal;
        color: rgb(68, 68, 68);
            background: red; /* not working, let's see some red */
        background: black;
        overflow: hidden;
    }

    img {
        border: 0 none;
        height: auto;
        max-width: 100%;
        outline: 0 none;
    }
    
    .wrapper {
        margin: 60px auto;
        max-width: 620px;
        padding: 20px;
    }
    
    #full-video {
        position: absolute;;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -100;
    }
    
    #goats4-video-outer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        margin: 0 auto;
        width: 250px;
        height: auto;
    }
    
    #goats4-video {
        width: 250px;
        height: auto;
    }
    
    .rainbow {
        background-image: -webkit-gradient(linear,left top,right top,color-stop(0,#f22),color-stop(0.15,#f2f),color-stop(0.3,#22f),color-stop(0.45,#2ff),color-stop(0.6,#2f2),color-stop(0.75,#2f2),color-stop(0.9,#ff2),color-stop(1,#f22));
        background-image: gradient(linear,left top,right top,color-stop(0,#f22),color-stop(0.15,#f2f),color-stop(0.3,#22f),color-stop(0.45,#2ff),color-stop(0.6,#2f2),color-stop(0.75,#2f2),color-stop(0.9,#ff2),color-stop(1,#f22));
        color: transparent;
        -webkit-background-clip: text;
        background-clip: text;
    }
    
    .flying-image {
        position: fixed;
        max-width: 150px;
        height: auto;
        border: 8px solid transparent;
    }
    .flying-text {
        display: inline-block;
        position: fixed;
        font-siez: 24px;
    }
    
    .spinOtherWay {
        -webkit-animation: spinOtherWay 1s ease-in-out;
        -moz-animation: spinOtherWay 1s ease-in-out;
        -o-animation: spinOtherWay 1s ease-in-out;
        -ms-animation: spinOtherWay 1s ease-in-out;
        animation: spinOtherWay 1s ease-in-out;
    }
    .spin {
        -webkit-animation: spin 1s ease-in-out;
        -moz-animation: spin 1s ease-in-out;
        -o-animation: spin 1s ease-in-out;
        -ms-animation: spin 1s ease-in-out;
        animation: spin 1s ease-in-out;
    }
    
    .spinContinuousOtherWay {
        -webkit-animation: spinOtherWay 1s infinite linear;
        -moz-animation: spinOtherWay 1s infinite linear;
        -o-animation: spinOtherWay 1s infinite linear;
        -ms-animation: spinOtherWay 1s infinite linear;
        animation: spinOtherWay 1s infinite linear;
    }
    .spinContinuous {
        -webkit-animation: spin 1s infinite linear;
        -moz-animation: spin 1s infinite linear;
        -o-animation: spin 1s infinite linear;
        -ms-animation: spin 1s infinite linear;
        animation: spin 1s infinite linear;
    }
    .spinContinuousFast {
        -webkit-animation: spin 400ms infinite linear;
        -moz-animation: spin 400ms infinite linear;
        -o-animation: spin 400ms infinite linear;
        -ms-animation: spin 400ms infinite linear;
        animation: spin 400ms infinite linear;
    }
    
    @-moz-keyframes spin {
        100% { -moz-transform: rotate(360deg); transform:rotate(360deg); }
    }
    @-o-keyframes spin {
        100% { -o-transform: rotate(360deg); transform:rotate(360deg); }
    }
    @-ms-keyframes spin {
        100% { -ms-transform: rotate(360deg); transform:rotate(360deg); }
    }
    @-webkit-keyframes spin {
        100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); }
    }
    @keyframes spin { 
        100% { transform: rotate(360deg); transform:rotate(360deg); } 
    }
    @-moz-keyframes spinOtherWay {
        100% { -moz-transform: rotate(-360deg); transform:rotate(-360deg); }
    }
    @-o-keyframes spinOtherWay {
        100% { -o-transform: rotate(-360deg); transform:rotate(-360deg); }
    }
    @-ms-keyframes spinOtherWay {
        100% { -ms-transform: rotate(-360deg); transform:rotate(-360deg); }
    }
    @-webkit-keyframes spinOtherWay {
        100% { -webkit-transform: rotate(-360deg); transform:rotate(-360deg); }
    }
    @keyframes spinOtherWay { 
        100% { transform: rotate(-360deg); transform:rotate(-360deg); } 
    }
    
    .rainbow-h {
        -webkit-animation:rainbow 500ms infinite;
        -moz-animation:rainbow 500ms infinite;
        -ms-animation:rainbow 500ms infinite;
        -o-animation:rainbow 500ms infinite;
        animation:rainbow 500ms infinite;
    }
    
    @-webkit-keyframes rainbow {
    0% {color: #ff0000;}
    10% {color: #ff8000;}
    20% {color: #ffff00;}
    30% {color: #80ff00;}
    40% {color: #00ff00;}
    50% {color: #00ff80;}
    60% {color: #00ffff;}
    70% {color: #0080ff;}
    80% {color: #0000ff;}
    90% {color: #8000ff;}
    100% {color: #ff0080;}
    }
    @-ms-keyframes rainbow {
    0% {color: #ff0000;}
    10% {color: #ff8000;}
    20% {color: #ffff00;}
    30% {color: #80ff00;}
    40% {color: #00ff00;}
    50% {color: #00ff80;}
    60% {color: #00ffff;}
    70% {color: #0080ff;}
    80% {color: #0000ff;}
    90% {color: #8000ff;}
    100% {color: #ff0080;}
    }
    @-o-keyframes rainbow {
    0% {color: #ff0000;}
    10% {color: #ff8000;}
    20% {color: #ffff00;}
    30% {color: #80ff00;}
    40% {color: #00ff00;}
    50% {color: #00ff80;}
    60% {color: #00ffff;}
    70% {color: #0080ff;}
    80% {color: #0000ff;}
    90% {color: #8000ff;}
    100% {color: #ff0080;}
    }
    @-moz-keyframes rainbow {
    0% {color: #ff0000;}
    10% {color: #ff8000;}
    20% {color: #ffff00;}
    30% {color: #80ff00;}
    40% {color: #00ff00;}
    50% {color: #00ff80;}
    60% {color: #00ffff;}
    70% {color: #0080ff;}
    80% {color: #0000ff;}
    90% {color: #8000ff;}
    100% {color: #ff0080;}
    }
    @keyframes rainbow {
    0% {color: #ff0000;}
    10% {color: #ff8000;}
    20% {color: #ffff00;}
    30% {color: #80ff00;}
    40% {color: #00ff00;}
    50% {color: #00ff80;}
    60% {color: #00ffff;}
    70% {color: #0080ff;}
    80% {color: #0000ff;}
    90% {color: #8000ff;}
    100% {color: #ff0080;}
    }
    
    
    .rainbowBorder {
        -webkit-animation:rainbowBorder 500ms infinite;
        -moz-animation:rainbowBorder 500ms infinite;
        -ms-animation:rainbowBorder 500ms infinite;
        -o-animation:rainbowBorder 500ms infinite;
        animation:rainbowBorder 500ms infinite;
    }
    @-webkit-keyframes rainbowBorder {
    0% {border-color: #ff0000;}
    10% {border-color: #ff8000;}
    20% {border-color: #ffff00;}
    30% {border-color: #80ff00;}
    40% {border-color: #00ff00;}
    50% {border-color: #00ff80;}
    60% {border-color: #00ffff;}
    70% {border-color: #0080ff;}
    80% {border-color: #0000ff;}
    90% {border-color: #8000ff;}
    100% {border-color: #ff0080;}
    }
    @-ms-keyframes rainbowBorder {
    0% {border-color: #ff0000;}
    10% {border-color: #ff8000;}
    20% {border-color: #ffff00;}
    30% {border-color: #80ff00;}
    40% {border-color: #00ff00;}
    50% {border-color: #00ff80;}
    60% {border-color: #00ffff;}
    70% {border-color: #0080ff;}
    80% {border-color: #0000ff;}
    90% {border-color: #8000ff;}
    100% {border-color: #ff0080;}
    }
    @-o-keyframes rainbowBorder {
    0% {border-color: #ff0000;}
    10% {border-color: #ff8000;}
    20% {border-color: #ffff00;}
    30% {border-color: #80ff00;}
    40% {border-color: #00ff00;}
    50% {border-color: #00ff80;}
    60% {border-color: #00ffff;}
    70% {border-color: #0080ff;}
    80% {border-color: #0000ff;}
    90% {border-color: #8000ff;}
    100% {border-color: #ff0080;}
    }
    @-moz-keyframes rainbowBorder {
    0% {border-color: #ff0000;}
    10% {border-color: #ff8000;}
    20% {border-color: #ffff00;}
    30% {border-color: #80ff00;}
    40% {border-color: #00ff00;}
    50% {border-color: #00ff80;}
    60% {border-color: #00ffff;}
    70% {border-color: #0080ff;}
    80% {border-color: #0000ff;}
    90% {border-color: #8000ff;}
    100% {border-color: #ff0080;}
    }
    @keyframes rainbowBorder {
    0% {border-color: #ff0000;}
    10% {border-color: #ff8000;}
    20% {border-color: #ffff00;}
    30% {border-color: #80ff00;}
    40% {border-color: #00ff00;}
    50% {border-color: #00ff80;}
    60% {border-color: #00ffff;}
    70% {border-color: #0080ff;}
    80% {border-color: #0000ff;}
    90% {border-color: #8000ff;}
    100% {border-color: #ff0080;}
    }
    
    
    .rainbowBackground {
        -webkit-animation:rainbowBackground 500ms infinite;
        -moz-animation:rainbowBackground 500ms infinite;
        -ms-animation:rainbowBackground 500ms infinite;
        -o-animation:rainbowBackground 500ms infinite;
        animation:rainbowBackground 500ms infinite;
    }
    @-webkit-keyframes rainbowBackground {
    0% {background-color: #ff0000;}
    10% {background-color: #ff8000;}
    20% {background-color: #ffff00;}
    30% {background-color: #80ff00;}
    40% {background-color: #00ff00;}
    50% {background-color: #00ff80;}
    60% {background-color: #00ffff;}
    70% {background-color: #0080ff;}
    80% {background-color: #0000ff;}
    90% {background-color: #8000ff;}
    100% {background-color: #ff0080;}
    }
    @-ms-keyframes rainbowBackground {
    0% {background-color: #ff0000;}
    10% {background-color: #ff8000;}
    20% {background-color: #ffff00;}
    30% {background-color: #80ff00;}
    40% {background-color: #00ff00;}
    50% {background-color: #00ff80;}
    60% {background-color: #00ffff;}
    70% {background-color: #0080ff;}
    80% {background-color: #0000ff;}
    90% {background-color: #8000ff;}
    100% {background-color: #ff0080;}
    }
    @-o-keyframes rainbowBackground {
    0% {background-color: #ff0000;}
    10% {background-color: #ff8000;}
    20% {background-color: #ffff00;}
    30% {background-color: #80ff00;}
    40% {background-color: #00ff00;}
    50% {background-color: #00ff80;}
    60% {background-color: #00ffff;}
    70% {background-color: #0080ff;}
    80% {background-color: #0000ff;}
    90% {background-color: #8000ff;}
    100% {background-color: #ff0080;}
    }
    @-moz-keyframes rainbowBackground {
    0% {background-color: #ff0000;}
    10% {background-color: #ff8000;}
    20% {background-color: #ffff00;}
    30% {background-color: #80ff00;}
    40% {background-color: #00ff00;}
    50% {background-color: #00ff80;}
    60% {background-color: #00ffff;}
    70% {background-color: #0080ff;}
    80% {background-color: #0000ff;}
    90% {background-color: #8000ff;}
    100% {background-color: #ff0080;}
    }
    @keyframes rainbowBackground {
    0% {background-color: #ff0000;}
    10% {background-color: #ff8000;}
    20% {background-color: #ffff00;}
    30% {background-color: #80ff00;}
    40% {background-color: #00ff00;}
    50% {background-color: #00ff80;}
    60% {background-color: #00ffff;}
    70% {background-color: #0080ff;}
    80% {background-color: #0000ff;}
    90% {background-color: #8000ff;}
    100% {background-color: #ff0080;}
    }
    </style>
    <style>
    .tilting {
        -webkit-animation: tilting 200ms infinite linear;
        -moz-animation: tilting 200ms infinite linear;
        -o-animation: tilting 200ms infinite linear;
        -ms-animation: tilting 200ms infinite linear;
        animation: tilting 200ms infinite linear;
    }
    @-webkit-keyframes tilting {
    0% {transform: rotate(-20deg);}
    50% {transform: rotate(20deg;}
    100% {transform: rotate(-20deg;}
    }
    @-moz-keyframes tilting {
    0% {transform: rotate(-20deg);}
    50% {transform: rotate(20deg;}
    100% {transform: rotate(-20deg;}
    }
    @-ms-keyframes tilting {
    0% {transform: rotate(-20deg);}
    50% {transform: rotate(20deg;}
    100% {transform: rotate(-20deg;}
    }
    @-o-keyframes tilting {
    0% {transform: rotate(-20deg);}
    50% {transform: rotate(20deg;}
    100% {transform: rotate(-20deg;}
    }
    @keyframes tilting {
    0% {transform: rotate(-20deg);}
    50% {transform: rotate(20deg;}
    100% {transform: rotate(-20deg;}
    }
    </style>
    <style>
    .growShrink {
        -webkit-animation: growShrink 250ms infinite linear;
        -moz-animation: growShrink 250ms infinite linear;
        -o-animation: growShrink 250ms infinite linear;
        -ms-animation: growShrink 250ms infinite linear;
        animation: growShrink 250ms infinite linear;
    }
    @-webkit-keyframes growShrink {
    0% {transform: scale(0.9);}
    50% {transform: scale(1.2;}
    100% {transform: scale(0.5;}
    }
    @-moz-keyframes growShrink {
    0% {transform: scale(0.9);}
    50% {transform: scale(1.2;}
    100% {transform: scale(0.5;}
    }
    @-ms-keyframes growShrink {
    0% {transform: scale(0.9);}
    50% {transform: scale(1.2;}
    100% {transform: scale(0.5;}
    }
    @-o-keyframes growShrink {
    0% {transform: scale(0.9);}
    50% {transform: scale(1.2;}
    100% {transform: scale(0.5;}
    }
    @keyframes growShrink {
    0% {transform: scale(0.9);}
    50% {transform: scale(1.2;}
    100% {transform: scale(0.5;}
    }
    </style>
    <style>
    .horizShake {
        -webkit-animation: horizShake 200ms infinite linear;
        -moz-animation: horizShake 200ms infinite linear;
        -o-animation: horizShake 200ms infinite linear;
        -ms-animation: horizShake 200ms infinite linear;
        animation: horizShake 200ms infinite linear;
    }
    @-webkit-keyframes horizShake {
    0% {transform:translate(-20px,0);}
    0% {margin-left:translate(20px,0);}
    }
    @-moz-keyframes horizShake {
    0% {transform:translate(-20px,0);}
    0% {margin-left:translate(20px,0);}
    }
    @-ms-keyframes horizShake {
    0% {transform:translate(-20px,0);}
    0% {margin-left:translate(20px,0);}
    }
    @-o-keyframes horizShake {
    0% {transform:translate(-20px,0);}
    0% {margin-left:translate(20px,0);}
    }
    @keyframes horizShake {
    0% {transform:translate(-20px,0);}
    0% {margin-left:translate(20px,0);}
    }
    </style>
    <script>
    $(document).ready(function() {
        $('.flying-object').each(function() {
            animateDiv($(this));
        });
        
        nextBlink();
        nextFlyingObjectMutation();
        
        $(".static-image, #header, .flying-object").sparkle({
            color: "rainbow",
            count: 100,
            overlap: 10
        });
    });
    
    var blink_state = true;
    function nextBlink() {
        setTimeout(function(){
            if (blink_state) {
                $('.blinking').css('opacity', 0);
            } else {
                $('.blinking').css('opacity', 1);
            }
            blink_state = !blink_state;
            nextBlink();
        }, 200);
    }
    
    function nextFlyingObjectMutation() {
        setTimeout(function(){
            $('.flying-object').each(function(index) {
                var x = Math.random();
                if (x < 0.2) {
                    $(this).addClass('spinContinuous');
                } else if (x > 0.85) {
                    $(this).addClass('spinContinuousFast');
                } else if (x > 0.70) {
                    $(this).addClass('rainbowBorder');
                    $(this).addClass('rainbowBackground');
                } else {
                    $(this).removeClass('spinContinuous');
                    $(this).removeClass('spinContinuousFast');
                    $(this).removeClass('rainbowBorder');
                    $(this).removeClass('rainbowBackground');
                }
                if (index % 2 == 0) {
                    $(this).addClass('spin');
                } else {
                    $(this).addClass('spinOtherWay');
                }
            });
            setTimeout(function(){
                $('.flying-object').removeClass('spin');
                $('.flying-object').removeClass('spinOtherWay');
                nextFlyingObjectMutation();
            }, 1000);
        }, 3000);
    }
    
    // From http://jsfiddle.net/j2PAb/

    function makeNewPosition($container) {

        // Get viewport dimensions (remove the dimension of the div)
        var h = $container.height() - 50;
        var w = $container.width() - 50;

        var nh = Math.floor(Math.random() * h);
        var nw = Math.floor(Math.random() * w);

        return [nh, nw];

    }

    function animateDiv($target) {
        var newq = makeNewPosition($target.parent());
        var oldq = $target.offset();
        var speed = calcSpeed([oldq.top, oldq.left], newq);

        $target.animate({
            top: newq[0],
            left: newq[1]
        }, speed, function() {
            animateDiv($target);
        });

    };

    function calcSpeed(prev, next) {

        var x = Math.abs(prev[1] - next[1]);
        var y = Math.abs(prev[0] - next[0]);

        var greatest = x > y ? x : y;

        var speedModifier = 0.6;

        var speed = Math.ceil(greatest / speedModifier);

        return speed;

    }
    </script>
</head>
<body>
    <div style="text-align: center;padding-top: 40px; position: fixed; z-index: 800; width: 100%;">
        <div id="header" class="rainbow-h" style="display:inline-block;font-size:58px;line-height:58px;"><span>TOTES MA GOATS</span></div>
    </div>
    
    <!-- Top right image -->
    <img class="static-image spinContinuous" src="<?php echo site_url() ?>images/goat1.jpg" style="height:auto;width:250px;position:absolute;top:0;right:0;" />
    <!-- Top left image -->
    <img class="static-image blinking" src="<?php echo site_url() ?>images/tote.jpg" style="height:auto;width:200px;position:absolute;top:0;left:0;" />
    <!-- Bottom left image -->
    <img class="static-image tilting" src="<?php echo site_url() ?>images/goat8.jpg" style="height:250px;width:auto;position:absolute;left:0;bottom:0;" />
    <!-- Bottom right image -->
    <img class="static-image growShrink" src="<?php echo site_url() ?>images/goats3.png" style="height:200px;width:auto;position:absolute;right:0;bottom:0;" />
    
    <!-- Bottom center image -->
    <div id="goats4-video-outer" class="static-image horizShake">
        <video autoplay loop id="goats4-video" width="320" height="240">
            <source src="<?php echo site_url() ?>images/goats4.webm" type="video/webm">
        </video>
    </div>
    
    <!-- Background video -->
    <video autoplay loop id="full-video" width="320" height="240">
        <source src="<?php echo site_url() ?>images/RainbowTroloload.webm" type="video/webm">
        <source src="<?php echo site_url() ?>images/RainbowTroloload.mp4" type="video/mp4">
    </video>
    
    <!-- Annoying flying things -->
    <div class="flying-object flying-image">
        <img src="<?php echo site_url() ?>images/RecycleRush-TransparentBG.png" />
    </div>
    <div class="flying-object flying-image" style="max-height:150px;width:auto">
        <img src="<?php echo site_url() ?>images/dean-kamen-bnw-soft-effect-whiter.png" style="max-height:150px;width:auto" />
    </div>
    <div class="flying-object flying-image">
        <img src="<?php echo site_url() ?>images/goat1.jpg" />
    </div>
    <div class="flying-object flying-image">
        <img src="<?php echo site_url() ?>images/goat5.jpg" />
    </div>
    <div class="flying-object flying-image" style="max-height:150px;width:auto">
        <img src="<?php echo site_url() ?>images/goat7.jpg" style="max-height:150px;width:auto" />
    </div>
    <div class="flying-object flying-image" style="max-height:150px;width:auto">
        <img src="<?php echo site_url() ?>images/goat6.jpg" style="max-height:150px;width:auto" />
    </div>
    <div class="flying-object flying-image">
        <img src="<?php echo site_url() ?>images/FRC_FIRSTBlue.gif" />
    </div>
    <div class="flying-object flying-image">
        <img src="<?php echo site_url() ?>images/first-logo-small.png" />
    </div>
    <div class="flying-object flying-text rainbow-h">SUCH GOATS</div>
    <div class="flying-object flying-text rainbow-h">SUCH TOTES</div>
    <div class="flying-object flying-text rainbow-h">TOTES MA GOATS</div>
    <div class="flying-object flying-text rainbow-h">GOATS!GOATS!GOATS!</div>
    <div class="flying-object flying-text rainbow-h">MUCH TOTES</div>
    <div class="flying-object flying-text rainbow-h">MUCH GOATS</div>
    <div class="flying-object flying-text rainbow-h">WOW</div>
    <div class="flying-object flying-text rainbow-h">AMAZEBALLS</div>
</body>
</html>