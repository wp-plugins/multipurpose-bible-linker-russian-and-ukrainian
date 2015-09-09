<?php
ini_set('display_errors', false);
set_exception_handler('ReturnError');
$line = '';
$url = (isset($_GET['url']) ? $_GET['url'] : null);

if ($url){
	// fetch XML
	$optional_headers = null;
	$params = array('http' => array(
					'method' => 'GET'));
	if ($optional_headers !== null) {
		$params['http']['header'] = $optional_headers;
	} 
	$ctx = stream_context_create($params);

	$handle = @fopen($url, 'rb', false, $ctx);
	while (!feof($handle)){
		$line .= fgets($handle, 4096);
	}
	fclose($handle);
}

if ($line) {
	// XML to JSON
	echo json_encode(new SimpleXMLElement($line));
} else {
	// nothing returned?
	ReturnError();
}

// return JSON error flag
function ReturnError() {
	//echo '{"error":true}';
}