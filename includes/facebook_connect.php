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
      <div id="fakeFB"></div>
      <img src="images/plannr.jpg" alt="Plannr" border="0" />
      <div style="display: none;"><fb:login-button scope="publish_stream,friends_events,user_events, email, friends_birthday, create_event, rsvp_event"></fb:login-button></div>
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