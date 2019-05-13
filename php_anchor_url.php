<?php
	//==========================================================================
	// Tritanium Labs USA LLC
	//
	// PURPOSE:  Store the signature of a document located on a URL 
	//           using the /anchor endpoint.
	//
	//           The url can be copied and stored on the cloud or only
	//           fingerprinted.
	//
	// The /anchor endpoint is used.
	// 
	// API Parameters:
	//     auth_key:  Blockchain address and secret key.
	//     gtin or asset_type:  Type of document.
	//     lot or asset_code:   Identifier of the document.
	//     url: The url of a document. 
	//     store:  (Y/[N]) Y will store the document on the cloud. N will
	//            only store the signature.
	//
	//     Do not include signature or signature_type if uploading document. 
	//==========================================================================

	//-- Sample Post 
	$_POST['asset_type']="IMAGE";
	$_POST['asset_code']="9982A1234";
	$_POST['url']="https://homepages.cae.wisc.edu/~ece533/images/boat.png";
	$_POST['store']="Y";

	$url = 'https://traceabilityapi.net/anchor';   //-- Microsoft Azure

	//-- The Blockchain address of the location where the asset is located.
	//-- Replace with your blockchain address.
	$blockchain_address="31a151d30363396042c3d1977a5763b18b90cb7f95192b9f06e7824c626862c1";
	
	//-- Read the auth_key from the key file.	
	$key=json_decode(file_get_contents('key.json'),true);
	$secret_key=$key['secret_key'];

	$fields=array();

	//-- Concatinate the blockchain address and secret key to create the auth key.
	$fields['auth_key']=$blockchain_address . ":" . $secret_key;

	$fields['asset_type']=$_POST['asset_type'];
	$fields['asset_code']=$_POST['asset_code'];
	$fields['url']=$_POST['url'];

	//-- Optional data stored with the document.
	$meta_data=array();
	$meta_data['title']="Big Boat";
	$meta_data['description']="Black and white description";
	
	//-- Optional asset data must be json encoded and urlencoded.
	$fields['data']=urlencode(json_encode($meta_data));

	//-- Store the actual file on the Traceability Blockchain Cloud
	$fields['store']=$_POST['store'];

	//url-ify the data for the POST
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');

	//Initialize curl	
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