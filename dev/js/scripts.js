(function(jQuery) {
	var MaleOnlineFunctions = function ($){
		var self = this;
		var $chartistWordValues = [];
		var $chartistWordLabels = [];
		var trendsChart;
		self.init = function() {
			initDailyArticle();
			highlightWordInArticle('.word-chart', '.graph-article');
			$('#trends-tab').one('click', function(event) {
				setTrendsChart();
				$(this).off(event);
			});
		};
		self.initDailyArticle = function() {
			$('[data-toggle="today-article"]').on('click', function(event) {
				// changes the article text
				var $src = event.target.dataset.src;
				var $articleText = event.target.dataset.article;
				var $link = event.target.dataset.href;
				var $prev = $(this).prev();
				$prev.children('.thumbnail-placeholder').html('<img src="'+ $src +'"></img>');
				$prev.children('.article-text').html($articleText + '<a class="graph-link" href="' + $link + '" target="_blank">Go to full article</a>');
				highlightWordInArticle('.daily-article-wrapper', '.article-text');
			});
			$('.today-list-item').each(function() {
				$(this).find('.today-word-articles-images > :first-child').trigger('click');
			});
			highlightWordInArticle('.daily-article-wrapper', '.article-text');
		};
		self.setTrendsChart = function() {
			$('.word-chart').each( function() {
				var $id = $(this).attr('id');
				var $word = $(this).attr('id');
				var $graph_vals = $('#'+$id).find('.word-value').map(function() {
					return $(this).text();
				}).get();
				var $graph_labels = $('#'+$id).find('.word-key').map(function() {
					return $(this).text();
				}).get();
				var data = {
					series: [$graph_vals],
					labels: $graph_labels
				};
				var options = {
					lineSmooth: true,
					showArea: true,
					fullWidth: true,
					bezierCurve:false,
					showPoint: false,
					chartPadding: {
					  right: 5,
					  bottom: -28,
					},
					axisX: {
						showGrid: false,
						showLabel: false
					},
					axisY: {
						offset: 0,
						showGrid: false,
						showLabel: false,
					}
				};
				var $mychart = new Chartist.Line('.'+$word, data, options);
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
			var $this = $(this);
			var articleText = $($this).text();
			var $link = $($this).find('a.graph-link');

			var wordStart = articleText.toLowerCase().indexOf($id);
			var beforeWord = articleText.slice(0, wordStart);
			var word =articleText.slice(wordStart, wordStart+$id.length);
			var afterWord = articleText.slice(wordStart+$id.length, articleText.length);

			var newText = beforeWord + '<span class="article-highlight">' + word + '</span>' + afterWord;
			$($this).html(newText + $link);
		});
	});
}
