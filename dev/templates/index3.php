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
            <li><input type="radio" name="sidebar-year" id="year-2017">
                <label for="year-2017">2017</label>
            </li>
            <li><input type="radio" name="sidebar-year" id="year-2016">
                <label for="year-2016">2016</label>
            </li>
            <li><input type="radio" name="sidebar-year" id="year-2015">
                <label for="year-2015">2015</label>
            </li>
            <li><input type="radio" name="sidebar-year" id="year-2014">
                <label for="year-2014">2014</label>
            </li>
            <li><input type="radio" name="sidebar-year" id="year-2013">
                <label for="year-2013">2013</label>
            </li>
            <li><input type="radio" name="sidebar-year" id="year-2012">
                <label for="year-2012">2012</label>
            </li>
            <li><input type="radio" name="sidebar-year" id="year-2011">
                <label for="year-2011">2011</label>
            </li>
            <li><input type="radio" name="sidebar-year" id="year-2010">
                <label for="year-2010">2010</label>
            </li>
            <li><input type="radio" name="sidebar-year" id="year-2009">
                <label for="year-2009">2009</label>
            </li>
            <li><input type="radio" name="sidebar-year" id="year-2008">
                <label for="year-2008">2008</label>
            </li>
            <li><input type="radio" name="sidebar-year" id="year-2007">
                <label for="year-2007">2007</label>
            </li>
            <li><input type="radio" name="sidebar-year" id="year-2006">
                <label for="year-2006">2006</label>
            </li>
            <li><input type="radio" name="sidebar-year" id="year-2005">
                <label for="year-2005">2005</label>
            </li>
            <li><input type="radio" name="sidebar-year" id="year-2004">
                <label for="year-2004">2004</label>
            </li>
            <li><input type="radio" name="sidebar-year" id="year-2003">
                <label for="year-2003">2003</label>
            </li>
            <li><input type="radio" name="sidebar-year" id="year-2002">
                <label for="year-2002">2002</label>
            </li>
            <li><input type="radio" name="sidebar-year" id="year-2001">
                <label for="year-2001">2001</label>
            </li>
            <li><input type="radio" name="sidebar-year" id="year-2000">
                <label for="year-2000">2000</label>
            </li>
            <li><input type="radio" name="sidebar-year" id="year-1999">
                <label for="year-1999">1999</label>
            </li>
            <li><input type="radio" name="sidebar-year" id="year-1998">
                <label for="year-1998">1998</label>
            </li>
            <li><input type="radio" name="sidebar-year" id="year-1997">
                <label for="year-1997">1997</label>
            </li>
            <li><input type="radio" name="sidebar-year" id="year-1996">
                <label for="year-1996">1996</label>
            </li>
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
