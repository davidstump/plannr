<?php require_once 'includes/template/header.php'; ?>

    <div id="facebook">
      <?php require_once('includes/facebook_connect.php'); ?>
      <?php if ($user) { ?>
        <script>
               $("body").removeClass("loggedout");
        </script>
      <div id="toggles">
        <button id="toggleBirthdays"><img src="images/birthdays.jpg" alt="birthdays" border="0" /></button>
        <button id="toggleConfirmed"><img src="images/confirmed.jpg" alt="confirmed" border="0" /></button>
        <button id="toggleEvents"><img src="images/events.jpg" alt="events" border="0" /></button>
      </div>
        <script type='text/javascript'>
            <?php include("js/calendar.js"); ?>
        </script>
        
        <div id="friend_selector" style="display: none;">
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
                    /*    
                    foreach($friends['data'] as $friend) {
                            $selected = "";
                            if (isset($_GET['id']) && $_GET['id'] == $friend['id']) {
                                $selected = 'selected=selected';
                            }
                            echo "<option value='" . $friend['id'] . "' $selected>" . $friend['name'] . "</option>";
                        }
                     */
                    ?>
                </select>
            </form>
        </div>
        <div id='calendar'></div>
        <div id="footer"><img src="images/inner-footer.jpg" alt="footer" border="0" /></div>
        <?php include("includes/dialogs.php"); ?>
        <?php } ?>
    </div>


<?php require_once 'includes/template/footer.php'; ?>