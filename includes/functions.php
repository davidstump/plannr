<?php
    
    function getBirthdays($friends) {
        
        $facebook = new Facebook(array(
          'appId'  => '278480032169335',
          'secret' => 'be0d3c92369b79084f97d7025b384049',
        ));

        $access_token = $facebook->getAccessToken();
        
        $birthdays = array();
        foreach($friends['data'] as $friend) {
            $date = $friend['birthday'];
            $date_parts = preg_split("/[\/]+/", $date);
            if (isset($friend['birthday']) && count($date_parts) >= 2) {
                $picture = "https://graph.facebook.com/" . $friend['id'] . "/picture?type=large";
                
                $birthdate = $date_parts[0] . "/" . $date_parts[1] . "/" . date('Y');
                $birthdays[$friend['id']]['name'] = $friend['name'];
                $birthdays[$friend['id']]['birthday'] = $birthdate;
                $birthdays[$friend['id']]['picture'] = $picture;
                $birthdays[$friend['id']]['id'] = $friend['id'];
            }
        }
        
        return $birthdays;
   }

   function getEvents($rawevents) {
       $facebook = new Facebook(array(
          'appId'  => '278480032169335',
          'secret' => 'be0d3c92369b79084f97d7025b384049',
        ));

        $access_token = $facebook->getAccessToken();

       $events = array();
       if (count($rawevents) > 0) {
            foreach ($rawevents['data'] as $event) {
                //event info
                $tempid = rtrim(sprintf("%f", $event['eid']),"0");
                $eventid = str_replace(".", "", $tempid);
                $this_event = $facebook->api("/" . $eventid);
                
                //rsvp info
                $query = "SELECT rsvp_status FROM event_member WHERE eid=$eventid AND uid=me()";
                $fql_url = "https://api.facebook.com/method/fql.query?"
                    . "query=" . urlencode($query)
                    . "&format=json"
                    . "&access_token=" . $access_token;
                $fql_resp = json_decode(file_get_contents($fql_url));
                $rsvp_status = $fql_resp[0]->rsvp_status;
                
                //date info
                $startdate = str_replace("T", "-", $this_event['start_time']);
                list($year, $month, $day, $time) = explode('-', $startdate);
                $start = $month . " " . $day . ", " . $year . " " . $time;
                $date = $month . "/" . $day . "/" . $year . " to ";
                $enddate = str_replace("T", "-", $this_event['end_time']);
                list($year, $month, $day, $time) = explode('-', $enddate);
                $end = $month . " " . $day . ", " . $year . " " . $time;
                $date .= $month . "/" . $day . "/" . $year;
                
                //create array
                $events[$eventid]['id'] = $eventid;
                $events[$eventid]['name'] = $this_event['name'];
                $events[$eventid]['start'] = $start;
                $events[$eventid]['end'] = $end;
                $events[$eventid]['date'] = $date;
                $events[$eventid]['description'] = str_replace("\n", "<br />", $this_event['description']);
                $events[$eventid]['picture'] = $event['pic_big'];
                $events[$eventid]['rsvp'] = $rsvp_status;
            }
        }
        
        return $events;
   }

  function _compareFacebookFriends($a, $b) {
    return strcasecmp($a['name'], $b['name']);
    }

    function sortFacebookFriendsArray(&$array) {
        usort($array, '_compareFacebookFriends');
    }
?>
