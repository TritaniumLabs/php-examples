<?php
//==========================================================================
// Tritanium Labs USA LLC
//
// PURPOSE:  Create a new blockchain address for your account.
//
// The /createAddress endpoint is used to create a new address for an account.
// 
// API Parameters:
//     address:  The physical address or blockchain address.

//==========================================================================

//==========================================================================
// PHP Example 
// 
// Prerequisites:
//		1) Create a key.json file for your secret key.
//==========================================================================

//-- Sample Post 
$_POST['address']="1600 E Golf Rd Rolling Meadows IL 60008";

//-- The Blockchain address of your primary location.
//-- Replace with your blockchain address created with your account.
$blockchain_address="31a151d30363396042c3d1977a5763b18b90cb7f95192b9f06e7824c626862c1";

//-- Read the auth_key from the key file.	
$key=json_decode(file_get_contents('key.json'),true);
$secret_key=$key['secret_key'];

$url = 'https://traceabilityapi.net/createAddress';   //-- Microsoft Azure

$fields['address']=urlencode($_POST['address']);

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

$result = curl_exec($ch);

curl_close($ch);

echo $result;

?>