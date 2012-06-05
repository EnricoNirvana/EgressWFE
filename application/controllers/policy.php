<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Policy extends SH_Controller {
 
	public $data 	= 	array();
	public function __construct() {
 
		parent::__construct();
		
		_writelog("INFO","Policy Controller Loaded");
	}
	
	public function TOSEdit() {
		_writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();

		//Ckeditor's configuration
		$CI->data['ckeditor'] = array(
 
			//ID of the textarea that will be replaced
			'id' 	=> 	'tostext',
			'path'	=>	'assets/js/ckeditor',
 
			//Optional values
			'config' => array(
				'toolbar' 	=> 	"DocToolbar", 	//Using the Full toolbar
				'width' 	=> 	"925px",	//Setting a custom width
				'height' 	=> 	'275px',	//Setting a custom height
 
			)
		);

		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
			_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		else:
			$sql = "SELECT * FROM policies WHERE policytype='TOS' ORDER BY policyver DESC LIMIT 1";
			$query  = $CI->db->query($sql);
			if($query == FALSE):
				$context = array('coarsemsg' => 'SQL Service error.',
                    'finemsg' => 'The select operation failed. Check your tables!!',
                    'callback' => 'splash');
                _writelog("ERROR","SQL Service error during select from policies table in TOS edit workflow");
                _writelog("POLICY","POLICY UPDATE FAILURE");
                _confirm($context);
                return;
            endif;
			if($query->num_rows() == 0):
				$CI->data['tostext'] = array(
					'policyver'  => '0.01',
					'policytype' => 'TOS',
					'policytext' => 'Compose Terms of Service Policy v0.01 in this window'
				);
			else:
				$policy = $query->row_array();
				$sql="SELECT policyver FROM policies ORDER BY policyver DESC LIMIT 1";
				$query  = $CI->db->query($sql);
				if($query == FALSE):
					$context = array('coarsemsg' => 'SQL Service error.',
						'finemsg' => 'The select operation failed. Check your tables!!',
						'callback' => 'splash');
					_writelog("ERROR","SQL Service error during select from policies table in TOS edit workflow");
					_writelog("POLICY","POLICY UPDATE FAILURE");
					_confirm($context);
					return;
				endif;
				$ver = $query->row_array();
				$CI->data['tostext'] = array(
					'policyver' => $ver['policyver']+0.01,
					'policytype' => $policy['policytype'],
					'policytext' => $policy['policytext']
				);
			endif;
			
			$CI->data['login_data'] = $login_data;
			$CI->load->view('TOSEdit', $CI->data);
		endif;    
 	}
 	
 	public function updateTOS()
 	{
		_writelog("INFO","Entering " . __FUNCTION__ );
 		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
			_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function"); 
			redirect('splash');
			return;
		else:
		
			$request_data['policytype'] = $CI->input->post('policytype',TRUE);
			$request_data['policytext'] = $CI->input->post('tostext',TRUE);
			
			$sql = "SELECT COUNT(*)/100 AS policyver FROM policies";
			$query = $CI->db->query($sql);
			if($query == FALSE):
				$context = array('coarsemsg' => 'SQL Service error.',
                    'finemsg' => 'The select operation failed. Check your tables!!',
                    'callback' => 'splash');
                _writelog("ERROR","SQL Service error during select from policies table in TOS edit workflow");
                _writelog("POLICY","POLICY UPDATE FAILURE");
                _confirm($context);
                return;
            endif;
			$policy = $query->row_array();
			$request_data['policyver'] = $policy['policyver'];
						
			$sql = "INSERT INTO policies set policyver=?, policytype=?, policytext=?";
			$sqlargs = array(
				$request_data['policyver'],
				$request_data['policytype'],
				$request_data['policytext']
			);
			if($query = $CI->db->query($sql,$sqlargs) == FALSE):
				$context = array('coarsemsg' => 'update of table policies failed during SQL query execution.',
                    'finemsg' => 'The update operation failed. Check your tables!!',
                    'callback' => 'splash');
                _writelog("ERROR","SQL query: row insertion FAILURE");
                _writelog("POLICY","POLICY UPDATE FAILURE");
                _confirm($context);
                return;
            else:
   				$context = array('coarsemsg' => 'update of table policies succeeded.',
                    'finemsg' => 'The update operation was a success.',
                    'callback' => 'splash');
                _writelog("POLICY","Site Policy Updated successfully");
                _confirm($context);
                return;
            endif;
		endif;    
 	}
 
	public function AUPEdit() {
		_writelog("INFO","Entering " . __FUNCTION__ );
        $CI = get_session();
 
		//Ckeditor's configuration
		$CI->data['ckeditor'] = array(
 
			//ID of the textarea that will be replaced
			'id' 	=> 	'auptext',
			'path'	=>	'assets/js/ckeditor',
 
			//Optional values
			'config' => array(
				'toolbar' 	=> 	"DocToolbar", 	//Using the Full toolbar
				'width' 	=> 	"925px",	//Setting a custom width
				'height' 	=> 	'275px',	//Setting a custom height
 
			)
		);

		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
			_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		else:
			$sql = "SELECT * FROM policies WHERE policytype='AUP' ORDER BY policyver DESC LIMIT 1";
			$query  = $CI->db->query($sql);
			if($query == FALSE):
				$context = array('coarsemsg' => 'SQL Service error.',
                    'finemsg' => 'The select operation failed. Check your tables!!',
                    'callback' => 'splash');
                _writelog("ERROR","SQL Service error during select from policies table in TOS edit workflow");
                _writelog("POLICY","POLICY UPDATE FAILURE");
                _confirm($context);
                return;
            endif;
			if($query->num_rows() == 0):
				$CI->data['auptext'] = array(
					'policyver'  => '0.01',
					'policytype' => 'AUP',
					'policytext' => 'Compose Acceptable Use Policy v0.01 in this window'
				);
			else:
				$policy = $query->row_array();
				$sql="SELECT policyver FROM policies ORDER BY policyver DESC LIMIT 1";
				$query  = $CI->db->query($sql);
			if($query == FALSE):
				$context = array('coarsemsg' => 'SQL Service error.',
                    'finemsg' => 'The select operation failed. Check your tables!!',
                    'callback' => 'splash');
                _writelog("ERROR","SQL Service error during select from policies table in TOS edit workflow");
                _writelog("POLICY","POLICY UPDATE FAILURE");
                _confirm($context);
                return;
            endif;
				$ver = $query->row_array();
				$CI->data['auptext'] = array(
					'policyver' => $ver['policyver']+0.01,
					'policytype' => $policy['policytype'],
					'policytext' => $policy['policytext']					
				);
			endif;

			$CI->data['login_data'] = $login_data;
			$CI->load->view('AUPEdit', $CI->data);
		endif;    
 	}
 	
 	public function updateAUP()
 	{
		_writelog("INFO","Entering " . __FUNCTION__ );
        $CI = get_session();

		$login_data = $CI->getLoginData();
		
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
			_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		else:
			$request_data['policytype'] = $CI->input->post('policytype',TRUE);
			$request_data['policytext'] = $CI->input->post('auptext',TRUE);
		
			$sql = "SELECT COUNT(*)/100 AS policyver FROM policies";
			$query = $CI->db->query($sql);
			$policy = $query->row_array();
			$request_data['policyver'] = $policy['policyver'];
			
			$sql = "INSERT INTO policies set policyver=?, policytype=?, policytext=?";
			$sqlargs = array(
				$request_data['policyver'],
				$request_data['policytype'],
				$request_data['policytext']
			);
			if($query = $CI->db->query($sql,$sqlargs) == FALSE):
				$context = array('coarsemsg' => 'update of table policies failed during SQL query execution.',
                    'finemsg' => 'The update operation failed. Check your tables!!',
                    'callback' => 'splash');
                _writelog("ERROR","SQL query: row insertion FAILURE");
                _writelog("POLICY","POLICY UPDATE FAILURE");
                _confirm($context);
                return;
            else:
   				$context = array('coarsemsg' => 'update of table policies succeeded.',
                    'finemsg' => 'The update operation was a success.',
                    'callback' => 'splash');
                _writelog("POLICY","Site Policy Updated successfully");
                _confirm($context);
                return;
            endif;
		endif;    
 	}
}
?>
