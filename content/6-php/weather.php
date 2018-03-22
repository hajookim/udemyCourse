<?php  
	$weather = "";
	$error = ""; 
	if ($_GET['city']) {
		$city = str_replace(' ', '', $_GET['city']);
		$file_headers = @get_headers("http://www.weather-forecast.com/locations/".$city."/forecasts/latest");

		if ($file_headers[9] == 'HTTP/1.1 404 Not Found') {
			$error = "That city could not be found"; 
		}
        else {
			$forecastPage = file_get_contents("http://www.weather-forecast.com/locations/".$city."/forecasts/latest");
			$pageArray = explode('3 Day Weather Forecast Summary:</b><span class="read-more-small"><span class="read-more-content"> <span class="phrase">', $forecastPage); 
			if (sizeof($pageArray) > 1) {
				$secondPageArray = explode('</span></span></span>', $pageArray[1]); 
				if (sizeof($secondPageArray) > 1) {
					$weather = $secondPageArray[0];	
				} else {
					$error = "That city could not be found"; 
				}
				
			} else {
				$error = "That city could not be found"; 
			}
			
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