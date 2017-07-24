(function(jQuery) {
	var MaleOnlineFunctions = function ($){
		var self = this;
		var $chartistWordValues = [];
		var $chartistWordLabels = [];
		var trendsChart;
		self.init = function() {
			$(window).scrollTop(0);
			toggleDailyArticleSelection();
			highlightArticleTextAndCloneThumbnail();
			highlightWordInArticle('.word-chart', '.graph-article');
			setTrendsChart();
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
		self.setTrendsChart = function() {
			var color_count = 0;
			var colors = graphColors(42);
			$('#trends-tab').on('click', function() {
				$('.word-chart').each( function() {
					var chartid = $(this).attr('id');
					var $graph_vals = $('#'+chartid).find('.word-value');
					var $graph_labels = $('#'+chartid).find('.word-key');
					$($graph_vals).each(function() {
						$chartistWordValues.push(parseInt($(this).text()));
					});
					$($graph_labels).each(function() {
						$chartistWordLabels.push($(this).text());
					});
					var $chartcolor = colors[color_count];
					trendsChart = new Chart(document.getElementById(chartid + '-canvas').getContext('2d'), {
						type: 'line',
						data: {
							labels: $chartistWordLabels,
							datasets: [{
								data: $chartistWordValues,
								radius: 0,
								borderWidth: 2,
								borderColor: $chartcolor,
								fill: false
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
								xAxes: [{ // horizontal
									display: false
								}],
								yAxes: [{ // vertical
									display: false,
									gridLines: {
										display: false
									},
									ticks: {
										suggestedMin: -1,
										max: 100
									}
								}]
							},
							elements: {
								line: {
									tension: 0.2
								}
							},
							layout: {
								padding: {
									left: 5
								}
							}
						}
					});
					$chartistWordLabels = [];
					$chartistWordValues = [];
					color_count++;
				});
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

function graphColors(number) {
	var alpha = 1
	var col_set = [ 'rgba(182, 211, 219,'+alpha+')',
					'rgba(248, 182, 219,'+alpha+')',
					'rgba(204, 195, 220,'+alpha+')',
					'rgba(178, 214, 196,'+alpha+')',
					'rgba(237, 199, 178,'+alpha+')',
					'rgba(235, 231, 241,'+alpha+')',
					'rgba(183, 220, 181,'+alpha+')',
					'rgba(232, 213, 184,'+alpha+')',
					'rgba(178, 192, 210,'+alpha+')',
					'rgba(245, 233, 185,'+alpha+')'];
	var cols = [];
	var division = Math.floor(number / col_set.length);
	var remainder = number - (division * col_set.length);
	for (i=0; i<division; i++) {
		cols = cols.concat(col_set);
	}
	for (i=0; i<remainder; i++) {
		cols.push(col_set[i]);
	}
	return cols;
}
