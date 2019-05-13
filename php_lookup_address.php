<?php
//==========================================================================
// Tritanium Labs USA LLC
//
// PURPOSE:  Lookup a blockchain address for a physical address.
//
// The /addressLookup endpoint is used to lookup a physical address and 
//    return blockchain address, formatted address, longitude, and latitude.
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

$url = 'https://traceabilityapi.net/addressLookup';   //-- Microsoft Azure

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