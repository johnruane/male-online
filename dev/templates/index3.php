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
    <meta name="viewport" content="width=device-width; initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no; " />

    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.min.js" integrity="sha256-xNjb53/rY+WmG+4L6tTl9m6PpqknWZvRt0rO1SRnJzw=" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>

    <link rel="stylesheet" href="css/styles.css?v=1.0">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
    <link href="https://fonts.googleapis.com/css?family=Eczar:800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
    <![endif]-->
</head>

<body>
    <div class="site-wrapper">
        <div class="content-wrapper">
            <header>
                <p>The Male Online</p>
                <nav data-bind="navigation">
                    <span></span>
                    <span></span>
                    <span></span>
                </nav>
            </header>
            <main class="mo-content">
                <?php include 'daily-list.php' ?>
            </main>
        </div>
        <div id="sidebar-tab" class="mo-sidebar-container active" data-bind="sidebar">
            <ul class="mo-sidebar-tabs">
                <li><a href="#tab-1">Y</a></li>
                <li><a href="#tab-2">W</a></li>
            </ul>
            <div id="tab-1">
                <ul class="mo-sidebar-content">
                    <li><input type="radio" name="sidebar-year" value="today" id="year-today">
                        <label for="year-today" data-bind="sidebar-year-selection"><span>Today</span></label>
                    </li>
                    <?php foreach (range(2017, 1996) as $year_display_sidebar) { ?>
                        <li><input type="radio" name="sidebar-year" value="<?php echo $year_display_sidebar ?>" id="year-<?php echo $year_display_sidebar ?>">
                            <label for="year-<?php echo $year_display_sidebar ?>" class="sidebar-year" data-bind="sidebar-year-selection"><span><?php echo $year_display_sidebar ?></span></label>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div id="tab-2">
                <ul class="mo-sidebar-content">
                    <?php foreach (getBadWords() as $word_display_sidebar) { ?>
                        <li><input type="radio" name="sidebar-word" value="<?php echo $word_display_sidebar ?>" id="word-<?php echo $word_display_sidebar ?>">
                            <label for="word-<?php echo $word_display_sidebar ?>" data-bind="sidebar-word-selection"><span><?php echo $word_display_sidebar ?></span></label>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script src="//localhost:35729/livereload.js"></script>
</body>
</html>
        <script>
        (function(jQuery) {
            var MaleOnlineFunctions = function ($){
                var self = this;

                self.init = function() {
                    navToggle();
                    sidebarSelection();
                    // $('[for="year-today"]').trigger('click');
                    $('#sidebar-tab').tabs();
                    toggleCollapse();
                };
                self.navToggle = function() {
                    $('[data-bind="navigation"]').on('click', function() {
                        $('.mo-sidebar-container').toggleClass('active');
                    });
                };
                self.sidebarSelection = function() {
                    $('[data-bind="sidebar-year-selection"]').on('click', function() {
                        $sidebar_value = $(this).prev().val();
                        $main_component ="";
                        $data = "";
                        if ($sidebar_value == "today") {
                            $.get("daily-list.php", function(data) {
                                $('.mo-content').html(data);
                            });
                        } else {
                            $.ajax({
                                url: "yearly-list.php",
                                type: "POST",
                                data: {
                                    year: $sidebar_value
                                },
                                success: function(data) {
                                    $('.mo-content').html(data);
                                }
                            });
                        }
                    });
                    $('[data-bind="sidebar-word-selection"]').on('click', function() {
                        $sidebar_value = $(this).prev().val();
                        $main_component ="";
                        $data = "";
                        $.ajax({
                            url: "word-graph.php",
                            type: "POST",
                            data: {
                                word: $sidebar_value
                            },
                            success: function(data) {
                                $('.mo-content').html(data);
                                self.wordChart();
                            }
                        });
                    });
                };
                self.toggleCollapse = function() {
                    $('[data-toggle="collapse"]').on('click', function() {
                        if ($('.article-list-item').is(':visible')) {
                            $(this).slideUp(300);
                        }
                        var $id = $(this).data('target');
                        $($id).slideToggle(300);
                    });
                };
                self.wordChart = function() {
                    var $word_labels = [];
                    $('.mo-daily-list .word-key').each(function() {
                        var w = $(this).text();
                        $word_labels.push(w.slice(2));
                    })
                    var $word_values = [];
                    $('.mo-daily-list .word-value').each(function() {
                        $word_values.push(parseInt($(this).text()));
                    });

                    var data = {
                        labels: $word_labels,
                        series: [$word_values],
                    };
                    var options = {
                        lineSmooth: false,
                        axisX: {
                            showGrid: false
                        },
                        axisY: {
                            offset: 0,
                            showLabel: false
                        }
                    };

                    // Create a new line chart object where as first parameter we pass in a selector
                    // that is resolving to our chart container element. The Second parameter
                    // is the actual data object.
                    new Chartist.Line('.ct-chart', data, options);
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
