(function(jQuery) {
	var MaleOnlineFunctions = function ($){
		var self = this;
		var $chartistWordValues = [];
		var $chartistWordLabels = [];
		var trendsChart;
		self.init = function() {
			toggleDailyArticleSelection();
			highlightArticleTextAndCloneThumbnail();
			highlightWordInArticle('.word-chart', '.graph-article');
			$('#trends-tab').one('click', function(event) {
				setTrendsChart();
				$(this).off(event);
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
			highlightWordInArticle('.daily-article-wrapper', '.article-text-span');
			$('.daily-article-wrapper').each(function() {
				var $id = $(this).attr('id');
				$(this).find('.today-word-articles-text .article-text:nth-child(2)').css('display', 'block');
				$(this).find('.today-word-articles-images img:first-child').clone().appendTo('#'+$id+'-thumbnail-placeholder');
			});
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
			var $articleSpan = $(this);
			var $articleText = $($articleSpan).text();
			var $link = $($articleSpan).find('a.graph-link');

			var wordStart = $articleText.toLowerCase().indexOf($id);
			var beforeWord = $articleText.slice(0, wordStart);
			var word = $articleText.slice(wordStart, wordStart+$id.length);
			var afterWord = $articleText.slice(wordStart+$id.length, $articleText.length);

			var newText = beforeWord + '<span class="article-highlight">' + word + '</span>' + afterWord;
			$($articleSpan).html(newText);
		});
	});
}

// $.fn.isOnScreen = function(){
// 	var win = $(window);
// 	var viewport = {
// 		top : win.scrollTop(),
// 		left : win.scrollLeft()
// 	};
// 	viewport.right = viewport.left + win.width();
// 	viewport.bottom = viewport.top + win.height();
// 	var bounds = this.offset();
// 	bounds.right = bounds.left + this.outerWidth();
// 	bounds.bottom = bounds.top + this.outerHeight();
// 	return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
// };
