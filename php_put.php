<?php
//==========================================================================
// Tritanium Labs USA LLC
//
// PURPOSE:  Put an asset on the blockchain or add optional data.  Put 
//           does not change inputs or outputs, only optional meta data.
//
// USES:  Add additional optional data to the blockchain or load an asset from
//        inventory without receiving, processing, or packaging it.
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
// Prerequisites:
//		1) Create a key.json file for your secret key.
//      2) Load an asset using, /receive, /process, /put, or /anchor.
//==========================================================================

//-- Sample Post 
$_POST['gtin']="00001300872567";
$_POST['lot']="AM09982A";

$url = 'https://traceabilityapi.net/put';   //-- Microsoft Azure Cosmos
$url = 'https://traceabilityapi.com/put/';   //-- Microsoft Azure Fast API

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

//-- Create an array containing the data that will be added or changed on the 
//-- asset record.
$new_data=array();
$new_data['item_name']="Spicy Guac";
$new_data['create_date']="2019-01-01";
$new_data['qty_created']="100";

//-- New data must pe posted json encoded and url encoded.
$fields['data']=urlencode(json_encode($new_data));

//-- url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

//-- Initialied curl.
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//-- Execute curl.
$result = curl_exec($ch);

//--close connection
curl_close($ch);

echo $result;

?>