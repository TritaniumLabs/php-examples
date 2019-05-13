<?php
	
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	ini_set('memory_limit',-1);
	ini_set('max_execution_time', 30000); 
	ini_set('display_errors', 1);
	header('Access-Control-Allow-Origin: *'); 
	set_time_limit(0);
	
	//--------------------------------------------------------------
	// Example to upload a file to the API.
	// Warning:  Upload only one file at a time.
	//--------------------------------------------------------------
	
	$blockchain_address="31a151d30363396042c3d1977a5763b18b90cb7f95192b9f06e7824c626862c1";
	$key=json_decode(file_get_contents('key.json'),true);
	$secret_key=$key['secret_key'];

	$auth_key=$blockchain_address . ":" . $secret_key;
	$type="MyDocument";
	$code="00000000";

	//---------------------------------------------------------------
	// Create the Fields that go with the FILE.
	//---------------------------------------------------------------
	$fields = array("auth_key"=>$auth_key, "asset_type"=>$type, "asset_code"=>$code);
	$fields=array();
	$fields['auth_key']=$auth_key;
	$fields['asset_type']=$type;
	$fields['asset_code']=$code;
	$fields['store']='Y';

	//---------------------------------------------------------------
	// Create the FILES _POST variable
	//---------------------------------------------------------------
	$fn="https://homepages.cae.wisc.edu/~ece533/images/boat.png";
	$filenames = array($fn);
	$files = array();
	foreach ($filenames as $f){
		$files['document'] = file_get_contents($f);
	}


	$curl = curl_init();
	$url_data = http_build_query($fields);
	$boundary = uniqid();

	//---------------------------------------------------------------
	// Build the file and post_data
	//---------------------------------------------------------------
					
	$data = '';
	$eol = "\r\n";
	$delimiter = '-------------' . $boundary;

	//--
	//-- Create the fields
	//--
	foreach ($fields as $name => $content) {
	$data .= "--" . $delimiter . $eol
			. 'Content-Disposition: form-data; name="' . $name . "\"".$eol.$eol
			. $content . $eol;
	}

	//--
	//-- Create the files
	//--
	foreach ($files as $name => $content) {
		$data .= "--" . $delimiter . $eol
			. 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $fn . '"' . $eol . 'Content-Transfer-Encoding: binary'.$eol;
		$data .= $eol;
		$data .= $content . $eol;
	}
	$data .= "--" . $delimiter . "--".$eol;
	$post_data=$data;

	//--
	//-- Perform the transfer
	//--
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
	$response = curl_exec($curl);
	curl_close($curl);						

	echo $response;

			
?>