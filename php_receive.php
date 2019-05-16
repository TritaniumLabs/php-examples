<?php
//==========================================================================
// Tritanium Labs USA LLC
//
// PURPOSE:  Receive an item from a vendor using the /receive endpoint. 
//
// The /receive endpoint is used to receive a single item.
// 
// API Parameters:
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
$_POST['address']="5634 W Oklahoma Ave, Milwaukee, WI 53219";
$_POST['gtin']="00001300872071";
$_POST['lot']="AM09982A";
$_POST['vendor_name']="ACME Wigits";			//-- Added to 'data'
$_POST['po_number']="11244321";					//-- Added to 'data'
$_POST['shipment_id']="1Z9999999999999999";		
$_POST['transport_company']="ACME Shipping";	//-- Added to 'shipment_data'


$url = 'https://traceabilityapi.net/receive';   //-- Microsoft Azure
$url = 'https://traceabilityapi.com/receive/';   //-- Microsoft Azure Fast API

//-- The Blockchain address of the location where the asset is located.
//-- Replace with your blockchain address.
$blockchain_address="31a151d30363396042c3d1977a5763b18b90cb7f95192b9f06e7824c626862c1";

//-- Read the auth_key from the key file.
$key=json_decode(file_get_contents('key.json'),true);
$secret_key=$key['secret_key'];

$fields=array();

//-- Concatinate the blockchain address and secret key to create the auth key.
$fields['auth_key']=$blockchain_address . ":" . $secret_key;

//-- Physical address or blockchain address of the shipper.
$fields['address']=urlencode($_POST['address']);

//-- GTIN and LOT number of the item received.
$fields['gtin']=$_POST['gtin'];
$fields['lot']=$_POST['lot'];

//-- Optional data releted to the order.  Each element of the array is optional.
$item_data=array();
$item_data['vendor_name']=$_POST['vendor_name'];
$item_data['po_number']=$_POST['po_number'];

$fields['data']=urlencode(json_encode($item_data));

//-- Shipper GSIN or PRO Number
$fields['shipment_id']=$_POST['shipment_id'];

//-- Optional data releted to the shiopment.  Each element of the array is optional.
$shipment_data=array();
$shipment_data['transport_company']=$_POST['transport_company'];

$fields['shipment_data']=urlencode(json_encode($shipment_data));

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