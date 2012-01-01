<?php session_start(); ?>
<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title>Plannr v0.1</title>
  </head>
  <body>
    <div id="facebook">
      <h1>Plannr v0.1</h1>
      <p><?php require_once('includes/facebook_connect.php'); ?></p>
    </div>
    <!--
	<div id="twitter">
      <h1>TWITTER</h1>
      <?php require_once('includes/twitter_connect.php'); ?>
      <a href="includes/twitter_connect.php?login"><img src="images/twitter-signin.png" alt="Sign In Via Twitter" border="0" width="200" /></a>
    </div>
    <div id="foursquare">
      <h1>FOURSQUARE</h1>
      <p><?php require_once('includes/foursquare_connect.php'); ?></p>
    </div>
    <div id="linkedin">
      <h1>LINKEDIN</h1>
      <?php include_once('includes/linkedin_connect.php'); ?>
    </div>
	-->
  </body>
</html>