<?php
//==========================================================================
// Tritanium Labs USA LLC
//
// PURPOSE:  Read dat or a document from the blockchain using the /get endpoint.
//
// The PHP API endpoint is used to return a single asset from the blockchain.
// 
// API Parameters:
//     gtin:  Global Trade Identification Number.
//     lot:   Lot Number
//     file:  (Y/[N]) For data/documents, return the file (Y) or the
//            asset data (N).
//
//==========================================================================

//==========================================================================
// PHP Example 
//
// Read a Document from the Blockchain. 
// 
// Prerequisites:
//		1) Create a key.json file for your secret key.
//      2) Load an document asset using /anchor.
//==========================================================================

//-- Sample Post 
$_POST['gtin']="IMAGE";
$_POST['lot']="9982A1234";
$_POST['show']='Y';
$_POST['file']='Y';


$url = 'https://traceabilityapi.com/get/';   //-- Microsoft Azure

//-- The Blockchain address of the location where the asset is located.
//-- Replace with your blockchain address.
$blockchain_address="31a151d30363396042c3d1977a5763b18b90cb7f95192b9f06e7824c626862c1";

//-- Read the auth_key from the key file.
$key=json_decode(file_get_contents('key.json'),true);
$secret_key=$key['secret_key'];

$fields=array();

//-- Concatinate the blockchain address and secret key to create the auth key.
$fields['auth_key']=$blockchain_address . ":" . $secret_key;

$fields['gtin']=$_POST['gtin'];
$fields['lot']=$_POST['lot'];
$fields['file']=$_POST['file'];

//-- url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

//-- Initialize curl and set options
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//-- Execute curl.
$result = curl_exec($ch);

//--close connection
curl_close($ch);

//------
// The data from the file will be returned as a stream.
// Put the contents in a file.
//------
file_put_contents("MyTest2.png",$result);

?>
<img src="MyTest2.png">