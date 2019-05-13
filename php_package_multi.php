<?php
//==========================================================================
// Tritanium Labs USA LLC
//
// PURPOSE:  Package multiple items using the /batch endpoint. 
//
// The /batch endpoint is used to ship, receive, or package multiple lines.
// 
// API Parameters:
//     endpoint:  ship, receive, or package.
//     address:  The physical address or blockchain address of the vendor.
//						Blockchain address can be found using /lookupAddress 
//                      or physical address used each time.
//     gtin:  Global Trade Identification Number.
//     lot:   Lot Number
//     data:  Optional json object containing optional data.
//     shipment_id:  Optional shipment number, GSIN or PRO number.
//     shipment_data:  Optional data about the shipment. 
//
//==========================================================================

//==========================================================================
// PHP Example 
// 
// Prerequisites:
//		1) Create a key.json file for your secret key.
//==========================================================================

//-- Sample Post 

$fields=array();
$fields['endpoint']="package";

//-- The item/batch being packaged.

$fields['input_gtin']="00000000000001";
$fields['input_lot']="A000001";

//-- The packages 

$records=array();

//-- Item 1
$item1=array();
$item1['gtin']="00000000000011";
$item1['lot']="A000002";
$item1['data']='{"item_name":"8oz"}';

array_push($records,$item1);

//-- Item 2
$item2=array();
$item2['gtin']="000000000000012";
$item2['lot']="A000002";
$item2['data']='{"item_name":"16oz"}';

array_push($records,$item2);

//-- Item 3
$item3=array();
$item3['gtin']="00000000000013";
$item3['lot']="A000003";
$item3['data']='{"item_name":"3lb"}';

array_push($records,$item3);

$fields['records']=urlencode(json_encode($records));

$url = 'https://traceabilityapi.net/batch.php';   //-- Microsoft Azure

//-- The Blockchain address of the location where the asset will be received
//-- Replace with your blockchain address.
$blockchain_address="31a151d30363396042c3d1977a5763b18b90cb7f95192b9f06e7824c626862c1";

//-- Read the auth_key from the key file.
$key=json_decode(file_get_contents('key.json'),true);
$secret_key=$key['secret_key'];

//-- Concatinate the blockchain address and secret key to create the auth key.
$fields['auth_key']=$blockchain_address . ":" . $secret_key;

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

//close connection
curl_close($ch);

echo $result;


?>