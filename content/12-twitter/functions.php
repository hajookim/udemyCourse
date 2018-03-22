<?php
	session_start(); 
	$link = mysqli_connect("shareddb-f.hosting.stackcp.net", "twitter-323691c1", "password1234", "twitter-323691c1"); 
	if (mysqli_connect_errno()) {
		print_R(mysqli_connect_error()); 
		exit();
	}
	if ($_GET['function'] == "logout") {
		session_unset(); 
	}

	/*
		time function from google 
	*/
	function time_since($since) {
	    $chunks = array(
	        array(60 * 60 * 24 * 365 , 'year'),
	        array(60 * 60 * 24 * 30 , 'month'),
	        array(60 * 60 * 24 * 7, 'week'),
	        array(60 * 60 * 24 , 'day'),
	        array(60 * 60 , 'hour'),
	        array(60 , 'min'),
	        array(1 , 'sec')
	    );

	    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
	        $seconds = $chunks[$i][0];
	        $name = $chunks[$i][1];
	        if (($count = floor($since / $seconds)) != 0) {
	            break;
	        }
	    }

	    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
	    return $print;
	}

	function displayTweets($type) {
		global $link; 
		if ($type == 'public') {
			$whereClause = " WHERE anonymous = 0"; 
		} else if ($type == 'isFollowing') {
			$query = "SELECT * FROM isFollowing WHERE follower = ".mysqli_real_escape_string($link, $_SESSION['id']);
			$result = mysqli_query($link, $query);
			$whereClause = "";
			while ($row = mysqli_fetch_assoc($result)) {
				if ($whereClause == "") {
					$whereClause = "WHERE";
				}
				else {
					$whereClause .= " OR"; 
				}
				$whereClause .= " userid = ".$row['isFollowing'];
			} 
			$whereClause .= " AND anonymous = 0"; 
			if (mysqli_num_rows($result) == 0) {
				$whereClause = "WHERE userid = ".$_SESSION['id']; 
			} else {
				$whereClause .= " OR userid = ".$_SESSION['id']; 
			}
		} else if ($type == 'yourtweets') {
			$whereClause = "WHERE userid = ". mysqli_real_escape_string($link, $_SESSION['id']); 
		} else if ($type == 'search') {
			echo "<p> Showing results for '<strong>" .mysqli_real_escape_string($link, $_GET['q'])."</strong>': </p>"; 
			$whereClause = "WHERE tweet LIKE '%". mysqli_real_escape_string($link, $_GET['q'])."%'";
		} else if (is_numeric($type)) {
			$userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $type)." LIMIT 1"; 
			$userQueryResult = mysqli_query($link, $userQuery); 
			$user = mysqli_fetch_assoc($userQueryResult); 
			echo "<h2>".mysqli_real_escape_string($link, $user['username'])."'s Tweets</h2>"; 
			$whereClause = "WHERE userid = ". mysqli_real_escape_string($link, $type); 
			$whereClause .= " AND anonymous = 0"; 
		}
		
		$query = "SELECT * FROM tweets ".$whereClause." ORDER BY datetime DESC LIMIT 10"; 
		
		$result = mysqli_query($link, $query); 
		if (mysqli_num_rows($result) == 0) {
			echo "There are no tweets to display";
			if ($type == 'yourtweets' && !$_SESSION['id']) {
				echo "Please login first to see your tweets!"; 
			}
		} else {
			while ($row = mysqli_fetch_assoc($result)) {
				$userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $row['userid'])." LIMIT 1"; 
				$userQueryResult = mysqli_query($link, $userQuery); 
				$user = mysqli_fetch_assoc($userQueryResult); 
				
				echo "<div class='tweet'> <p><a href='?page=publicprofiles&userid=".$user['id']."'>".$user['username']." </a><span class='time'>".time_since(time() - strtotime($row['datetime']))." ago</span>: </p>"; 
				echo "<p>".$row['tweet']."</p>";
				if ($type != 'yourtweets' && $row['userid'] != $_SESSION['id'] && $_SESSION['id']) {
					echo "<p><a href='#' class='toggleFollow' data-userId='".$row['userid']."'>";
					$isFollowingQuery = "SELECT * FROM isFollowing WHERE follower = ".mysqli_real_escape_string($link, $_SESSION['id'])." AND isFollowing = ". mysqli_real_escape_string($link, $row['userid'])." LIMIT 1";
					$isFollowingQueryResult = mysqli_query($link, $isFollowingQuery);
					if (mysqli_num_rows($isFollowingQueryResult) > 0) {
						echo "Unfollow"; 
					} else {
						echo "Follow"; 
					}
				}
				else if ($type == 'yourtweets') {
					echo "<div class='deleteTweetDiv'><a href='#' class='deleteTweet' id='deleteTweet' data-tweetId='".$row['id']."'>Delete Line</div>"; 
				}
				echo "</a></p> </div>";  
			}
		}
	}

	function displaySearch() {
		echo '<form class="form-inline">
  <div class="form-group">
  	<input type="hidden" name="page" value="search"> 
    <input type="text" name="q" class="form-control" id="search" value="Search">
  </div>
  <button type="submit" class="btn btn-primary">Search Tweets</button>
</form>';
	}

	function displayTweetBox() {
		if ($_SESSION['id'] > 0) {
			echo '<div id="tweetSuccess" class="alert alert-success">Your tweet was posted successfully! </div> 
			<div id="tweetFail" class="alert alert-danger"></div> <div class="form">
  <div class="form-group">
    <textarea class="form-control" id="tweetContent" style="height: 100px;" placeholder="Enter your thoughts in less than 140 characters!"></textarea>
  </div>
  <button id="postTweetButton" class="btn btn-primary">Post Tweet</button>
<label class="custom-control custom-checkbox">
  <input type="checkbox" id="postAno" class="custom-control-input">
  <span class="custom-control-indicator"></span>
  <span class="custom-control-description">Post Anonymously</span>
</label>
</div>'; 
		}
	}

	function displaySignUp() {
		if (!$_SESSION['id']) {
			echo '<div class="signupMsg-container" id="signupMsg">
  			<img src="http://jadekim-com.stackstaging.com/content/12-twitter/images/signup.jpg" style="width: 400px; height: 300px;">
  			<div class="centered">Share Your Thoughts with the World and Know What Others Are Thinking! <br> Sign up Now!</div>
  		</div>'; 
		}
	}
	function displayUsers() {
		global $link; 
		$query = "SELECT * FROM users LIMIT 10"; 
		$result = mysqli_query($link, $query); 
		while ($row = mysqli_fetch_assoc($result)) {
			echo "<p><a href='?page=publicprofiles&userid=".$row['id']."'>".$row['username']."</a></p>";
		}
	}
?>
