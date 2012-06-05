<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Splash extends SH_Controller {

    function __construct()
    {
		parent::__construct();
		_writelog("INFO","splash Controller Loaded"); 
    }

	function index()
	{
		_writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		
		_writelog("DEBUG","UUID: " . ($login_data['UUID'] == '' ? 'Null' : $login_data['UUID']));
		
		if(isLoggedIn() == TRUE):
		    $callfwd = 'terminal';	// not a form, so no callback structure -- all paths lead out by way of URL in the embedded anchors
		    $context = array('callback' => $callfwd,'login_data' => $login_data);
		    $CI->load->view('functionality',$context);
		    _writelog("DEBUG","isLoggedIn() == TRUE");
		else:
			$login_data = array(
				'session_status' => FALSE,
				'firstname' => '',
				'lastname' => '',
				'userlevel' => '',
				'passwordhash' => '',
				'salt' => '',
				'UUID' => '',
				'IP' => $CI->session->userdata('ip_address')
			);
			$CI->setLoginData($login_data);
			
			if($CI->options['feeds_enabled'] == TRUE):
				// load the feed model
				$CI->load->model('feed');

				// load all readable posts
				$feeds = $this->feed->getAllReadablePosts($login_data['userlevel']);	
			endif;

			_writelog("DEBUG","isLoggedIn() == FALSE");

			if(($CI->options['feeds_enabled'] == TRUE) && ($CI->options['acct_moderation'] == TRUE)):
				$CI->load->view('splash_message_mod',array('feeds' => $feeds, 'login_data' => $CI->login_data));
			else:
				$CI->load->view('splash_message_open',array('login_data' => $CI->login_data));
			endif;
		endif;
	}
}
/* End of file splash.php */
/* Location: ./application/controllers/splash.php */
?>
