(function(jQuery) {
	var MaleOnlineFunctions = function ($){
		var self = this;
		var $chartistWordValues = [];
		var $chartistWordLabels = [];
		var trendsChart;
		self.init = function() {
			initDailyArticles();
			$('#trends-tab').one('click', function(event) {
				setTrendsChart();
				$(this).off(event);
			});
		};
		self.initDailyArticles = function() {
			$('[data-toggle="today-article"]').on('click', function(event) {
				swapDailyArticle($(event.target));
			});
			$('.daily-article-wrapper').each(function() {
				var $firstThumbnail = $(this).find('.today-word-articles-images > :first-child');
				swapDailyArticle($firstThumbnail);
			});
		};
		self.swapDailyArticle = function(image) {
			var parent = image.closest('.daily-article-wrapper');
			var src = image[0].dataset.src;
			var href = image[0].dataset.href;
			var text = image[0].dataset.article;
			$(parent).find('.thumbnail-placeholder').html('<img src="'+ src +'"></img>');
			$(parent).find('.article-link').html('<a class="graph-link" href="' + href + '" target="_blank">Go to full article</a>');
			var $articleText = $(parent).find('.article-text');
			$($articleText)[0].innerHTML = highlightWordInArticle(text, $($articleText).data('highlighter'));
		};
		self.setTrendsChart = function() {
			$('.word-chart').each( function() {
				var $id = $(this).attr('id');
				var $word = $(this).attr('id');
				var $data = $('#'+$id).find('.ct-chart').data('trend-graph');
				var $jdata = JSON.parse('{'+$data+'}');
				var data = {
					series: [Object.values($jdata)],
					labels: Object.keys($jdata)
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

function highlightWordInArticle(text, word) {
	var wordStart = text.toLowerCase().indexOf(word);
	var beforeWord = text.slice(0, wordStart);
	var word = text.slice(wordStart, wordStart+word.length);
	var afterWord = text.slice(wordStart+word.length, text.length);
	var newText = beforeWord + '<span class="article-highlight">' + word + '</span>' + afterWord;
	return newText;
}
