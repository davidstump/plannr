<?php
    
    function getBirthdays($friends) {
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
        
        return $birthdays;
   }

   function getEvents($rawevents) {
       $events = array();
       if (count($rawevents) > 0) {
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
        
        return $events;
   }

  function _compareFacebookFriends($a, $b) {
    return strcasecmp($a['name'], $b['name']);
    }

    function sortFacebookFriendsArray(&$array) {
        usort($array, '_compareFacebookFriends');
    }
?>
