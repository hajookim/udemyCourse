<?php
	
	$error = "";
	$successMessage = ""; 
	if ($_POST) {
		if (!$_POST["email"]) {
			$error .= "An email is required <br>";
		}
		if (!$_POST["subject"]) {
			$error .= "An subject is required <br>";
		}
		if (!$_POST["content"]) {
			$error .= "A content is required <br>";
		}
	}
	if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
  		$error .= "The email address is invalid. <br>";
  	}

	if ($error != "") {
		$error = '<div class="alert alert-danger" role="alert"><strong>There were error(s) in your form: </strong> <br>' . $error . '</div>';
	} else {
		$to = "me@mydomain.com";
		$subject = $_POST["subject"];
		$content = $_POST["content"]; 
		$header = "FROM: ".$_POST['email']; 
		if (mail($to, $subject, $content, $header)) {
			$successMessage = '<div class="alert alert-success" role="alert"><strong> Your message was sent, we\'ll get back to you ASAP! </strong> <br>' . $error . '</div>';
		} else {
			$error = '<div class="alert alert-danger" role="alert"><strong> Your message couldn\'t be sent - please try again later </div>';
		}
	}

	

?>
<!DOCTYPE html>
<html>
<head>

	<title></title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<style>
		.form-control {
			margin-bottom: 10px; 
		}
	</style>
</head>
<body>
	<div class="container">
		<h1>Get in touch!</h1>
		<div id="error"><? echo $error.$successMessage; ?></div>
		<form method="POST">
			<fieldset class="form-group">
				<label for="email">Email address</label>
				<input class = "form-control" id="email" type="email" name="email">
				<p class="text-muted">We'll never share your email with anyone else.</p>
			</fieldset>
			<fieldset class="form-group">
				<label for="subject">Subject</label>
				<input class = "form-control" id="subject" type="text" name="subject">
			</fieldset>
			 
			<fieldset class="form-group">
				<label for="content">What would you like to ask us?</label>
				<textarea class = "form-control" id="content" type="text" name="content" rows="3"> </textarea>
			</fieldset>

			<button class="btn btn-primary" id="submit" type="submit">Submit</button> 
		</form>
		

		
	</div>
	
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/javascript.util/0.12.12/javascript.util.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <script type="text/javascript">
		$("form").submit(function (e) {
			var error = "";
			if ($("#email").val() == "") {
				console.log("empty"); 
				error += "<p>The email field is required. </p>";
			}
			if ($("#subject").val() == "") {
				console.log("empty"); 
				error += "<p>The subject field is required. </p>";
			}
			if ($("#content").val() == "") {
				console.log("empty"); 
				error += "<p>The content field is required. </p>";
			}
			if (error != "") {
				$("#error").html('<div class="alert alert-danger" role="alert"><strong>There were error(s) in your from: </strong>' + error + '</div>'); 	
				return false; 
			} else {
				return true; 
			}
			
		});
	</script> 
</body>
</html>



