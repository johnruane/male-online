<?php
require_once("mo.php");
require_once("conf.php");
require_once("db.php");
ini_set("error_reporting","-1");
ini_set("display_errors","On");

$sort_array = array();
?>
<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>The Male Online</title>
    <meta name="description" content="The Male Online">
    <meta name="author" content="SitePoint">
    <meta name="viewport" content="width=device-width; initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no;" />

    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
    <script src="js/jquery.resize.js"></script>
    <script src="js/jquery.randomColor.js"></script>
    <script src="js/bootstrap-tab.js"></script>
    <script src="js/rangeslider.min.js"></script>

    <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
    <link rel="stylesheet" href="css/rangeslider.css?v=1.0">
    <link rel="stylesheet" href="css/styles.css?v=1.0">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Bigshot+One" rel="stylesheet">


    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
    <![endif]-->
</head>

<body>
    <div class="site-wrapper">
        <header class="main-header">
            <a class="site-logo" href="/index4.php">
                <span class="flam-text">Male </span>
                <span class="thin-text">Online</span>
            </a>
            <nav class="nav-icon" data-bind="menu">
                <span></span>
                <span></span>
                <span></span>
            </nav>
        </header>
        <main class="main-content">
            <div>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a id="today-tab" href="#today" aria-controls="today" role="tab" data-toggle="tab">Today</a></li>
                    <li role="presentation"><a id="trends-tab" href="#trends" aria-controls="trends" role="tab" data-toggle="tab">Trends</a></li>
                    <li role="presentation"><a id="years-tab" href="#years" aria-controls="years" role="tab" data-toggle="tab">Years</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="today">
                        <div class="graph-wrapper trends">

                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="trends">
                        <div class="graph-wrapper trends">
                            <?php foreach (getBadWords() as $word): ?>
                                <?php include 'word-graph.php' ?>
                            <?php endforeach ?>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="years">
                        <div class="graph-wrapper">
                            <input type="range" min="2001" max="2017" value="2001" step="1" data-rangeslider>
                            <h3 id="slider-output" class="year-range-slider">2001</h3>
                            <div class="graph-container yearly-chart clearfix">
                            <?php foreach ($years as $year): ?>
                                <?php $yearlyResults = getYearlyTotals($year); ?>
                                <ul class="hidden-word-results chart-values-<?php echo $year ?>">
                                    <?php foreach ($yearlyResults as $row): ?>
                                        <li><span class="yearly-word-key"><?php echo $row['word'] ?></span>
                                        <span class="yearly-word-value"><?php echo $row['count'] ?></span></li>
                                    <?php endforeach ?>
                                </ul>
                            <?php endforeach ?>
                            <canvas id="yearsChart" height="600"></canvas>
                            <!-- <div class="ct-chart yearly-chart"></div> -->
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            Footer footer
        </footer>
    </div>
    <script src="//localhost:35729/livereload.js"></script>
