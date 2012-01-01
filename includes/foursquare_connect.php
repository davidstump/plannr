<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . '/library/foursquare/class.foursquare.php');
    $name = array_key_exists("name",$_GET) ? $_GET['name'] : "David Stump";
?>
<?php 
	// Set your client key and secret
	$client_key = "ZOANA00GV53FOC50EQVUSXALFGV1OLVM4EJYAJ3RH4PWMFVC";
	$client_secret = "LH0BUR2K1I1JJYWF2TXWQDWXTJEVVLE1CNI2JKKAJ0RY21ST";
        $redirect_uri = 'http://plannrapp.com';
	// Set your auth token, loaded using the workflow described in tokenrequest.php
	$auth_token = "<your auth token>";
	// Load the Foursquare API library
	$foursquare = new FoursquareAPI($client_key,$client_secret);
        if(array_key_exists("code",$_GET)){
		$token = $foursquare->GetToken($_GET['code'],$redirect_uri);
	}
	$foursquare->SetAccessToken($token);

	// Prepare parameters
	$params = array("name"=>$name);

	// Perform a request to a authenticated-only resource
	$response = $foursquare->GetPrivate("users/search",$params);
	$users = json_decode($response);

	// NOTE:
	// Foursquare only allows for 500 api requests/hr for a given client (meaning the below code would be
	// a very inefficient use of your api calls on a production application). It would be a better idea in
	// this scenario to have a caching layer for user details and only request the details of users that
	// you have not yet seen. Alternatively, several client keys could be tried in a round-robin pattern 
	// to increase your allowed requests.

?>
         <?php 
          // If we have not received a token, display the link for Foursquare webauth
          if(!isset($token)){ 
	    echo "<a href='".$foursquare->AuthenticationLink($redirect_uri)."'>Connect to this app via Foursquare</a>";
          // Otherwise display the token
          }else{
	    echo "Your auth token: $token";
          }
	?>
        <p>Search for users by name...</p>
	<form action="" method="GET">
		<input type="text" name="name" />
		<input type="submit" value="Search!" />
	</form>
      <p>Searching for users with name similar to <?php echo $name; ?></p>
      <hr />
	<ul>
          <?php $i = 0; ?>
            <?php if ($users->meta->code != 400) { ?>
		<?php foreach($users->response->results as $user): ?>
                <?php if ($i < 10) { ?>
			<li style="list-style-type: none;">
                          <img src="<?php if(property_exists($user,"photo")) echo $user->photo; ?>" alt="avatar" width="50" />
				<?php 
					if(property_exists($user,"firstName")) echo $user->firstName . " ";
					if(property_exists($user,"lastName")) echo $user->lastName;

					// Grab user twitter details
					$request = $foursquare->GetPrivate("users/{$user->id}");
					$details = json_decode($request);
					$u = $details->response->user;
					if(property_exists($u->contact,"twitter")){
						echo " -- follow this user <a href=\"http://www.twitter.com/{$u->contact->twitter}\">@{$u->contact->twitter}</a>";
					}

				?>
			
			</li>
                      <?php }
                      $i++; ?>
		<?php endforeach; ?>
            <?php } ?>
	</ul>