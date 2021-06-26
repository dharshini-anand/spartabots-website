<?php
$ch = curl_init("https://www.cloudflare.com/api_json.html");
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);

$interval = $_REQUEST['interval'];
if (!isset($interval)) {
	echo 'Please specify the "interval" parameter with any of the following values:<br/>';
	echo '20 (past 30 days), 30 (past 7 days), or 40 (past day)';
	exit();
}

$intervalStr = null;
if ($interval == 20) {
    $intervalStr = '30 days';
} else if ($interval == 30) {
    $intervalStr = '7 days';
} else if ($interval == 40) {
    $intervalStr = 'day';
} else {
    exit('Error: The interval parameter must either be 20 (30 days), 30 (7 days), or 40 (past day)');
}

// handling of -d curl parameter is here.
$param = array(
    'a' => 'stats',
    'tkn' => '77b1c49a1ceae08a87f6ed10094fe1ac3b012',
    'email' => 'skyline.spartabots@gmail.com',
    'z' => 'spartabots.org',
    'interval' => $interval
);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));

$result = curl_exec($ch);
curl_close($ch);

$data = json_decode($result, true)['response']['result']['objs'][0];

$trafficPageViews = $data['trafficBreakdown']['pageviews'];
$trafficPageViewsReg = $trafficPageViews['regular'];
$trafficPageViewsThreat = $trafficPageViews['threat'];
$trafficPageViewsCrawler = $trafficPageViews['crawler'];

$trafficUnique = $data['trafficBreakdown']['uniques'];
$trafficUniqueReg = $trafficUnique['regular'];
$trafficUniqueThreat = $trafficUnique['threat'];
$trafficUniqueCrawler = $trafficUnique['crawler'];

$bwServedCF = $data['bandwidthServed']['cloudflare'];
$bwServedUser = $data['bandwidthServed']['user'];

$reqServedCF = $data['requestsServed']['cloudflare'];
$reqServedUser = $data['requestsServed']['user'];

echo <<<EOF
    <!doctype html>
    <html>
    <head>
        <meta charset='utf-8' />
        <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />
        <meta name=viewport content='width=device-width, initial-scale=1' />
        <title>CF Statistics | Spartabots</title>
        <style>
        * {
            padding: 0;
            margin: 0;
        }
        body {
            font-family: "Courier New", Courier, monospace;
            font-size: 15px;
        }
        #wrapper {
            padding: 10px;
        }
        </style>
    </head>
    <body>
        <div id="wrapper">
EOF;
echo "##################################################<br/>";
echo "#&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;spartabots.org&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;#<br/>";
echo "#&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ClouldFlare&nbsp;Statistics&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;#<br/>";
echo "##################################################<br/>";
echo "<br/>";
echo "Statistics Report from past $intervalStr<br/>";
echo "==================================================<br/><br/>";

echo "Traffic: Page Views<br/>";
echo "--------------------------------------------------<br/>";
echo "Regular: $trafficPageViewsReg<br/>";
echo "Threats: $trafficPageViewsThreat<br/>";
echo "Crawlers: $trafficPageViewsCrawler<br/>";
echo "</br><br/>";

echo "Traffic: Unique Views<br/>";
echo "--------------------------------------------------<br/>";
echo "Regular: $trafficUniqueReg<br/>";
echo "Threats: $trafficUniqueThreat<br/>";
echo "Crawlers: $trafficUniqueCrawler<br/>";
echo "</br><br/>";

echo "Bandwidth Served<br/>";
echo "--------------------------------------------------<br/>";
echo "CloudFlare: $bwServedCF (KB)<br/>";
echo "User: $bwServedUser (KB)<br/>";
echo "</br><br/>";

echo "Requests Served<br/>";
echo "--------------------------------------------------<br/>";
echo "CloudFlare: $reqServedCF<br/>";
echo "User: $reqServedUser<br/>";

echo '</div></body></html>';
?>