(function(jQuery) {
    var MaleOnlineFunctions = function ($){
        var self = this;
        var $chartistWordValues = [];
        var $chartistWordLabels = [];
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
			$(window).scrollTop(0);
            toggleDailyArticleSelection();
            highlightArticleTextAndCloneThumbnail();
			highlightWordInArticle('.word-chart', '.graph-article');
			scrollIntoView();
			setVisible();
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
			highlightWordInArticle('.daily-article-wrapper', '.article-text');
            $('.daily-article-wrapper').each(function() {
				var $id = $(this).attr('id');
                $(this).find('.today-word-articles-text .article-text:nth-child(2)').css('display', 'block');
				$(this).find('.today-word-articles-images img:first-child').clone().appendTo('#'+$id+'-thumbnail-placeholder');
            });
        };
        self.setTrendsChart = function(chartid) {
            var $graph_vals = $('#'+chartid).find('.word-value');
            var $graph_labels = $('#'+chartid).find('.word-key');
            $($graph_vals).each(function() {
                $chartistWordValues.push(parseInt($(this).text()));
            });
            $($graph_labels).each(function() {
                $chartistWordLabels.push($(this).text());
            });
            var $chartcolor = barBackgroundColors(1);
        	trendsChart = new Chart(document.getElementById(chartid + '-canvas').getContext('2d'), {
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
								display: false
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
        };
		self.scrollIntoView = function() {
			var didScroll = false;
			window.onscroll = doThisStuffOnScroll;
			function doThisStuffOnScroll() {
			    didScroll = true;
			}
			setInterval(function() {
			    if(didScroll) {
			        didScroll = false;
					setVisible();
			    }
			}, 100);
		};
		self.setVisible = function() {
			$('.word-chart').each( function() {
				if ($(this).isOnScreen() && !$(this).hasClass('loaded'))) {
					setTrendsChart($(this).attr('id'));
					$(this).addClass('loaded');
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

function highlightWordInArticle(articleContainerClass, articleTextClass) {
	$(articleContainerClass).each(function() {
		var $id = $(this).data('highlighter');
		$(this).find(articleTextClass).each(function() {
			var $articleSpan = $(this);
			var $articleText = $($articleSpan).text();

			var wordStart = $articleText.toLowerCase().indexOf($id);
			var beforeWord = $articleText.slice(0, wordStart);
			var word = $articleText.slice(wordStart, wordStart+$id.length);
			var afterWord = $articleText.slice(wordStart+$id.length, $articleText.length);

			var newText = beforeWord + '<span class="article-highlight">' + word + '</span>' + afterWord;
			$($articleSpan).html(newText);
		});
	});
}

$.fn.isOnScreen = function(){
    var win = $(window);
    var viewport = {
        top : win.scrollTop(),
        left : win.scrollLeft()
    };
    viewport.right = viewport.left + win.width();
    viewport.bottom = viewport.top + win.height();
    var bounds = this.offset();
    bounds.right = bounds.left + this.outerWidth();
    bounds.bottom = bounds.top + this.outerHeight();
    return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
};
