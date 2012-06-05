<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class reqmod extends CI_Model {
	function __construct(){
		parent::__construct();
        _writelog("INFO","reqmod Model Loaded");
	}

	function get_reqs($num, $offset) {
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
			redirect('splash');
		endif;
			
		$where = "status != 'moderated' ORDER BY id";
		$CI->db->where($where);
		$query = $CI->db->get('accountreqs', $num, $offset);	
		return $query;
	}
}

?>
