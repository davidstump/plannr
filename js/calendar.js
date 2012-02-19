$(document).ready(function() {

        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        var calendar = $('#calendar').fullCalendar({
                header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                },
                selectable: true,
                selectHelper: true,
                dayClick: function (date, allDay, jsEvent, view) {  
                        $("#dialog").dialog('open');     
                        $("#date-start").val($.fullCalendar.formatDate(date, 'MM/dd/yyyy'));
                        $("#date-end").val($.fullCalendar.formatDate(date, 'MM/dd/yyyy'));
                        $("#time-start").val($.fullCalendar.formatDate(date, 'HH:mm'));
                        $("#time-end").val($.fullCalendar.formatDate(date, 'HH:mm')); 
                }, 
                editable: true,
                events: [
                        <?php
                            $i = 0;
                            $birthdaynum = count($birthdays);
                            foreach ($birthdays as $birthday) {
                                echo "{";
                                echo "id: '" . $birthday['id'] . "',";
                                echo "title: '" . addslashes($birthday['name']) . "\'s Birthday',";
                                echo "start: new Date('" . $birthday['birthday'] . "'),";
                                echo "type: 'birthday',";
                                echo "picture: '" . $birthday['picture'] . "',";
                                echo "birthday: '" . $birthday['birthday'] . "'";
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
                                echo "id: '" . $event['id'] . "',";
                                echo "title: '" . addslashes($event['name']) . "',";
                                echo "start: new Date('" . $event['start'] . "'),";
                                echo "end: new Date('" . $event['end'] . "'),";
                                echo $event['rsvp_status'];
                                if ($event['rsvp'] == "attending") {
                                    echo "color: 'green',";
                                } else if ($event['rsvp'] == "declined") {
                                    echo "color: 'grey',";
                                    echo "textColor: 'black',";
                                } else {
                                    echo "color: 'yellow',";
                                    echo "textColor: 'black',";
                                }
                                echo "allDay: false,";
                                echo "type: 'event',";
                                echo "picture: '" . $event['picture'] . "',";
                                echo "eventDate: '" . $event['date'] . "',";
                                echo "description: '" . addslashes($event['description']) . "'";
                                echo "}";
                                if (!$i == $eventnum - 1) {
                                   echo ",";
                                }
                            }
                        ?>
                ],
            eventClick: function(calEvent, jsEvent, view) {
                if (calEvent.type === "birthday") {
                    $("#birthday-dialog #birthday-date").html(calEvent.birthday);
                    $("#birthday-dialog #birthday-link a").attr('href', "http://facebook.com/" + calEvent.id);
                    $("#birthday-dialog #birthday-picture").html("<img src='" + calEvent.picture + "' width='100' border='0' />");
                    $("#birthday-dialog").dialog({
                        title: calEvent.title,
                        height: 250,
                        width: 400,
                        modal: true
                    });
                } else {
                    $("#event-dialog #event-date").html(calEvent.eventDate);
                    $("#event-dialog #event-description").html(calEvent.description);
                    $("#event-dialog #event-link a").attr('href', "http://facebook.com/events/" + calEvent.id);
                    $("#event-dialog #event-picture").html("<img src='" + calEvent.picture + "' border='0' width='100' />");
                    $("#event-dialog").dialog({
                        title: calEvent.title,
                        height: 450,
                        width: 700,
                        modal: true
                    });
                }
            }
        });

        $("#dialog").dialog({
            autoOpen: false,
            height: 450,
            width: 700,
            modal: true,
            buttons: {
                'Create event': function () {
                    calendar.fullCalendar('renderEvent',
                        {
                                title:  $("#name").val(),
                                start:  new Date($("#date-start").val() + " " + $("#time-start").val()),
                                end:  new Date($("#date-end").val() + " " + $("#time-end").val())
                        },
                        true // make the event "stick"
                    );
                    calendar.fullCalendar('unselect');

                   //save event to facebook via ajax
                    $.ajax({
                       type: 'POST',
                       url: 'https://graph.facebook.com/me/events?access_token=<?php echo $access_token ?>',
                       data: 'name=' + $("#name").val() + '&start_time=' + $("#date-start").val() + " " + $("#time-start").val() + '&end_time=' + $("#date-end").val() + " " + $("#time-end").val() + '&description=' + $("#description").val(),
                       success: function(msg) {
                           //all done. woot.
                       }
                   });
                   $(this).dialog('close');
                },
                Cancel: function () {
                    $(this).dialog('close');
                }
            },

            close: function () {
            }

    });   

});