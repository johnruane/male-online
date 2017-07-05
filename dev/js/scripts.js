(function(jQuery) {
    var MaleOnlineFunctions = function ($){
        var self = this;
        var $chartistWordValues = [];
        var $chartistWordLabels = [];
        var $chartistYearlyWordValues = [];
        var $chartistYearlyWordLabels = [];
        var yearsChart;
        var trendsChart;
        var alpha = 1;
        var todayColors = [
            [0, 'rgba(28, 186, 184)'],
            [10, 'rgba(0, 142, 135)'],
            [20, 'rgba(6, 143, 69)'],
            [30, 'rgba(103, 189, 69)'],
            [40, 'rgba(254, 231, 1)'],
            [50, 'rgba(248, 150, 29)'],
            [60, 'rgba(243, 111, 32)'],
            [70, 'rgba(238, 49, 36)'],
            [80, 'rgba(202, 57, 144)'],
            [90, 'rgba(42, 75, 155)']
        ];
        self.init = function() {
            toggleDailyArticleSelection();
            highlightArticleTextAndCloneThumbnail();
            showTabs();
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
            var barColour = barBackgroundColors($yealry_graph_labels.length);
            yearsChart = new Chart(ctx, {
                type: 'horizontalBar',
                data: {
                    labels: $chartistYearlyWordLabels,
                    datasets: [{
                        data: $chartistYearlyWordValues,
                        backgroundColor: barColour,
                        borderColor: barColour,
                        borderWidth: 0
                    }]
                },
                options: {
					animation: {
					  onProgress: drawBarValues,
					  onComplete: drawBarValues,
					  duration: 1000,
					  easing: 'easeOutBounce'
					},
					hover: { animationDuration: 0 },
                    responsive: true,
                    maintainAspectRatio: true,
                    scaleShowVerticalLines: false,
					showTooltips: true,
					layout: {
						padding: {
							left: 5,
							right: 20
						}
					},
                    legend: {
                        display: false,
                    },
                    scales: {
                        yAxes: [{ // horizontal lines
							gridLines: {
								 display: true,
								 drawBorder: true,
								 drawOnChartArea: false
							},
							ticks: {
								fontColor: '#000',
								fontStyle: 'italic'								
							}
                        }],
						xAxes: [{ // vertical lines
							position: 'top',
							gridLines: {
								 display: true,
								 drawBorder: true,
								 drawOnChartArea: true
							}
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
        self.barBackgroundColors = function(len) {
            var colorAry = [];
            if (len === 1) {
                return randomColor({
					luminosity: 'dark',
                    format: 'rgba',
                    alpha: 0.3});
            } else {
                for (i=0;i<len;i++) {
                    colorAry.push(randomColor({
                        format: 'rgba',
                        alpha: 0.3})
                    );
                }
                return colorAry;
            }
        };
        self.showTabs = function() {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                var $tab = $(this).attr('id');
                switch($tab) {
                    case "trends-tab":
                        if ( $('.word-chart .chartjs-hidden-iframe').length == 0 ) {
                            setTrendsChart();
                        }
                        break;
                    case "years-tab":
                        rangeslider();
                        if ( $('.yearly-chart .chartjs-hidden-iframe').length == 0 ) {
                            setYearChart();
                        }
                        break;
                    default:
                        break;
                }
            });
        };
        self.toggleDailyArticleSelection = function() {
            $('[data-toggle="trends-reveal"]').on('click', function(event) {
				// changes the article text
                $(this).parents('.today-list-item').find('.article-text').css('display', 'none');
                $id = $(this).data('id');
                $($id).css('display', 'block');
				// changes the article image
				var $placeholder = $($id).parent('.today-word-articles-text').children('.thumbnail-placeholder');
				$($placeholder).empty();
				var clone = $(this).clone().appendTo($placeholder);
            });
        };
        self.highlightArticleTextAndCloneThumbnail = function() {
            $('.daily-article-wrapper').each(function() {
                var $id = $(this).attr('id');
                $(this).find('.article-text').each(function() {
                    var $articleSpan = $(this).find('span');
                    var $articleText = $($articleSpan).text();

                    var wordStart = $articleText.toLowerCase().indexOf($id);
                    var beforeWord = $articleText.slice(0, wordStart);
                    var word = $articleText.slice(wordStart, wordStart+$id.length);
                    var afterWord = $articleText.slice(wordStart+$id.length, $articleText.length);

                    var newText = beforeWord + '<span class="article-highlight">' + word + '</span>' + afterWord;
                    $($articleSpan).html(newText);
                });
                $(this).find('.today-word-articles-text .article-text:nth-child(2)').css('display', 'block');
				$(this).find('.today-word-articles-images img:first-child').clone().appendTo('#'+$id+'-thumbnail-placeholder');
            });
        };
        self.setTrendsChart = function() {
            $('[data-bind="word-chart"]').each(function() {
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
                trendsChart = new Chart(ctx, {
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
						responsive: true,
						maintainAspectRatio: true,
                        animation: false,
                        legend: {
                            display: false
                        },
                        scales: {
                             xAxes: [{
								 display: false
                             }],
                             yAxes: [{
								display: false,
								gridLines: {
									display: false
								},
								scaleLabel: {
									display: false,
									labelString: "Mentions"
								}
                             }]
                         },
						 gridLines: {
							 display: true
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
        self.setTodayColor = function() {
            var $percentVal = 0;
            var $highestVal = parseInt($('.card-list .card:first-child .word-value').text());

            $('.card-list .card').each(function() {
                var $colVal = $(this).find('.word-value').text();
                $percentVal = 100 * (parseInt($colVal) / $highestVal);
                for (var i = todayColors.length - 1; i >=0; --i ) {
                    if ($percentVal > todayColors[i][0]) {
                        var a = todayColors[i][1];
                        var alpha = ', 0.1';
                        var col1 = [a.slice(0, a.length - 1), alpha, a.slice(a.length - 2)].join('')
                        $(this).css('background-color', col1);
                        var alpha = ', 0.3';
                        var col2 = [a.slice(0, a.length - 1), alpha, a.slice(a.length - 2)].join('')
                        $(this).css('border', '1px solid ' + col2);
                        break;
                    }
                }
            });
        }
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

function drawBarValues() {
	var ctx = this.chart.ctx;
	ctx.font = Chart.helpers.fontString(8, 'normal', Chart.defaults.global.defaultFontFamily);
	ctx.fillStyle = this.chart.config.options.defaultFontColor;
	ctx.textAlign = 'left';
	ctx.textBaseline = 'bottom';
	this.data.datasets.forEach(function (dataset) {
		for (var i = 0; i < dataset.data.length; i++) {
			if(dataset.hidden === true && dataset._meta[Object.keys(dataset._meta)[0]].hidden !== false){ continue; }
			var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model;
			if(dataset.data[i] !== null && dataset.data[i] != 0){
				ctx.fillText(dataset.data[i], model.x + 2, model.y + 5); // x=hor, y=vert
			}
		}
	});
}
