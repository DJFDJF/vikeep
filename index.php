<?php
/************************
File:		index.php
Date:		2013-06-20
Brief:		Main index file for vikeep website.
************************/
include('functions.inc.php');

?>
<!DOCTYPE html>
<html>
<!--
Legal notice:
    Vikeep. Keep viki.com videos, yours.
    Copyright (C) 2013  Victor Villarreal <mefhigoseth@gmail.com>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along
    with this program; if not, write to the Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
-->
<head>
<title>Vikeep - Keep viki.com videos, yours</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Vikeep - Keep yours viki.com videos">
<meta name="author" content="MefhigosetH">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>

<body>

<!-- NavBar -->
<div class="navbar navbar-inverse">
	<div class="navbar-inner">
		<a class="brand" href=".">Vikeep</a>
		<ul class="nav">
			<li class="active"><a href="#">Home</a></li>
		</ul>
		<p class="navbar-text pull-right">Beta</p>
	</div>
</div>

<div class="container">

<?php
if( isset($_GET['episode']) && !empty($_GET['episode']) ) {
	$viki = new vikiAPI();
?>
<!-- EpisodeResults -->
<div class="page-header">
<h1>Select your download preferences</h1>
</div>
<?php
	$vikiStreams = $viki->streams($_GET['episode']);
	$count = count($vikiStreams);

	if( $count ) {
		echo "<h3>1. Download subtitles. Choose your lang:</h3>";
		echo "<p>Right-click -> Save as...</p>";
		$strEsUrl = $viki->subtitles($_GET['episode'],"es");
		$strEnUrl = $viki->subtitles($_GET['episode'],"en");
		echo "<p><a href='".$strEsUrl."' title='Download spanish subtitles' class='btn btn-large btn-primary'>Spanish</a> <a href='".$strEnUrl."' title='Download english subtitles' class='btn btn-large btn-primary'>English</a></p>";

		echo "<h3>2. Download video. Choose your quality:</h3>";
		echo "<p>Right-click -> Save as...</p>";
		echo "<p>";
		foreach( $vikiStreams as $stream => $data ) {
			$quality = $stream;
			$url = $vikiStreams[$stream]['http']['url'];

			echo "<a href='".$url."' title='Download video in ".$quality."' class='btn btn-large btn-primary'>".$quality."</a> ";
		}
		echo "</p>";
	}
}
elseif( isset($_GET['serie']) && !empty($_GET['serie']) ) {
	$viki = new vikiAPI();
?>
<!-- SerieResults -->
<div class="page-header">
<h1>Select your episode</h1>
</div>
<?php
	$vikiEpisodes = $viki->episodes($_GET['serie']);
	$count = count($vikiEpisodes['response']);

	if( $count ) {
		echo "<div class='row'>\r\n";
		echo "<ul class='thumbnails'>\r\n";
		for($i=0;$i<$count;$i++) {
			$id = $vikiEpisodes['response'][$i]['id'];
			$number = $vikiEpisodes['response'][$i]['number'];
			$poster = $vikiEpisodes['response'][$i]['images']['poster']['url'];

			echo "<li class='span6'>\r\n";
			echo "<div class='thumbnail'>\r\n";
			echo "<img src='".$poster."' alt='Episode ".$number."' />\r\n";
			echo "<div class='caption'>\r\n";
			echo "<h3>Episode ".$number."</h3>\r\n";
			echo "<p><a href='index.php?episode=".$id."' class='btn btn-primary'>Download</a></p>\r\n";
			echo "</div></div></li>\r\n\r\n";
		}
		echo "</ul></div>\r\n";

	}
	else {
		echo "<p>Sin episodios.</p>";
	}
	//echo "<pre>";
	//print_r($vikiEpisodes);
	//echo "</pre>";
}
elseif( isset($_POST['q']) && !empty($_POST['q']) ) {
	$viki = new vikiAPI();
?>
<!-- SearchResults -->
<div class="page-header">
<h1>Search results <small>for <?php echo $_POST['q']; ?></small></h1>
</div>
<?php
	$vikiSearch = $viki->search($_POST['q']);
	$count = count($vikiSearch['response']);

	if( $count ){
		for( $i=0;$i<$count;$i++) {
			$title = $vikiSearch['response'][$i]['titles']['en'];
			$poster = $vikiSearch['response'][$i]['images']['poster']['url'];
			$episodes = $vikiSearch['response'][$i]['episodes']['count'];
			$id = $vikiSearch['response'][$i]['id'];

			if(!empty($episodes)) {
				echo "<div class='hero-unit text-center'>";
				echo "<h1>".$title."</h1>";
				echo "<p><img src='".$poster."' alt='".$title."' class='img-polaroid' /></p>";
				echo "<p><a class='btn btn-large btn-primary text-left' href='index.php?serie=".$id."' title='View episodes for ".$title."'>Episodes</a> <small>(".$episodes." episodes)</small></p>";
				echo "</div>";
			}
		}
	}
	else {
		echo "<p>No results.</p>";
	}
}
else {
?>
<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fvikeep.herokuapp.com&amp;send=false&amp;layout=standard&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:35px;" allowTransparency="true"></iframe>

<!-- HeroUnit -->
<div class="hero-unit text-center">
	<h1>Keep viki.com videos, yours.</h1>
	<p>Search what you want to download from viki.com</p>
	<form action="index.php" method="post">
	<input class="input-block-level" id="appendedInputButton" type="text" name="q" autocomplete="off" value="" />
	<button class="btn btn-large btn-primary" type="submit">Search!</button>
	</form>
</div>

<blockquote>
Vikeep allows you to search your favorite series and download to your computer the chapters and subtitles you want. All for free ;-)<small>About this site</small>
</blockquote>

<div class="row">
	<div class="span6">
	<div class="page-header">
		<h1>Blog <small>of news</small></h1>
	</div>
	<h3><span class="label label-info">2013-06-30</span> It's alive !</h3>
	<p>We are on-line now and we are very exiting for this new startup.</p>
	<p>These are some 'To Do' features that we are working on:</p>
	<ul>
		<li>Multi language.</li>
		<li>Add paging in search results.</li>
		<li>Add paging in episode list.</li>
	</ul>
	<p>Enjoy this site ! Love.</p>
	</div>

	<div class="span6">
	<div class="page-header">
		<h1>Colaborate <small>with us</small></h1>
	</div>
	<p>Keep this site free of Ads and on-line. Consider donating.</p>
	<br />
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" class="text-center">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="NFL5S9YQQW5F2">
	<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/es_XC/i/scr/pixel.gif" width="1" height="1">
	</form>

	</div>
</div>
<?php
}
?>

</div>

<!-- Footer -->
<footer class="text-center">
	<hr />
	<p>CopyLeft 2013 - <a href='http://www.mefhigoseth.com.ar/' title='Technology, our passion.'>[^MefhigosetH^]</a>. All rights dispersed !</p>
	<p>Powered by <a href='http://www.php.net/' title='PHP: Hypertext Preprocessor'>PHP</a>, <a href='http://twitter.github.io/bootstrap/' title='Twitter Bootstrap'>Bootstrap</a>, <a href='http://git-scm.com/' title='GIT: Fast Version Control'>GIT</a> &amp; <a href='http://en.wikipedia.org/wiki/God' title='The one that create the Universe with a single word'>God</a> | Made in Argentina</p>
</footer>

<script src="http://code.jquery.com/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-42109142-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>

</html>
