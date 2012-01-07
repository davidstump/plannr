<?php require_once 'includes/header.php'; ?>

    <div id="facebook">
      <p><?php require_once('includes/facebook_connect.php'); ?></p>
      <script type='text/javascript'>
            $(document).ready(function() {

                    var date = new Date();
                    var d = date.getDate();
                    var m = date.getMonth();
                    var y = date.getFullYear();

                    $('#calendar').fullCalendar({
                            header: {
                                    left: 'prev,next today',
                                    center: 'title',
                                    right: 'month,agendaWeek,agendaDay'
                            },
                            editable: true,
                            events: [
                                    <?php
                                        $i = 0;
                                        $birthdaynum = count($birthdays);
                                        foreach ($birthdays as $birthday) {
                                            echo "{";
                                            echo "title: '" . addslashes($birthday['name']) . "\'s Birthday',";
                                            echo "start: new Date('" . $birthday['birthday'] . "')";
                                            echo "}";
                                            if ($i < ($birthdaynum - 1)) {
                                               echo ",";
                                            }
                                            $i++;
                                        }
                                        if (count($events) > 0) {
                                            echo ",";
                                        }
                                    ?>
                                    <?php
                                        $i = 0;
                                        $eventnum = count($events);
                                        foreach ($events as $event) {
                                            echo "{";
                                            echo "title: '" . addslashes($event['name']) . "',";
                                            echo "start: new Date('" . $event['start'] . "'),";
                                            echo "end: new Date('" . $event['end'] . "'),";
                                            echo "color: 'green',";
                                            echo "allDay: false";
                                            echo "}";
                                            if (!$i == $eventnum - 1) {
                                               echo ",";
                                            }
                                        }
                                    ?>
                            ]
                    });

            });

        </script>
    
        <div id="friend_selector">
            <div id="friend_image">
                <?php if (isset($_GET['id']) && !($_GET['id'] == 'me')) { ?>
                    <img src="https://graph.facebook.com/<?php echo $_GET['id'] ?>/picture" border="0" alt="Profile Picture" />
                <?php } else { ?>
                    <img src="https://graph.facebook.com/<?php echo $user ?>/picture" border="0" alt="Profile Picture" />
               <?php } ?>
            </div>
            <form action="/" method="GET" name="friendevents" id="friends">
                <label>
                    Check out events for: 
                </label>
                <select name="id" id="friendlist">
                    <option value="me">Me</option>
                    <?php
                        foreach($friends['data'] as $friend) {
                            $selected = "";
                            if (isset($_GET['id']) && $_GET['id'] == $friend['id']) {
                                $selected = 'selected=selected';
                            }
                            echo "<option value='" . $friend['id'] . "' $selected>" . $friend['name'] . "</option>";
                        }
                    ?>
                </select>
            </form>
        </div>
        <div id='calendar'></div>
    </div>


<?php require_once 'includes/footer.php'; ?>