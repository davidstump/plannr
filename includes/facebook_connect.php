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
    $rawevents = $facebook->api('/me/events');
    $friends = $facebook->api('me/friends?fields=id,name,birthday,picture,link');
  } catch (FacebookApiException $e) {
    echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
    $user = null;
  }
}

?>
<?php if ($user) { ?>
    <?php
        if (count($rawevents) > 0) {
            $events = array();
            foreach ($rawevents['data'] as $event) {
                $startdate = str_replace("T", "-", $event['start_time']);
                list($year, $month, $day, $time) = explode('-', $startdate);
                $start = $month . " " . $day . ", " . $year . " " . $time;
                $enddate = str_replace("T", "-", $event['end_time']);
                list($year, $month, $day, $time) = explode('-', $enddate);
                $end = $month . " " . $day . ", " . $year . " " . $time;
                $events[$event['id']]['name'] = $event['name'];
                $events[$event['id']]['start'] = $start;
                $events[$event['id']]['end'] = $end;
            }
        }
        //convert birthday info into usable array and sort out unusable or nonexistant birthdays
        $birthdays = array();
        foreach($friends['data'] as $friend) {
            $date = $friend['birthday'];
            $date_parts = preg_split("/[\/]+/", $date);
            if (isset($friend['birthday']) && count($date_parts) >= 2) {
                $birthdate = $date_parts[0] . "/" . $date_parts[1] . "/" . date('Y');
                $birthdays[$friend['id']]['name'] = $friend['name'];
                $birthdays[$friend['id']]['birthday'] = $birthdate;
                $birthdays[$friend['id']]['picture'] = $friend['picture'];
            }
        }
        
    ?>
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