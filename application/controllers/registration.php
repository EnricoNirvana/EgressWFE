<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Registration extends SH_Controller {
    function __construct()
    {
            parent::__construct();
            _writelog("INFO","Registration Controller Loaded");
    }

    function writeregis()
	{
		_writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();

		$request_data['avname_first'] = $CI->input->post('afname',TRUE);
		$request_data['avname_last'] = $CI->input->post('alname',TRUE);
		$request_data['email']         = $CI->input->post('email',TRUE);
		$request_data['RL_First']      = $CI->input->post('rfname',TRUE);
		$request_data['RL_Last']      = $CI->input->post('rlname',TRUE);

		
		$sql      = "SELECT * FROM accountreqs WHERE avFirst = '" . $request_data['avname_first'] . "' and avLast = '" . $request_data['avname_last'] . "';";
		$query  = $CI->db->query($sql);
		
		if($query->num_rows() > 0):
	       	$message = array('coarsemsg' => 'The requested avatar name already exists in the grid account request tables.',
                                             'finemsg' => 'Someone else (possibly even you) has already requested this avatar name.',
                                             'callback' => 'splash');
			_confirm($message);
			return;
		endif;

		$sql      = "SELECT * FROM UserAccounts WHERE FirstName = '" . $request_data['avname_first'] . "' and LastName = '" . $request_data['avname_last'] . "';";
		$query  = $CI->db->query($sql);

		if($query->num_rows() > 0):
	       		$message = array('coarsemsg' => 'The requested avatar name already exists in the grid user tables.',
                                             'finemsg' => 'Someone else has already taken this avatar name.',
                                             'callback' => 'splash');
			_confirm($message);
			return;
		endif;
		
		
		$timenow = time();
		$uuid      = _uuid();

		$pass1   = $CI->input->post('password');
		$pass2   = $CI->input->post('repassword');

		$salted  = sprintf('%s', md5(_uuid()));
		$hash    = md5(md5($pass1) . ":" . $salted);

		$sql      = "INSERT INTO accountreqs (uuid,chronostamp,avFirst,avLast,email,RLfirst,RLlast,passwordhash,notes,salt,status) VALUES ('".$uuid."',".$timenow.",'".$request_data['avname_first']."','".$request_data['avname_last']."','".$request_data['email']."','".$request_data['RL_First']."','".$request_data['RL_Last']."','".$hash."','','".$salted."','unmoderated');";
		$query   = $CI->db->query($sql);

		if($query !== TRUE):
           	$context = array('callback'  => 'splash',
				       		  'coarsemsg' => 'Error inserting into table accountreqs during SQL query execution.',
				       		  'finemsg'   => 'This is indicative of an underlying operating system or filesystem issue -- have you an impacted disk?'
       		);
       		_writelog("CRITICAL","SQL Service error while updating accountreqs table during submission of request");
			_confirm($context);
		else:
           	$context = array('callback'  => 'splash',
							  'coarsemsg' => 'Account request filed.',
                              'finemsg' => 'Your account request has been filed. If your account request is approved by an admin it will become activated at that time.' .
        				       'If automatic request moderation is on, then your account is active now.'
               		);
       		_writelog("INFO","Account request submitted successfully");
			_confirm($context);
			
			// send email to potential registrant
			if($CI->options['enable_email'] == TRUE):
				$CI->email->from($CI->options['email_from_addr'], $CI->options['email_from_text']);
				$CI->email->to($request_data['email']);
				$CI->email->cc($CI->options['email_from_ccaddr']);
	
				$CI->email->subject($CI->options['email_ack_subj']);
				$CI->email->message('Greetings ' . $request_data['RL_First'] . ' ' . $request_data['RL_Last'] . ',' . $CI->options['email_acctreq_ack']); 
	
				$CI->email->send();
            endif;
		endif;
	}

	function mod_accountreq() {
		_writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
			_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		else:
			$query = $CI->db->query("SELECT * FROM accountreqs WHERE status != 'moderated';");
			if($query->num_rows() === 0):
				$errmsg = array('callback' => 'splash',
						'coarsemsg' => 'Nothing to do.',
						'finemsg' => 'No user account requests available for moderation.'
						);
				$CI->load->view('confirm',$errmsg);
			else:
				$CI->load->view('header');
				$pgconfig['base_url'] = base_url() . 'index.php/registration/mod_accountreq/';
				$pgconfig['total_rows'] = $query->num_rows;
				$pgconfig['per_page'] = '15'; 

				$CI->pagination->initialize($pgconfig); 

				$CI->load->model('reqmod');
				$data['results'] = $CI->reqmod->get_reqs($pgconfig['per_page'],$CI->uri->segment(3));
				$data['login_data'] = $login_data;
				$CI->load->view('reqsview',$data);
				$CI->load->view('footer');
			endif;
        endif;
	}

	function mod_request($id) {
		_writelog("INFO","Entering " . __FUNCTION__ );
		_writelog("DEBUG",(isLoggedIn() == TRUE ? "isLoggedIn() == TRUE" : "isLoggedIn() == FALSE"));
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
			_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function"); 
			redirect('splash');
			return;
		endif;

		$query = $CI->db->query("SELECT * FROM accountreqs WHERE id='" . $id . "';");
		if($query === FALSE):
           	$context = array('callback'  => 'splash',
							  'coarsemsg' => 'Account requests retrieval failed.',
                              'finemsg' => 'An error was returned by the SQL Service while retrieving the rows for your operation.'
               		);
       		_writelog("CRITICAL","SQL Service error while selecting from accountreqs table during moderation workflow");
            _confirm($context);
            return;
        endif;
        
		$row = $query->row_array();
		$row['login_data'] = $login_data;
		$CI->load->view('mod_acctreqs',$row);
	}

	function update_request() {
		_writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
			_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function");
            redirect('splash');
            return;
		endif;
		
		$acct_notes  = $CI->input->post('notes',TRUE);
		$acct_action = $CI->input->post('action',TRUE);
		$req_id      = $CI->input->post('id');

		if($acct_action == 'accept'):
			$query = $CI->db->query("SELECT * from accountreqs WHERE id=".$req_id.";");
			if($query === FALSE):
				$context = array('callback' => 'registration/mod_accountreq',
						'coarsemsg' => 'Error selecting from accountreqs during SQL query execution.',
						'finemsg' => 'Error or no accountreqs rows.'
						);
				_writelog("CRITICAL","SQL Service error or no rows while selecting from accountreqs table during moderation request update");
				_confirm($context);
			endif;
			$db_row = $query->row_array();
			$sql = "INSERT INTO UserAccounts (PrincipalID, ScopeID, FirstName, LastName, Email, ServiceURLs, Created, UserLevel, UserFlags, UserTitle) VALUES (?,?,?,?,?,?,?,?,?,?);";
			$sqlargs = array(
				$db_row['uuid'],
				'00000000-0000-0000-0000-000000000000',
				$db_row['avFirst'],
				$db_row['avLast'],
				$db_row['email'],
				'HomeURI= GatekeeperURI= InventoryServerURI= AssetServerURI= ',
				$db_row['chronostamp'],
				0,
				0,
				''
			);
			$query = $CI->db->query($sql,$sqlargs);
			if($query === FALSE):
				$context = array('callback' => 'registration/mod_accountreq',
						'coarsemsg' => 'Error inserting into UserAccounts table during SQL query execution.',
						'finemsg' => 'This is indicative of an underlying operating system or filesystem issue -- is your disk full?'
						);
				_writelog("CRITICAL","SQL Service error while inserting into UserAccounts table during moderation request update");
				_confirm($context);
			else:
				$user_email = $db_row['email'];

				// write auth table particulars
				$query = $CI->db->query("INSERT INTO auth (UUID,passwordHash,passwordSalt,webLoginKey) VALUES ('".$db_row['uuid']."','".$db_row['passwordhash']."','".$db_row['salt']."','00000000-0000-0000-0000-000000000000');");
				if($query === FALSE):
					$context = array('callback' => 'registration/mod_accountreq',
							'coarsemsg' => 'Error inserting into auth table during SQL query execution.',
							'finemsg' => 'This is indicative of an underlying operating system or filesystem issue -- is your disk full?'
							);
					_writelog("CRITICAL","SQL Service error while inserting into auth table during moderation request update");
					_confirm($context);
				else:
					// write inventory skeleton
					$inv_template = array('Calling Cards' =>  2,
							      'Objects' =>  6,
							      'Landmarks' =>  3,
							      'Clothing' =>  5,
							      'Gestures' => 21,
							      'Body Parts' => 13,
							      'Textures' =>  0,
							      'Scripts' => 10,
							      'Photo Album' => 15,
							      'Lost And Found' => 16,
							      'Trash' => 14,
							      'Notecards' =>  7,
							      'My Inventory' =>  9,
							      'Sounds' =>  1,
							      'Animations' => 20
					);
					// $inv_masterID is the uuid of the 'My Inventory' folder, the parent folder of all other folders, and which has a
					// folder UUID of UUID.zero
					// all other folders have a unique, random UUID for a folder ID, and the folder ID of the 'My Inventory' folder for their
					// parent folder ID.

					$inv_masterID = _uuid();

					foreach ($inv_template as $invfldr_name => $inv_type) {
						$invfldr_uuid = _uuid();

						if($inv_type === 9):
							$invfldr_uuid = $inv_masterID;
							$inv_parent = '00000000-0000-0000-0000-000000000000';
						else:
							$inv_parent = $inv_masterID;
						endif;
						$query = $CI->db->query("INSERT INTO inventoryfolders (folderName,type,version,folderID,agentID,parentFolderID) VALUES ('".$invfldr_name."','".$inv_type."','1','".$invfldr_uuid."','".$db_row['uuid']."','".$inv_parent."');");
						if($query === FALSE):
							$context = array('callback' => 'registration/mod_accountreq',
									'coarsemsg' => 'Error inserting into inventoryfolders table during SQL query execution.',
									'finemsg' => 'This is indicative of an underlying operating system or filesystem issue -- is your storage volume functional?'
									);
							_writelog("CRITICAL","SQL Service error while inserting into inventory table during account creation workflow");
							_confirm($context);
						endif;
					}
				endif;
			endif;
			$query = $CI->db->query("UPDATE accountreqs SET status='moderated' WHERE id='".$req_id."';");
			if($query === FALSE):
				$context = array('callback' => 'registration/mod_accountreq',
						'coarsemsg' => 'Error updating accountreqs table during account creation workflow.',
						'finemsg' => 'This is indicative of an underlying operating system or filesystem issue -- is your filesystem impacted?'
						);
				_writelog("CRITICAL","SQL Service error while updating accoutnreqs table during account creation workflow");
				_confirm($context);
			else:
				// send email to new registrant
				if($CI->options['enable_email'] == TRUE):
					$CI->email->from($CI->options['email_from_addr'], $CI->options['email_from_text']);
					$CI->email->to($user_email);
					$CI->email->cc($CI->options['email_from_ccaddr']);
		
					$CI->email->subject($CI->options['email_apr_subj']);
					$CI->email->message('Greetings ' . $request_data['RL_First'] . ' ' . $request_data['RL_Last'] . ',' . $CI->options['email_acctreq_apr']); 
		
					$CI->email->send();
				endif;
				_writelog("INFO","Account request for " . $db_row['avFirst'] . " " . $db_row['avLast'] . " Approved by " . $login_data['firstname'] . " " . $login_data['lastname'] . " from: " . $login_data['IP']);				
			endif;
		elseif($acct_action == 'hold'):
			$query = $CI->db->query("UPDATE accountreqs SET status='hold' WHERE id='".$req_id."';");
			if($query === FALSE):
				$context = array('callback' => 'registration/mod_accountreq',
						'coarsemsg' => 'Error updating accountreqs table during account request moderation workflow.',
						'finemsg' => 'This is indicative of an underlying operating system or filesystem issue -- what is the status of your storage volumes?'
						);
				_writelog("CRITICAL","SQL Service error while updating accountreqs table during account request moderation workflow");
				_confirm($context);
			endif;
		elseif($acct_action == 'reject'):
			$query = $CI->db->query("UPDATE accountreqs SET status='rejected' WHERE id='".$req_id."';");
			if($query === FALSE):
				$context = array('callback' => 'registration/mod_accountreq',
						'coarsemsg' => 'Error updating accountreqs table during account request moderation workflow.',
						'finemsg' => 'This is indicative of an underlying operating system or filesystem issue -- what is the status of your storage volumes?'
						);
				_writelog("CRITICAL","SQL Service error while updating accountreqs table during account request moderation workflow");
				_confirm($context);
			endif;
		elseif($acct_action == 'update'):
			if($acct_notes != ""):
				$query = $CI->db->query("UPDATE accountreqs SET notes='" . $acct_notes . "' WHERE id='" . $req_id . "';");
				if($query === FALSE):
				$context = array('callback' => 'registration/mod_accountreq',
						'coarsemsg' => 'Error updating accountreqs table during account request moderation workflow.',
						'finemsg' => 'This is indicative of an underlying operating system or filesystem issue -- what is the status of your storage volumes?'
						);
				_writelog("CRITICAL","SQL Service error while updating accountreqs table during account request moderation workflow");
				_confirm($context);
				endif;
			endif;
		endif;
		redirect('registration/mod_accountreq');
	}
}

/* End of file registration.php */
/* Location: ./application/controllers/registration.php */
?>
