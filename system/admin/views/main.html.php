<section style="padding-bottom: 10px;">
    <h3>Welcome <?php echo get_username() ?></h3>
    <p>What would you like to do?</p>

    <a class="action-box" href="http://www.spartabots.org/add/post">
        <i class="fa fa-pencil" style="margin-right: 5px;"></i>Write a blog post
    </a>

    <a class="action-box" href="http://www.spartabots.org/add/page">
        <i class="fa fa-pencil" style="margin-right: 5px;"></i>Create a new page
    </a>

    <?php if (get_user_role() === 'admin'): ?>
    <a class="action-box" href="http://www.spartabots.org/admin/file-upload">
        <i class="fa fa-upload" style="margin-right: 5px;"></i>Upload a file
    </a>
    <?php endif; ?>
    
    <?php if (get_user_role() === 'admin'):
    
    $stats = cf_stats(30); // 20 = Past 30 days, 30 = Past 7 days, 40 = Past day
    $pageViewsReg           = intval($stats['trafficPageViewsReg']);
    $pageViewsThreat        = intval($stats['trafficPageViewsThreat']);
    $pageViewsCrawler       = intval($stats['trafficPageViewsCrawler']);
    $pageViewsTotal         = $pageViewsReg + $pageViewsThreat + $pageViewsCrawler;
    
    $uniqueViewsReg         = intval($stats['trafficUniqueReg']);
    $uniqueViewsThreat      = intval($stats['trafficUniqueThreat']);
    $uniqueViewsCrawler     = intval($stats['trafficUniqueCrawler']);
    $uniqueViewsTotal       = $uniqueViewsReg + $uniqueViewsThreat + $uniqueViewsCrawler;
    
    echo <<<EOF
    <script src="//code.highcharts.com/highcharts.js"></script>
    <div class="admin-stats" style="border: 1px solid #e3e3e3; padding: 10px; margin-top: 20px; position:relative; padding-left: 240px; height: 175px;">
        <h2>Stats</h2>
        <div id="admin-stats-page-views-chart" style="width:240px;height:240px;position:absolute;top:0;bottom:0;margin:auto 0;left:0; margin-left: -20px"></div>
        <div class="admin-stats-page-views fl" style="font-size:15px;line-height:28px;margin-right:40px;">
            <div class="page-views-total" style="color: #404040; font-size: 18px;">$pageViewsTotal Page Views</div>
            <div class="page-views-regular" style="color: #4c9900;">$pageViewsReg regular traffic</div>
            <div class="page-views-crawler" style="color: #b500d9;">$pageViewsCrawler crawlers/bots</div>
            <div class="page-views-threat" style="color: #cc1414;">$pageViewsThreat threats</div>
        </div>
        <div class="admin-stats-unique-views fl" style="font-size:15px;line-height:28px;">
            <div>
                <i class="fa fa-info-circle" style="color: #aeaeae"></i>
                <span style="margin-left: 10px;font-style: italic;font-size: 12px;">Note: these values are estimates</span>
            </div>
            <div class="page-views-total" style="color: #4c9900;">$uniqueViewsTotal unique visitors</div>
        </div>
        <div class="clearfix"></div>
    </div>
    
    <script>
    $(function () {
        $('#admin-stats-page-views-chart').highcharts({
            chart: {
                backgroundColor: null,
                borderRadius: 0,
                margin: [0, 0, 0, 0]
            },
            credits: false,
            title: {
                text: null
            },
            tooltip: {
                formatter: function() {
                    return '<b>' + this.point.name + '</b><br/>'
                        + Highcharts.numberFormat(this.y, 0) + ' page views<br/>'
                        + '(' + parseInt(100 * (this.y / this.total)) + '% of total)'
                },
                backgroundColor: 'rgba(255, 255, 255, .9)'
            },
            plotOptions: {
                pie: {
                    dataLabels: {
                        enabled: !1
                    }
                },
                series: {
                    borderColor: "#f7f7f7",
                    borderWidth: 3,
                    stickyTracking: !1,
                    size: 216
                }
            },
            series: [{
                type: 'pie',
                name: 'Page Views',
                data: [
                    {
                        name: 'Regular traffix',
                        color: '#59b300',
                        y: $pageViewsReg
                    },{
                        name: 'Crawlers/bots',
                        color: '#b500d9',
                        y: $pageViewsCrawler
                    },{
                        name: 'Threats',
                        color: '#cc1414',
                        y: $pageViewsThreat
                    }
                ]
            }]
        });
    });
    </script>
EOF;
    
    endif; ?>

    <?php if (get_user_role() === 'admin'): ?>
    <div class="admin-panel-role-explain">Your user role is "admin". You have full access to the admin control panel.</div>
    <?php endif; if (get_user_role() === 'editor'): ?>
    <div class="admin-panel-role-explain">Your user role is "editor". You have limited access only to the content editing sections of the admin control panel.</div>
    <?php endif; ?>
</section>

<hr/>

<section>
    <?php 
    echo '<h2>Your recent posts</h2>';
    get_recent_posts(); ?>
    </section>
    <section>
    <?php
    echo '<h2>Static pages</h2>';
    get_recent_pages(); ?>
</section>