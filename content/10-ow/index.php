<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	session_start();
	$error = "";
	$posts = ""; 
	include("connection.php");
	$query = "SELECT content FROM posts"; 
	$result = mysqli_query($link, $query); 

	while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
    	$posts .= $row[0]; 
	}
	print(mysqli_error($link));
	if (array_key_exists("logout", $_GET)) {
		unset($_SESSION);
		setcookie("id", "", time() - 60*60);
		$_COOKIE["id"] = "";
		session_destroy(); 
	}
	else if ((array_key_exists("id", $_SESSION) AND $_SESSION["id"]) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE["id"])) {
		header("Location: loggedinpage.php"); 
	}
	if (array_key_exists("submit", $_POST)) {
		 
		// ----------- FORM VALIDATION
		
		if (!$_POST['email']) {
			$error .= "An email address is required<br>"; 
		}
		if (!$_POST['password']) {
			$error .= "A password is required <br>"; 
		}
		
		if ($error) {
			$error = "<p>There were error(s) in your form: </p>".$error; 
		} else {
			if ($_POST['signUp'] == '1') {
				if (!$_POST['name']) {
					$error .= "A name is required <br>"; 
				}
				$query = "SELECT id FROM users WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
				$result = mysqli_query($link, $query); 
				if (mysqli_num_rows($result) > 0) {
					$error = "<p>That email address is already registered</p>"; 
				} else {
					$name = mysqli_real_escape_string($link, $_POST['name']); 
					$query = "INSERT into users (name, email, password) VALUES ('$name', '".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";
					if (!mysqli_query($link, $query)) {
					 	print(mysqli_error($link)); 
						$error = "<p> Could not sign you up - please try again later.</p>";
					} else {
						$query = "UPDATE users SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";
						$id = mysqli_insert_id($link); 
						mysqli_query($link, $query);
						$_SESSION["id"] = $id; 
						if ($_POST["stayLoggedIn"] == '1') {
							setcookie("id", $id, time() + 60*60*24*365);
						}
						header("Location: loggedinpage.php"); 
					}
				}
			} else {
				$query = "SELECT * FROM users WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
				$result = mysqli_query($link, $query); 
				$row = mysqli_fetch_array($result); 
				if (isset($row)) {
					$hashedPassword = md5(md5($row['id']).$_POST['password']);
					if ($hashedPassword == $row['password']) {
						$_SESSION['id'] = $row['id'];
						if (isset($_POST['stayLoggedIn']) AND $_POST['stayLoggedIn'] == '1') {
							setcookie("id", $row['id'], time() + 60*60*24*365); 
						}

						header("Location: loggedinpage.php"); 
					} else {
						$error = "That email/password combination could not be found"; 
					}
				} else {
					$error = "That email/password combination could not be found"; 
				}
			}
		}
	} 

?>
<?php include("header.php"); ?>
	<nav class="navbar navbar-inverse bg-inverse navbar-fixed-top">
		<a class="navbar-brand" href="#">Welcome!</a>
		<div class="pull-xs-right"> 
				
			  <div class="collapse navbar-collapse" id="navbarNav">
			    <ul class="navbar-nav">
			      <li class="nav-item active">
			        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
			      </li>
			      <li class="nav-item">
			        <a class="nav-link" href="#">Features</a>
			      </li>
			      <li class="nav-item">
			        <a class="nav-link" href="#">Pricing</a>
			      </li>
			      <li class="nav-item">
			        <a class="nav-link disabled" href="#">Disabled</a>
			      </li>
			    </ul>
			  </div>
	</div>
</nav>
	<div class="jumbotron">
		<h1 class="display-3 header-text">Scrimmage</h1>
		<p class="lead header-text" >Register your team and find matching opponents</p>
		
		<div id="error"><?php if ($error) {
			echo '<div class="alert alert-danger" role="alert">'.$error.'</div'; 
		}?>
		</div>
		<div class="container">
			<form method="post" id="signUpForm">
				<p>Interested? Register now.</p>
				<fieldset class="form-group">
					<input class="form-control" type="text" name="name" placeholder="Your name">
				</fieldset>
				<fieldset class="form-group">
					<input class="form-control" type="email" name="email" placeholder="Your email">
				</fieldset>
				<fieldset class="form-group">
					<input class="form-control" type="password" name="password" placeholder="password">
				</fieldset>
				<div class="checkbox">
					<input type="checkbox" name="stayLoggedIn" value=1> Stay logged in 
				</div>
				<fieldset class="form-group">
					<input type="hidden" name="signUp" value="1">
					<input class="btn btn-success" type="submit" name="submit" value="Sign Up!">
				</fieldset>
				<p>Already registered? <a class="toggleForms">Log In</a></p>
			</form>
		</div>
		<div class="container">
			<form method="post" id="logInForm">
				<p>Log in using your email and password.</p>
				<fieldset class="form-group">
					<input class="form-control" type="email" name="email" placeholder="Your email">
				</fieldset>
				<fieldset class="form-group">
					<input class="form-control" type="password" name="password" placeholder="password">
				</fieldset>
				<div class="checkbox">
					<input type="checkbox" name="stayLoggedIn" value=1> Stay logged in 
				</div>
				<input type="hidden" name="signUp" value="0">
				<fieldset class="form-group">
					<input class="btn btn-success" type="submit" name="submit" value="Log In!">
				</fieldset>
				<p>Not registered? <a class="toggleForms">Sign Up</a></p>
			</form>
		</div>
	</div>
	<div class="container">
		<div id="posts"> <?php echo $posts ?>
		</div>
	</div>
	
	<!-- jQuery first, then Bootstrap JS. -->
    
</body>
</html>
