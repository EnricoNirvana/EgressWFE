<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function _uuid() {

        // The field names refer to RFC 4122 section 4.1.2

        return sprintf('%04x%04x-%04x-%03x4-%04x-%04x%04x%04x',
                mt_rand(0, 65535), mt_rand(0, 65535), // 32 bits for "time_low"
                mt_rand(0, 65535), // 16 bits for "time_mid"
                mt_rand(0, 4095),  // 12 bits before the 0100 of (version) 4 for "time_hi_and_version"
                bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '01', 6, 2)),
                                // 8 bits, the last two of which (positions 6 and 7) are 01, for "clk_seq_hi_res"
                                // (hence, the 2nd hex digit after the 3rd hyphen can only be 1, 5, 9 or d)
                                // 8 bits for "clk_seq_low"
                mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535) // 48 bits for "node" 
        );
}

function _salt() {
	sprintf('%s',md5($this->_uuid()));
}

function _confirm($cb) {
    $CI = get_session();
    $cb['login_data'] = $CI->getLoginData();
    
    $CI->load->view('confirm',$cb);
}


function getTexture($uuid)
{
	// fetch a profile image resource given a UUID
	// $uuid = "afa7a98b-2db6-4c18-9e7e-b0e10ca5f599";
	$server = "http://grid.lfgrid.com:8003/";
	$uri = $server . "assets/" . $uuid;
	
	// set up for curl session
	$curlhandle = curl_init();
	
	// set URL and other appropriate options
	curl_setopt($curlhandle, CURLOPT_URL, $uri);
	curl_setopt($curlhandle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curlhandle, CURLOPT_HEADER, 0);
	
	// execute the curl command
	$rawdata = curl_exec($curlhandle);
	
	// close cURL resource, and free up system resources
	curl_close($curlhandle);
	
	// parse out the base64-encoded blob data for the image
	$start = strpos($rawdata,"<Data>")+6;
	$end = strpos($rawdata,"</Data>");
	$length = $end - $start;
	
	$encimg = substr($rawdata,$start,$length);
	
	// parse out image caption
	$start = strpos($rawdata,"<Name>")+6;
	$end = strpos($rawdata,"</Name>");
	$length = $end - $start;
	
	$imgcapt = substr($rawdata,$start,$length);
	
	// decode the image data
	$decodedimg = ""; 
	for ($i=0; $i < ceil(strlen($encimg)/256); $i++) 
	   $decodedimg = $decodedimg . base64_decode(substr($encimg,$i*256,256)); 
	
	// instantiate the image
	$image = new Imagick();
	$image->readImageBlob($decodedimg); 
	
	return($image);
}

function isLoggedIn()
{
	$CI = get_session();
	$login_data = session_get('login_data');

	if ($login_data['session_status'] == ""): // test presence of valid creds in session data
		return(FALSE);
	else:
		return(TRUE);
	endif;
}
?>

