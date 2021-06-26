<section>
<h2><?php echo $heading?></h2>
<?php

// configuration
//$url = site_url() . 'admin/config-edit';
$url = 'http://www.google.com';
$file = 'config/config.ini';

// check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
} else {
    // read the textfile
    $opts = array( 
        'http' => array( 
            'method'=>"GET", 
            'header'=>"Content-Type: text/html; charset=utf-8" 
        ) 
    ); 

    $context = stream_context_create($opts);

    $oldcontent = file_get_contents($file, false, $context);
}

if (!empty($error)) {
    echo("Error: $error");
}
?>

<p>This file is very important. An error in this file can cause the entire website to fail. If a error in this file were to occur
please log into the website's FTP server and edit the "config/config.ini" file.
</p>

<!-- HTML form -->
<form method="POST">
<textarea name="content" style="width:100%;resize:vertical;min-height:400px;padding:2px;"><?php echo htmlentities($oldcontent) ?></textarea><br/>
<input type="submit" name="submit" />
<input type="reset" name="reset" />
</form>
</section>