<?php 
	require_once("class.foursquare.php");

	// This file is intended to be used as your redirect_uri for the client on Foursquare

	// Set your client key and secret
	$client_key = "ZOANA00GV53FOC50EQVUSXALFGV1OLVM4EJYAJ3RH4PWMFVC";
	$client_secret = "LH0BUR2K1I1JJYWF2TXWQDWXTJEVVLE1CNI2JKKAJ0RY21ST";
	$redirect_uri = "http://plannrapp.com";

	// Load the Foursquare API library
	$foursquare = new FoursquareAPI($client_key,$client_secret);

	// If the link has been clicked, and we have a supplied code, use it to request a token
	if(array_key_exists("code",$_GET)){
		$token = $foursquare->GetToken($_GET['code'],$redirect_uri);
	}

?>
<!doctype html>
<html>
<head>
	<title>PHP-Foursquare :: Token Request Example</title>
</head>
<body>
<h1>Token Request Example</h1>
<p>
	<?php 
	// If we have not received a token, display the link for Foursquare webauth
	if(!isset($token)){ 
		echo "<a href='".$foursquare->AuthenticationLink($redirect_uri)."'>Connect to this app via Foursquare</a>";
	// Otherwise display the token
	}else{
		echo "Your auth token: $token";
	}

	?>
	
</p>
<hr />
<?php 

?>
</body>
</html>