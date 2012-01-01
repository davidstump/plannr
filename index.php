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
                                            if (!$i == $birthdaynum - 1) {
                                               echo ",";
                                            }
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
        <div id='calendar'></div>
    </div>


<?php require_once 'includes/footer.php'; ?>