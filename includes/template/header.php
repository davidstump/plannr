<?php session_start(); ?>
<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title>Plannr v0.1</title>
    
    <link rel='stylesheet' type='text/css' href='css/main.css' />
    
    <!-- Start Calendar -->
    <link rel='stylesheet' type='text/css' href='js/fullcalendar/fullcalendar.print.css' media='print' />
    <link rel='stylesheet' type='text/css' href='js/fullcalendar/fullcalendar.css' />
    <script type='text/javascript' src='js/jquery/jquery-1.5.2.min.js'></script>
    <script type='text/javascript' src='js/jquery/jquery-ui-1.8.11.custom.min.js'></script>
    <link rel='stylesheet' type='text/css' href='js/jquery/ui/jquery.ui.all.css' />
    <link rel='stylesheet' type='text/css' href='js/jquery/ui/timepicker.css' />
    <script src="js/jquery/ui/jquery.ui.core.js"></script>
    <script src="js/jquery/ui/jquery.ui.widget.js"></script>
    <script src="js/jquery/ui/jquery.ui.mouse.js"></script>
    <script src="js/jquery/ui/jquery.ui.draggable.js"></script>
    <script src="js/jquery/ui/jquery.ui.position.js"></script>
    <script src="js/jquery/ui/jquery.ui.resizable.js"></script>
    <script src="js/jquery/ui/jquery.ui.dialog.js"></script>
     <script src="js/jquery/ui/timepicker.js"></script>
    <script type='text/javascript' src='js/fullcalendar/fullcalendar.min.js'></script>
    <!-- End Calendar -->
    
    <script type='text/javascript' src='js/main.js'></script>


  </head>
<?php
    require '/home/david/public_html/library/facebook/facebook.php';

    $facebook = new Facebook(array(
      'appId'  => '278480032169335',
      'secret' => 'be0d3c92369b79084f97d7025b384049',
      'fileUpload' => true
    ));

    $access_token = $facebook->getAccessToken();

    // See if there is a user from a cookie
    $user = $facebook->getUser();
    require '/home/david/public_html/includes/functions.php';

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
    if ($user) {
?>
    <body class="loggedin">
  <?php } else { ?>
    <body>
  <?php } ?>

