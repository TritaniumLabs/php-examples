<?php
	//==========================================================================
	// Tritanium Labs USA LLC
	//
	// PURPOSE:  Upload a file/document from a url or local file
	//           and use the /anchor endpoint to store the document on the  
	//           blockchain cloud and the signature on the blockchain.
	//
	// The /anchor endpoint is used.
	// 
	// API Parameters:
	//     auth_key:  Blockchain address and secret key.
	//     gtin or asset_type:  Type of document.
	//     lot or asset_code:   Identifier of the document.
	//     store:  (Y/[N]) Y will store the document on the cloud. N will
	//            only store the signature.
	//
	//     Do not include:  signature, signature_type, or url parameters when
	//     sending a file.
	//==========================================================================

	//==========================================================================
	// PHP Example 
	// 
	// Prerequisites:
	//		1) Create a key.json file for your secret key.
	//  	2) File must be an available URL that ends in the proper file
	//         extension or a local file.   
	//      3) Works for ONLY ONE file at a time.
	//	
	//==========================================================================	

	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	ini_set('memory_limit',-1);
	ini_set('max_execution_time', 30000); 
	ini_set('display_errors', 1);
	header('Access-Control-Allow-Origin: *'); 
	set_time_limit(0);
	
	//-- The Blockchain address of the location where the asset is located.
	//-- Replace with your blockchain address.
	$blockchain_address="31a151d30363396042c3d1977a5763b18b90cb7f95192b9f06e7824c626862c1";

	//-- Read the auth_key from the key file.
	$key=json_decode(file_get_contents('key.json'),true);
	$secret_key=$key['secret_key'];

	//-- Concatinate the blockchain address and secret key to create the auth key.	

	$auth_key=$blockchain_address . ":" . $secret_key;
	$type="MyDocument";
	$code="00000000";

	//---------------------------------------------------------------
	// Create the Fields that go with the FILE.
	//---------------------------------------------------------------

	$fields=array();
	$fields['auth_key']=$auth_key;
	$fields['asset_type']=$type;
	$fields['asset_code']=$code;
	$fields['store']='Y';


	//--- Create the FILES _POST variable
	
	//-- url example.
	$fn="https://homepages.cae.wisc.edu/~ece533/images/boat.png";
	//-- file example.
	//  $fn="boat.png";


	//---Create an array contain the file name.  1 item only.
	$filenames = array($fn);
	$files = array();
	foreach ($filenames as $f){
		$files['document'] = file_get_contents($f);
	}

	//-- Initialize Curl.
	$curl = curl_init();
	
	//-- Use http_build_query to create the url data.
	$url_data = http_build_query($fields);
	
	//-- Create a unique boundary.
	$boundary = uniqid();


	//-- Build the data.		
	$data = '';
	$eol = "\r\n";
	$delimiter = '-------------' . $boundary;

	//-- Create the fields
	foreach ($fields as $name => $content) {
	$data .= "--" . $delimiter . $eol
			. 'Content-Disposition: form-data; name="' . $name . "\"".$eol.$eol
			. $content . $eol;
	}

	//-- Create the files
	foreach ($files as $name => $content) {
		$data .= "--" . $delimiter . $eol
			. 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $fn . '"' . $eol . 'Content-Transfer-Encoding: binary'.$eol;
		$data .= $eol;
		$data .= $content . $eol;
	}
	$data .= "--" . $delimiter . "--".$eol;
	$post_data=$data;

	//-- Perform the transfer
	$url="https://traceabilityapi.net/anchor";
	
	curl_setopt_array($curl, array(
	CURLOPT_URL => $url,
	CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => $post_data,
	CURLOPT_HTTPHEADER => array("Content-Type: multipart/form-data; boundary=" . $delimiter,"Content-Length: " . strlen($post_data)),));					

    //-- run curl.
	$response = curl_exec($curl);
	
	//-- close curl.
	curl_close($curl);						

	echo $response;

			
?>