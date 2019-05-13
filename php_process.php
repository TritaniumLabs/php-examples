<?php
//==========================================================================
// Tritanium Labs USA LLC
//
// PURPOSE:  Create a item using the /process endpoint. 
//
// The /process endpoint is used to create a new item or add ingredients to
//     an existing endpoint.
// 
// API Parameters:
//     gtin:  Global Trade Identification Number of the item created.
//     lot:   Lot Number of the Item created.
//     data:  Optional json object containing optional data.
//     items:  Json encoded array of gtin/lot records that are the ingredients.
//
//==========================================================================

//==========================================================================
// PHP Example 
// 
// Prerequisites:
//		1) Create a key.json file for your secret key.
//==========================================================================

//-- Sample Post 
//-- The GTIN of the product being created or having inputs added to.
$_POST['gtin']="00001300872567";
$_POST['lot']="AM09982A";

//-- Create 3 Input assets.
//--1
$_POST['input_gtin_1']="00001300872567";
$_POST['input_lot_1']="AM09982A";

//--2
$_POST['input_gtin_2']="00001300871234";
$_POST['input_lot_2']="AM03242A";

//--3
$_POST['input_gtin_3']="00001300812345";
$_POST['input_lot_3']="AZ04482A";

//-- Optional data addeed to 'data'

$_POST['product_name']="Spicey Guacamole - Batch";

//$url = 'https://traceabilityapi2.net/process'  //-- Amazon AWS
//$url = 'https://traceabilityapi3.net/process'  //-- Google Cloud
//$url = 'https://traceabilityapi4.net/process'  //-- IBM
$url = 'https://traceabilityapi.net/process';   //-- Microsoft Azure

$blockchain_address="31a151d30363396042c3d1977a5763b18b90cb7f95192b9f06e7824c626862c1";
$key=json_decode(file_get_contents('key.json'),true);
$secret_key=$key['secret_key'];

$fields=array();

//-- Authentication Key to access API.
$fields['auth_key']=$blockchain_address . ":" . $secret_key;

//-- GTIN and LOT number of the item being created.
$fields['gtin']=$_POST['gtin'];
$fields['lot']=$_POST['lot'];

//-- Create an array of the input GTIN and LOT number.
$items=array();

$item1=array();
$item1['gtin']=$_POST['input_gtin_1'];
$item1['lot']=$_POST['input_lot_1'];
array_push($items,$item1);

$item2=array();
$item2['gtin']=$_POST['input_gtin_2'];
$item2['lot']=$_POST['input_lot_2'];
array_push($items,$item2);

$item3=array();
$item3['gtin']=$_POST['input_gtin_3'];
$item3['lot']=$_POST['input_lot_3'];
array_push($items,$item3);

//-- Create the url encoded and json encode item array.
$fields['items']=urlencode(json_encode($items));

//-- Optional data releted to the product created.  Each element of the array is optional.
$product_data=array();
$product_data['product_name']=$_POST['product_name'];
$product_data['qty']="300 lbs";
$fields['data']=urlencode(json_encode($product_data));

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

$result = curl_exec($ch);
curl_close($ch);

echo $result;

//----------------------
// Return Values 
//----------------------

// $result_array=json_decode($result)
// $result_array['code'] - Error Code (should be 0)
// $result_array['message'] - Error Message (should by Tranaction Complete)
// $result_array['transactionHash'] - Block ID
// $result_array['blockHash'] - sha-256 of the block asset data.
// $result_array['addresses'] - Array of blockchain addresses that participate in the transaction.
// $result_array['assets'] - Number of assets affected by the transaction.


?>