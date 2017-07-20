(function(jQuery) {
	var MaleOnlineFunctions = function ($){
		var self = this;
		var $chartistWordValues = [];
		var $chartistWordLabels = [];
		var trendsChart;
		var alpha = 1;
		self.init = function() {
			$(window).scrollTop(0);
			toggleDailyArticleSelection();
			highlightArticleTextAndCloneThumbnail();
			highlightWordInArticle('.word-chart', '.graph-article');
			setTrendsChart();
		};
		self.barBackgroundColors = function(len) {
			var colorAry = [];
			return randomColor({
				luminosity: 'dark',
				format: 'rgba',
				alpha: 0.3
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
			highlightWordInArticle('.daily-article-wrapper', '.article-text');
			$('.daily-article-wrapper').each(function() {
				var $id = $(this).attr('id');
				$(this).find('.today-word-articles-text .article-text:nth-child(2)').css('display', 'block');
				$(this).find('.today-word-articles-images img:first-child').clone().appendTo('#'+$id+'-thumbnail-placeholder');
			});
		};
		self.setTrendsChart = function() {
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
										suggestedMin: -1
									}
								}]
							}
						}
					});
					$chartistWordLabels = [];
					$chartistWordValues = [];
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
