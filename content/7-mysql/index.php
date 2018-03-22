<?php

	if (array_key_exists('email', $_POST) OR array_key_exists('password', $_POST)) {
		$link = mysqli_connect("shareddb1e.hosting.stackcp.net", "userss-3232dd24", "zwtyLC1Yl3Wk", "userss-3232dd24");

		if (mysqli_connect_error()) {
			die ("database connection failed");
		} 

		$email = $_POST['email'];
		$password = $_POST['password'];
		if (!$email) {
			echo "<p>a valid email is required. </p>";
			//return false; 
		}
		if (!$password) {
			echo "<p>a valid password is required. </p>"; 
			//return false; 
		}

		$query = "SELECT id FROM users WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
		$result = mysqli_query($link, $query); 

		if (mysqli_num_rows($result) > 0) {
			echo "already registered user";
			//return false; 
		}
		$query = "INSERT INTO users (email, password) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')"; 
		if (mysqli_query($link, $query)) {
			$_SESSION['email'] = $_POST['email']; 
			header("Location: session.php"); 
		}  else {
			echo "<p> There was a problem. </p>";
		}
	}
	

?>

<form method="POST">
	<input type="text" name="email" id="email" placeholder="email">
	<input type="text" name="password" id="password" placeholder="password">
	<button type="submit" >Submit</button>
</form>
