<?php
require 'library/facebook/facebook.php';

$facebook = new Facebook(array(
  'appId'  => '278480032169335',
  'secret' => 'be0d3c92369b79084f97d7025b384049',
  'fileUpload' => true
));

$access_token = $facebook->getAccessToken();

// See if there is a user from a cookie
$user = $facebook->getUser();
require 'functions.php';

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    //$rawevents = $facebook->api('/me/events');
    $eventfql = "https://graph.facebook.com/fql?q=SELECT+eid,pic_big,name,description,start_time,end_time,venue+FROM+event+WHERE+eid+IN+(+SELECT+eid+FROM+event_member+WHERE+uid=me()+)&access_token=" . $access_token;
    $eventresult = file_get_contents($eventfql);
    $rawevents = json_decode($eventresult, true);
    $friends = $facebook->api('me/friends?fields=id,name,birthday,picture,link,events');
    sortFacebookFriendsArray($friends['data']);
    //echo "<pre style='text-align: left;'>" . print_r($friends, 1) . "</pre>";
    if (isset($_GET['id'])) {
        $user_rawevents = $facebook->api('/' . $_GET['id'] . '/events');
    }
  } catch (FacebookApiException $e) {
    $user = null;
  }
}

?>
<?php if ($user) { ?>
    <?php
        $events = getEvents($rawevents);
        if (isset($_GET['id'])) {
            $events = getEvents($user_rawevents);
        }
        //convert birthday info into usable array and sort out unusable or nonexistant birthdays
        $birthdays = getBirthdays($friends);
        
    ?>
<?php } else { ?>
      <fb:login-button scope="publish_stream,friends_events,user_events, email, friends_birthday, create_event, rsvp_event"></fb:login-button>
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