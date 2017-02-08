<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>The HTML5 Herald</title>
  <meta name="description" content="The Male Online">
  <meta name="author" content="SitePoint">
  <meta name="viewport" content="width=device-width; initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no; " />

  <link rel="stylesheet" href="css/styles.css?v=1.0">

  <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css?family=Eczar:800" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

  <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->
</head>

<body>

<main>
    <header>
        <p>Header Header</p>
        <nav data-bind="navigation">
            <span></span>
            <span></span>
            <span></span>
        </nav>
    </header>
    <div class="mo-main">
        <p>test</p>
    </div>
    <div class="mo-sidebar" data-bind="sidebar">
        <ul>
            <li><input type="radio" name="sidebar-year" id="year-today">
                <label for="year-today">Today</label>
            </li>
            <?php foreach (range(2017, 1996) as $year_display_sidebar) { ?>
                <li><input type="radio" name="sidebar-year" id="year-<?php echo $year_display_sidebar ?>">
                    <label for="year-<?php echo $year_display_sidebar ?>"><?php echo $year_display_sidebar ?></label>
                </li>
            <?php } ?>
        </ul>
    </div>
</main>

<script src="//localhost:35729/livereload.js"></script>
</body>
</html>
<script>
(function(jQuery) {
	var MaleOnlineFunctions = function ($){
		var self = this;

		self.init = function() {
            navToggle();
		};
        self.navToggle = function() {
            $('[data-bind="navigation"]').on('click', function() {
                if (!$(this).hasClass('active')) {
                    $('[data-bind="sidebar"]').css('right', '-0');
                    $(this).addClass('active');
                } else {
                    $('[data-bind="sidebar"]').css('right', '-200px');
                    $(this).removeClass('active');
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
