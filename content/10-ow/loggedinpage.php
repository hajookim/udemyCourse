<?php

    session_start();
    $user = ""; 
    if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) {
        
        $_SESSION['id'] = $_COOKIE['id'];
    }

    if (array_key_exists("id", $_SESSION)) {
              
      include("connection.php");
      
      $query = "SELECT name FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
      $row = mysqli_fetch_array(mysqli_query($link, $query));
 
      $user = $row['name'];
      
    } else {
        
        header("Location: index.php");
        
    }

	include("header.php");

?>

<nav class="navbar navbar-inverse bg-inverse navbar-fixed-top">
  
  <div class="pull-xs-right">
      <a class="navbar-brand pull-" href="#"><?php echo "Welcome ".$user."!"?></a>
      <a href ='index.php?logout=1'>
        <button class="btn btn-primary-outline" type="submit">Logout</button></a>

          <button class="btn btn-primary-outline" id="viewPosts" type="submit">My Posts</button>
        
  </div>
</nav>

<div class="jumbotron">
  <h1 class="display-3 header-text">Scrimmage</h1>
  <p class="lead header-text" >Register your team and find matching opponents</p>
</div>

<div class = "container">
  <div id="myPosts">
  </div>
</div>

<?php
    include("footer.php");
?>