<?php
	$stats = "";
	$error = "";
	if ($_GET['battletag']) {
		$file_headers = @get_headers("https://playoverwatch.com/en-us/career/pc/us/".$_GET['battletag']);
		if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {
			$error = "cannot find the battletag - please make sure the format is correct"; 
		} else {
			$page = file_get_contents("https://playoverwatch.com/en-us/career/pc/us/".$_GET['battletag']);
			$contents = explode('<div class="u-relative">', $page); 
			$tempStats = explode('<div class="bg-crystal-dark profile-background">', $contents[1]);  
			$stats = $tempStats[0]; 
			 // $stats = '<div data-js="heroMastheadImage" data-hero-quickplay="soldier-76" data-hero-competitive="winston" class="masthead-hero-image quickplay"></div><div class="u-max-width-container row content-box gutter-18"><div class="column lg-8"><div class="masthead"><div class="masthead-player"><img src="https://d1u1mce87gyfbn.cloudfront.net/game/unlocks/0x02500000000008E8.png" class="player-portrait"><h1 class="header-masthead">JADE</h1><div class="masthead-player-progression show-for-lg"><div style="background-image:url("https://d1u1mce87gyfbn.cloudfront.net/game/playerlevelrewards/0x025000000000093F_Border.png")" class="player-level"><div class="u-vertical-center">59</div><div style="background-image:url("https://d1u1mce87gyfbn.cloudfront.net/game/playerlevelrewards/0x025000000000093F_Rank.png")" class="player-rank"></div></div><div class="competitive-rank"><img src="https://d1u1mce87gyfbn.cloudfront.net/game/rank-icons/season-2/rank-3.png"/><div class="u-align-center h5">2062</div></div></div></div><p class="masthead-detail h4"><span>533 games won</span></p><div id="profile-platforms" class="masthead-buttons button-group js-button-group loading"></div><div class="masthead-player-progression hide-for-lg"><div style="background-image:url("https://d1u1mce87gyfbn.cloudfront.net/game/playerlevelrewards/0x025000000000093F_Border.png")" class="player-level"><div class="u-vertical-center">59</div><div style="background-image:url(https://d1u1mce87gyfbn.cloudfront.net/game/playerlevelrewards/0x025000000000093F_Rank.png)" class="player-rank"></div></div><div class="competitive-rank"><img src="https://d1u1mce87gyfbn.cloudfront.net/game/rank-icons/season-2/rank-3.png"/><div class="u-align-center h5">2062</div></div></div></div></div></div></div></section>';

		}
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Scrim Finder</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<style type="text/css">
	body{
		height:100%;
   		width:100%;
   		background-image:url(ow-images/bg.jpg);/*your background image*/  
   		background-repeat:no-repeat;/*we want to have one single image not a repeated one*/  
   		background-size:cover;/*this sets the image to fullscreen covering the whole screen*/  
	}
	#stats {
		height: 800px;
		margin-top: 10px;
	}
	</style>
</head>
<body>
	<div class="container">
	<form>
		<fieldset class="form-group">
			<label for="battletag" style="color: white"> Enter the player's battletag</label>
			<input class="form-control" id="battletag" name="battletag" type="text" placeholder="Eg. ov-1234">
			<p class="text-muted">warning: searches are case sensitive</p>
		</fieldset>
		<button class="btn btn-primary" id="submit" type="submit">Search</button>
	</form>
	</div>
	<div class="container" id="stats">
		<div id="stats"><?php if ($stats) {
			echo '<div class="alert alert-success" role="alert">'.$stats.'</div>'; 
		} ?>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/javascript.util/0.12.12/javascript.util.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</body>
</html>