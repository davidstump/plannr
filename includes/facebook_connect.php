<?php

require 'library/facebook/facebook.php';

$facebook = new Facebook(array(
  'appId'  => '278480032169335',
  'secret' => 'be0d3c92369b79084f97d7025b384049',
));

// See if there is a user from a cookie
$user = $facebook->getUser();

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $events = $facebook->api('/me/events');
	$birthdays = $facebook->api('me/friends?fields=id,name,birthday,picture,link');
  } catch (FacebookApiException $e) {
    echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
    $user = null;
  }
}

?>
<?php if ($user) { ?>
      Your user profile is
      <pre>
		<b>Events:</b> <?php echo htmlspecialchars(print_r($events, true)); ?><br /><br />
		<b>Birthdays:</b> <?php echo htmlspecialchars(print_r($birthdays, true)); ?>
      </pre>
    <?php } else { ?>
      <fb:login-button scope="user_events, email, friends_birthday"></fb:login-button>
    <?php } ?>
    <div id="fb-root"></div>
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId: '<?php echo $facebook->getAppID() ?>',
          cookie: true,
          xfbml: true,
          oauth: true
        });
        FB.Event.subscribe('auth.login', function(response) {
          window.location.reload();
        });
        FB.Event.subscribe('auth.logout', function(response) {
          window.location.reload();
        });
      };
      (function() {
        var e = document.createElement('script'); e.async = true;
        e.src = document.location.protocol +
          '//connect.facebook.net/en_US/all.js';
        document.getElementById('fb-root').appendChild(e);
      }());
    </script>