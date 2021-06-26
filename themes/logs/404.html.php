<!DOCTYPE html>
<html lang="en">
    <head>
        <link href='<?php echo site_url() ?>favicon.ico?v=3' rel='icon' type='image/x-icon'/>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name=viewport content="width=device-width, initial-scale=1">
        <title>404 Not Found - <?php echo blog_title() ?></title>
        <style>
        * { margin:0; padding:0; outline:0; border:0; }
        html{ height: 100%; }
        body {
            background: #FFF;
            color: #777;
            font-family: Lato, 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 16px;
            line-height: 22px;
            overflow-x: hidden;
        }
        
        a {
            color: #2BAD4A;
            text-decoration: none;
        }
        a:hover {
            text-decoration: none;
            color: #24913E;
        }
        </style>
    </head>
    <body>
        <div id="main">
        <div style="max-width:860px;width:100%;margin:0 auto;padding-top:60px;box-sizing:border-box;-moz-box-sizing:border-box;">
            <div style="font:60px/1.5 'Segoe UI SemiLight', 'Helvetica Neue', Helvetica, Arial, sans-serif; color: black">404</div>
            <div style="font:30px/1.5 'Segoe UI SemiLight', 'Helvetica Neue', Helvetica, Arial, sans-serif; color: rgb(153, 153, 153); margin-top: -18px">Not found</div>
            
            <p style="font:16px/1.5 'Segoe UI SemiLight', 'Helvetica Neue', Helvetica, Arial, sans-serif;">
            The requested file could not be found.
            </p>

            <p style="font:16px/1.5 'Segoe UI SemiLight', 'Helvetica Neue', Helvetica, Arial, sans-serif; margin-top: 25px;">
            <a href="<?php echo site_url() ?>">Return to home</a>
            </p>
        </div>
        </div>
    </body>
</html>