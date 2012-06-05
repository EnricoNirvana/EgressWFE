<?
class Map extends SH_Controller {
    function __construct()
    {
            parent::__construct();
            _writelog("INFO","Map Controller Loaded");

    }

    function webmap() {
    	_writelog("INFO","Entering Map/webmap");
    	$CI = get_session();
    	$login_data = $CI->getLoginData();
    	
    	_writelog("DEBUG",(isLoggedIn() == TRUE ? "isLoggedIn() == TRUE" : "isLoggedIn() == FALSE"));
		_writelog("DEBUG","UUID: " . $login_data['UUID']);
		
    	$mappath = "/webmap"; 
    	$CI->load->view('webmap',array('mappath' => $mappath,'login_data' => $login_data));
    }
}
?>
