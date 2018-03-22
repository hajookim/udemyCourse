<?php  
	$weather = "";
	$error = ""; 
	if ($_GET['city']) {
		
		$urlContents = file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=".urlencode($_GET['city']).",uk&appid=369e5e92b9dee4f8e5a7b2e016253125"); 
		$weatherArray = json_decode($urlContents, true);
		print_r($weatherArray);
		if ($weatherArray['cod'] == 200) {
			$weather = "The weather in ".urlencode($_GET['city'])." is currently '".$weatherArray['weather'][0]['description']."'. ";
			$temp = $weatherArray['main']['temp'] - 273; 
			$weather .= "The temperature is ".intval($temp)."&deg;C. "; 
			$windSpeed = $weatherArray['wind']['speed'];
			$weather .= "The wind speed is ".$windSpeed." m/s.";
		} else {
			$error = "The city could not be found - please try again"; 
		}

	}

	
	

?>

<!DOCTYPE html>
<html>
<head>
	<title>Weather Scraper</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<style type="text/css">
		.container {
			text-align: center;
             margin-top: 100px;
             width: 450px;
        
		}
		body {
			height: 100%;
			background: url("weather.jpg");
			background-repeat: no-repeat;
			background-size: cover;
		}

		#weather {
			margin-top: 15px;
		}
	</style>

</head>
<body>
	<div class="container">
		<h1 class="display-3"><strong>What's The Weather?</strong></h1>
		<!-- <form>
			<fieldset class="form-group">
    			<label for="city">Enter the name of a city.</label>
    			<input type="text" class="form-control" name="city" id="city" placeholder="Eg. London, Tokyo" value = "<?php if (array_key_exists('city', $_GET)) { echo $_GET['city']; }?>">
    		</fieldset>
    		<button type="submit" class="btn btn-primary">Submit</button>
		</form> -->
		
		<form>
			<fieldset class="form-group">
				<label for="city">Enter the name of a city.</label>
				<input class="form-control" id="city" name="city" type="text" placeholder="Eg. London, Tokyo" value= "<?php echo $_GET['city']; ?>">
			</fieldset>
			<button class="btn btn-primary" id="submit" type="submit">Submit</button>
		</form>
		<div id="weather"><?php 
			if ($weather) {
				echo '<div class="alert alert-success" role="alert">'.$weather.'</div>';
			} else if ($error) {
				echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
			}
		?>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/javascript.util/0.12.12/javascript.util.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</body>
</html>