</body>
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.js"></script>
<script>
(function(jQuery) {
    var MaleOnlineFunctions = function ($){
        var self = this;
        var $chartistWordValues = [];
        var $chartistWordLabels = [];
        var $chartistYearlyWordValues = [];
        var $chartistYearlyWordLabels = [];
        var yearsChart;
        var trendsChart;
        self.init = function() {
            // menuToggle();
            // archiveToggle();
            // sidebarSelection();
            // // $('[for="year-today"]').trigger('click');
            // $('#sidebar-tab').tabs();
            // toggleCollapse();
            // $('.content-wrapper').resize(resize)
            tabShow();
        };
        self.setYearChart = function() {
            if (typeof $year === "undefined") {
                $year = $('#slider-output').text();
            }
            var $yealry_graph_vals = $('.chart-values-'+$year).find('.yearly-word-value');
            var $yealry_graph_labels = $('.chart-values-'+$year).find('.yearly-word-key');
            $($yealry_graph_labels).each(function() {
                $chartistYearlyWordLabels.push($(this).text());
            });
            $($yealry_graph_vals).each(function() {
                $chartistYearlyWordValues.push(parseInt($(this).text()));
            });
            var ctx = document.getElementById('yearsChart').getContext('2d');
            yearsChart = new Chart(ctx, {
                type: 'horizontalBar',
                data: {
                    labels: $chartistYearlyWordLabels,
                    datasets: [{
                        data: $chartistYearlyWordValues,
                        backgroundColor: barBackgroundColors($yealry_graph_labels.length)
                    }]
                },
                axisOptions: {
                    gridLines: {
                        offsetGridLines: true
                    }
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            stacked: true,
                        }],
                        yAxes: [{
                            stacked: true
                        }]
                    }
                }
            });
            $chartistYearlyWordLabels = [];
            $chartistYearlyWordValues = [];
        };
        self.updateYearsChart = function($year) {
            var $yealry_graph_vals = $('.chart-values-'+$year).find('.yearly-word-value');
            var $chartistUpdatedYearlyWordValues = [];
            $($yealry_graph_vals).each(function() {
                $chartistUpdatedYearlyWordValues.push(parseInt($(this).text()));
            });
            yearsChart.data.datasets[0].data = $chartistUpdatedYearlyWordValues;
            yearsChart.update();
        };
        self.resize = function() {
            if ( $(trendsChart).length > 0 ) {
                $(trendsChart).get(0).__chartist__.update();
            }
        };
        self.archiveToggle = function() {
            $('[data-bind="archive"]').on('click', function() {
                $('.site-wrapper').toggleClass('archive');
                var $sbar = $('#sidebar-tab');
                // if ($($sbar).hasClass('active')) {
                //     $($sbar).children('.sidebar-panel').css('display', 'block');
                // } else {
                //     setTimeout(function () {
                //         $($sbar).children('.sidebar-panel').css('display', 'none');
                //     }, 500);
                // }
            });
        };
        self.barBackgroundColors = function(len) {
            var colorAry = [];
            for (i=0;i<len;i++) {
                colorAry.push(randomColor({
                    format: 'rgba',
                    alpha: 0.3})
                );
            }
            return colorAry;
        }
        self.menuToggle = function() {
            $('[data-bind="menu"]').on('click', function() {
                $('.site-wrapper').toggleClass('menu');
            });
        };
        self.tabShow = function() {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var $tab = $(this).attr('id');
                switch($tab) {
                    case "trends-tab":
                        setTrendsChart();
                        break;
                    case "years-tab":
                        rangeslider();
                        setYearChart();
                        break;
                    default:
                        break;
                }
            });
        };
        self.toggleCollapse = function() {
            $('[data-toggle="collapse"]').on('click', function() {
                // if ($(this).attr('aria-expanded') == "true") {
                //     $(this).attr('aria-expanded', 'false');
                //     $($(this).data('target')).slideUp(300);
                // } else {
                //     var $current = $('.results-list li[aria-expanded="true"]');
                //     $($current).attr('aria-expanded', 'false');
                //     $($($current).data('target')).slideUp(300);
                //
                //     var $target = $(this).data('target');
                //     $(this).attr('aria-expanded', 'true');
                //     $($target).slideDown(300);
                // }
                var $target = $(this).data('target');
                $($target).dialog({
                    modal: true,
                    show: 'fade',
                    hide: 'fade',
                    closeText: "X",
                    resizable: false,
                    draggable: false,
                    open: function(event, ui) {
                        $('body').addClass('modal-open');
                    },
                    beforeClose: function(event, ui) {
                        $('body').removeClass('modal-open');
                        $(this).dialog('destroy');
                    }
                });
            });
        };
        self.setTrendsChart = function() {
            $('.word-chart').each(function() {
                var $id = $(this).attr('id');
                var $graph_vals = $('#'+$id).find('.word-value');
                var $graph_labels = $('#'+$id).find('.word-key');
                $($graph_vals).each(function() {
                    $chartistWordValues.push(parseInt($(this).text()));
                });
                $($graph_labels).each(function() {
                    $chartistWordLabels.push($(this).text());
                });
                var $chartcolor = barBackgroundColors(1);
                var ctx = document.getElementById($id + '-canvas').getContext('2d');
                yearsChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: $chartistWordLabels,
                        datasets: [{
                            data: $chartistWordValues,
                            radius: 0,
                            borderWidth: 1,
                            borderColor: $chartcolor,
                            backgroundColor: $chartcolor
                        }]
                    },
                    options: {
                        animation: false,
                        legend: {
                            display: false
                        },
                        scales: {
                             xAxes: [{
                                 display: false
                             }],
                             yAxes: [{
                                 display: false
                             }]
                         }
                    }
                });
                $chartistWordLabels = [];
                $chartistWordValues = [];
            });
        };
        self.rangeslider = function() {
            $('input[type="range"]').rangeslider({
                polyfill: false,
                onSlide: function(position, value) {
                    $('#slider-output').text(value);
                },
                onSlideEnd: function(position, value) {
                    updateYearsChart(value);
                }
            });
        };
        return {
            init: init,
        }
    };
    // Setup the global object and run init on document ready
    $(function(){
        window.MaleOnlineFunctions = MaleOnlineFunctions(jQuery);
        window.MaleOnlineFunctions.init();
    });
})(jQuery);
</script>
