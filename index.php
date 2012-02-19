<?php require_once 'includes/template/header.php'; ?>

    <div id="facebook">
      <p><?php require_once('includes/facebook_connect.php'); ?></p>
      <?php if ($user) { ?>
      <script type='text/javascript'>
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
        <div id="dialog" class="event-dialog" title="Event">
            <div id="dialog-inner">
                Event Name: <input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all title"><br><br />
                <span class="inline">From: <input type="text" name="date-start" id="date-start" class="text ui-widget-content ui-corner-all"></span>
                <span class="inline"><input type="text" name="time" id="time-start" class="text ui-widget-content ui-corner-all"></span><br /><br />
                <span class="inline">To: </span> <span class="inline"><input type="text" name="date" id="date-end" class="text ui-widget-content ui-corner-all"></span>
                <span class="inline"><input type="text" name="time" id="time-end" class="text ui-widget-content ui-corner-all"></span>
                <span class="inline">&nbsp;All day <input id="all-day" type="checkbox"></span> <br /><br />
                <label for="description">Description:</label> 
                <textarea name="description" id="description" class="text ui-widget-content ui-corner-all" rows="8" cols="73">       
</textarea>
            </div>
        </div>
        <?php } ?>
    </div>


<?php require_once 'includes/template/footer.php'; ?>