      <?php
          if (isset($_GET['login'])) {
             require("../library/twitter/twitteroauth.php");
            ini_set('display_errors', 'Off');
            ini_set('display_startup_errors', 'Off');
            error_reporting(0);  
            session_start();
          } else {
            require("library/twitter/twitteroauth.php");
          }
        //session_start();

        if(!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])){  
          // We've got everything we need
          // TwitterOAuth instance, with two new parameters we got in twitter_login.php  
          $twitteroauth = new TwitterOAuth('PzSo4gWDeK7SSvmJwecVQ', 'FlAAWCfGjwoLP8oRZagNSGACVY5T5eNXvBq8nbkohyA', $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);  
          // Let's request the access token  
          $access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']); 
          // Save it in a session var 
          $_SESSION['access_token'] = $access_token; 
          // Let's get the user's info 
          $user_info = $twitteroauth->get('account/verify_credentials'); 
          // Print user's info
          echo "<pre>";
          print_r($user_info);
          echo "</pre>";
          mysql_connect('localhost', 'david_plannr', 'plannrapp');  
          mysql_select_db('david_plannr');
          if(isset($user_info->error)){  
              // Something's wrong, go back to square 1  
              //header('Location: twitter_login.php'); 
          } else { 
              // Let's find the user by its ID  
              $query = mysql_query("SELECT * FROM users WHERE oauth_provider = 'twitter' AND oauth_uid = ". $user_info->id);  
              $result = mysql_fetch_array($query);  
            
              // If not, let's add it to the database  
              if(empty($result)){  
                  $query = mysql_query("INSERT INTO users (oauth_provider, oauth_uid, username, oauth_token, oauth_secret) VALUES ('twitter', {$user_info->id}, '{$user_info->screen_name}', '{$access_token['oauth_token']}', '{$access_token['oauth_token_secret']}')");  
                  $query = mysql_query("SELECT * FROM users WHERE id = " . mysql_insert_id());  
                  $result = mysql_fetch_array($query);  
              } else {  
                  // Update the tokens  
                  $query = mysql_query("UPDATE users SET oauth_token = '{$access_token['oauth_token']}', oauth_secret = '{$access_token['oauth_token_secret']}' WHERE oauth_provider = 'twitter' AND oauth_uid = {$user_info->id}");  
              }  
            
              $_SESSION['id'] = $result['id']; 
              $_SESSION['username'] = $result['username']; 
              $_SESSION['oauth_uid'] = $result['oauth_uid']; 
              $_SESSION['oauth_provider'] = $result['oauth_provider']; 
              $_SESSION['oauth_token'] = $result['oauth_token']; 
              $_SESSION['oauth_secret'] = $result['oauth_secret']; 
           
              //header('Location: twitter_update.php');
              ?>
              <h2>Hello <?=(!empty($_SESSION['username']) ? '@' . $_SESSION['username'] : 'Guest'); ?></h2>
              <?php
          } 
        } else {  
          //blah
        }
        
        if (isset($_GET['login'])) {
          // The TwitterOAuth instance  
          $twitteroauth = new TwitterOAuth('PzSo4gWDeK7SSvmJwecVQ', 'FlAAWCfGjwoLP8oRZagNSGACVY5T5eNXvBq8nbkohyA');  
          // Requesting authentication tokens, the parameter is the URL we will be redirected to  
          $request_token = $twitteroauth->getRequestToken('http://plannrapp.com/index.php');  
            
          // Saving them into the session  
          $_SESSION['oauth_token'] = $request_token['oauth_token'];  
          $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];  
          // If everything goes well..  
          if($twitteroauth->http_code==200){  
              // Let's generate the URL and redirect  
              $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
              echo '<META HTTP-EQUIV="Refresh" Content="0; URL=' . $url . '">';
          } else { 
              // It's a bad idea to kill the script, but we've got to know when there's an error.  
              die('Something wrong happened.');  
          } 
        }
      ?>