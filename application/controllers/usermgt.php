<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usermgt extends SH_Controller {
	
	function __construct()
	{
		parent::__construct();
		_writelog("INFO","Usermgt controller loaded"); 
	}

	function index()
	{
		redirect('splash');
		_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function"); 
    }

	function newaccount() {
        _writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
			_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
        else:
			$context = array('callback' => 'usermgt/newavatar');
			$CI->load->view('accountreq',$context);
			return;
		endif;
	}

	function uiAccountBan() {
        _writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
            _writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		endif;
        
		$CI->load->view('header');
		$CI->load->view('userlookup_screen',array('callback'=> 'usermgt/accountBanAPI','login_data' => $login_data));
		$CI->load->view('control',array('login_data' => $login_data));
		$CI->load->view('footer');
	}
	
	function accountBanAPI() {
		_writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
            _writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		endif;

		$request_data['avname_first'] = $CI->input->post('firstname',TRUE);
		$request_data['avname_last'] = $CI->input->post('lastname',TRUE);
        
		$resdata = $CI->_userByName($request_data);
        
		if( $resdata == ''):
            $message = array(
            	'coarsemsg' => 'Requested Account Not Found.',
    	        'finemsg'   => 'The search failed to return results for the supplied criteria.',
                'callback'  => 'splash'
            );
            _confirm($message);
            return;
        else:
			$id = $resdata['PrincipalID'];
			$scope = $resdata['ScopeID'];
			
			if($scope == "00000000-0000-0000-0000-000000000000"):
				$newscope = "10000000-0000-0000-0000-000000000000";
			else:
				$newscope = "00000000-0000-0000-0000-000000000000";
			endif;
			
			$sql = "UPDATE UserAccounts SET UserLevel = UserLevel * -1, ScopeID=? WHERE PrincipalID=?";
			$sqlargs = array(
				$newscope,
				$id
			);
			$query = $CI->db->query($sql,$sqlargs);
	
			if($query == FALSE):
	       		$message = array('coarsemsg' => 'There is a problem.',
        	                                 'finemsg' => 'Either a user with the ID indicated by the preceding search does not exist, or the table update failed.',
                                             'callback' => 'splash');
                _writelog("CRITICIAL","SQL Failure. Error updating UserAccounts; Query failed, or no user for ID indicated by preceding search.");
                _confirm($message);
                return;
            else:
	            $message = array('coarsemsg' => 'Account ban status toggled.',
        	                                 'finemsg' => 'The previous administrative state of the account has been logically reversed.',
                                             'callback' => 'splash');
                _writelog("INFO","Administrative account ban completed for " . $request_data['avname_first'] . " " . $request_data['avname_last'] . " by " . $login_data['firstname'] . " " . $login_data['lastname']);
                _confirm($message);
                return;
            endif;

        endif;
	}
	
	function unbanUI() 
	{
        _writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
			_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " userlevel: " . $login_data['userlevel'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		else:
			$context = array(
				'callback' => 'usermgt/unbanconfirm',
				'login_data' => $login_data
				);
			$CI->load->view('header',array('login_data' => $login_data));
			$CI->load->view('ipaddress_screen',$context);  
			$CI->load->view('control',array('login_data' => $login_data));
			$CI->load->view('footer');
		endif;
		return;
	}
	
	function unBanconfirm() 
	{
	    _writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
			_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " userlevel: " . $login_data['userlevel'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		else:
			$ip =  $CI->input->post('ipaddress');
			$context = array(
				'callback' => 'usermgt/ipunban/' . $ip,
				'login_data' => $login_data,
				'coarsemsg' => 'UNBLOCK THIS IP' . $ip . '?',
				'finemsg' => 'Click "Continue" to unblock this IP Address, or "return to front page" below to abandon the operation.'
				);
			_confirm($context);
		endif;	
		return;
	}
	
	
	function banUI() 
	{
        _writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
			_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " userlevel: " . $login_data['userlevel'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		else:
			$context = array(
				'callback' => 'usermgt/banconfirm',
				'login_data' => $login_data
				);
			$CI->load->view('header',array('login_data' => $login_data));
			$CI->load->view('ipaddress_screen',$context);  
			$CI->load->view('control',array('login_data' => $login_data));
			$CI->load->view('footer');
		endif;
		return;
	}
	
	function banconfirm() 
	{
	    _writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
			_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " userlevel: " . $login_data['userlevel'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		else:
			$ip =  $CI->input->post('ipaddress');
			$context = array(
				'callback' => 'usermgt/ipban/' . $ip,
				'login_data' => $login_data,
				'coarsemsg' => 'BLOCK THIS IP' . $ip . '?',
				'finemsg' => 'Click "Continue" to block this IP Address, or "return to front page" below to abandon the operation.'
				);
			_confirm($context);
		endif;	
		return;
	}

	function ipunban() 
	{
        _writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
			_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " userlevel: " . $login_data['userlevel'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
		else:
			$ip = $CI->uri->segment(3);
			$output = '';
			$cmd = '/usr/bin/sudo ufw allow proto tcp from ' . $ip;
			exec($cmd,$output);
			$context = array(
				'callback' => 'splash',
				'coarsemsg' => 'UFW IP Unblock Operation Complete.',
				'finemsg' => 'ufw allow ' . $ip . ":<br>"
			);
			foreach($output as $outline)
			{
				$context['finemsg'] = $context['finemsg'] . $outline . "<br>";
			}
			_writelog("POLICY",$context['finemsg'] . "\n\r" . "by " . $login_data['firstname'] . " " . $login_data['lastname']);
			_confirm($context);
		endif;
		return;
	}
	
	function ipban() 
	{
        _writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
			_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " userlevel: " . $login_data['userlevel'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
		else:
			$ip = $CI->uri->segment(3);
			$output = '';
			$cmd = '/usr/bin/sudo ufw deny proto tcp from ' . $ip;
			exec($cmd,$output);
			$context = array(
				'callback' => 'splash',
				'coarsemsg' => 'UFW IP Block Operation Complete.',
				'finemsg' => 'ufw deny ' . $ip . ":<br>"
			);
			foreach($output as $outline)
			{
				$context['finemsg'] = $context['finemsg'] . $outline . "<br>";
			}
			_writelog("POLICY",$context['finemsg'] . "\n\r" . "by " . $login_data['firstname'] . " " . $login_data['lastname']);
			_confirm($context);
		endif;
		return;
	}
	
	function showBans()
	{
        _writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
			_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " userlevel: " . $login_data['userlevel'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
		else:
			$output = '';
			$cmd = '/usr/bin/sudo ufw status verbose';
			exec($cmd,$output);
			$context = array(
				'login_data' => $login_data,
				'callback' => 'splash',
				'coarsemsg' => 'UFW Status Operation Complete.',
				'finemsg' => '<br>'
			);
			foreach($output as $outline)
			{
				$context['finemsg'] = $context['finemsg'] . $outline . "<br>";
			}
			_writelog("POLICY",$context['finemsg'] . "\n\r" . "by " . $login_data['firstname'] . " " . $login_data['lastname']);
			_confirm($context);
		endif;
		return;
	}


	function partnering()
	{
		_writelog("INFO","Entering Partnering Function " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE):
                    	_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " userlevel: " . $login_data['userlevel'] . " attempted to access the " . __FUNCTION__ . " function"); 
	            	redirect('splash');
            		return;
                else:

			$partner = $CI->input->post('partner_name',TRUE);
			$partnermsg = $CI->input->post('partner_msg',TRUE);

			$sql    = "SELECT * FROM UserAccounts WHERE CONCAT(CONCAT(FirstName,' '),LastName) = ?";
			$sqlargs = array(
				$partner
			);
        	        $query  = $CI->db->query($sql,$sqlargs);

                	if($query == FALSE || $query->num_rows() == 0):
				$context = array(
	                                'callback' => 'splash',
        	                        'coarsemsg' => 'Requested User Not Found.',
                	                'finemsg' => 'Your partner request failed to identify any existing user.'
                        	);
                        	_confirm($context);
	                else:
                	        $data = $query->row_array();

				$prt = array(
                                     'tgtID' => $data['PrincipalID'],
				     'askID' => $login_data['UUID'],
				     'message' => $partnermsg
				);

				// first check whether this request answers one that exists already
				$sql    = "SELECT * FROM partnerrequests WHERE tgtUUID = ?";

// $test = $sql . $prt['askID'];
// var_dump($test);
// exit('XD');

                        	$query  = $CI->db->query($sql,$prt['askID']);

				$data = $query->row_array();

				// if not answering an existing request, then do whatever else do the other
                        	if($query == FALSE || $query->num_rows() == 0):
					// enter partner request in database
					// enter message into offline message queue for tgtID from askID
                                	$sql    = "INSERT INTO partnerrequests (askUUID,tgtUUID,message) VALUES (?,?,?)";
                                	$sqlargs = array(
                                        	$prt['askID'],
						$prt['tgtID'],
						$prt['message']
                                	);
                                	$query  = $CI->db->query($sql,$sqlargs);

					if($query === FALSE):
                        			$message = array('coarsemsg' => 'Update of table Partnerrequests failed during SQL query execution.',
			                           'finemsg' => 'This is indicative of an underlying operating system or filesystem error - are you tapped out on diskspace?.',
			                           'callback' => 'splash');
					        _writelog("CRITICAL","SQL Server Error while updating partnerrequests table for " . $login_data['firstname'] . " " . $login_data['lastname']);
					        _confirm($message);
					endif;

// sample offline_im record
// f4f4afd3-f99c-4720-8c2f-40068ad78188 | 2012-02-05 13:35:01 | <GridInstantMessage xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><fromAgentID>b05640e5-daf1-4261-a76d-6701da6dcb6f</fromAgentID><fromAgentName>Sales Reporter Server v1.2</fromAgentName><toAgentID>f4f4afd3-f99c-4720-8c2f-40068ad78188</toAgentID><dialog>19</dialog><fromGroup>false</fromGroup><message>Vendor summary: Littlefield - Sunday - 2012/2/5 - 11:32:00 has been prepared.</message><imSessionID>c63cd9f1-2ceb-402b-bd25-ba3bea76d4b1</imSessionID><offline>0</offline><Position><X>184.97</X><Y>47.55025</Y><Z>22.61448</Z></Position><binaryBucket>TGl0dGxlZmllbGQvMTg0LzQ3LzIyAA==</binaryBucket><ParentEstateID>0</ParentEstateID><RegionID>1489e942-66ae-11e0-ae3e-0800200c9a66</RegionID><timestamp>1328470325</timestamp></GridInstantMessage>     |    1 |

					$xmlmsgpayload = sprintf('<GridInstantMessage xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><fromAgentID>%s</fromAgentID><fromAgentName>%s</fromAgentName><toAgentID>%s</toAgentID><dialog>19</dialog><fromGroup>false</fromGroup><message>%s</message><imSessionID>00000000-0000-0000-0000-000000000000</imSessionID><offline>0</offline><Position><X>128.00</X><Y>128.00</Y><Z>128.00</Z></Position><binaryBucket>TGl0dGxlZmllbGQvMTg0LzQ3LzIyAA==</binaryBucket><ParentEstateID>0</ParentEstateID><RegionID>00000000-0000-0000-0000-000000000000</RegionID><timestamp>0</timestamp></GridInstantMessage>',$login_data['UUID'],$login_data['firstname'] . " " . $login_data['lastname'],$prt['tgtID'],$prt['message']);
					$sql    = "INSERT INTO offline_im (uuid,message,sent) VALUES (?,?,?)";
                                        $sqlargs = array(
                                                $prt['tgtID'],
                                                $xmlmsgpayload,
                                                '0'
                                        );
                                        $query  = $CI->db->query($sql,$sqlargs);

                                        if($query === FALSE):
                                                $message = array('coarsemsg' => 'Update of table offline_im failed during SQL query execution.',
                                                   'finemsg' => 'This is indicative of an underlying operating system or filesystem error - are you tapped out on diskspace?.',
                                                   'callback' => 'splash');
                                                _writelog("CRITICAL","SQL Server Error while updating partnerrequests table for " . $login_data['firstname'] . " " . $login_data['lastname']);
                                                _confirm($message);
                                        endif;
					$message = array('coarsemsg' => 'Your partnership request has been sent.',
                                                'finemsg' => 'Your partnership request has been sent. If your desired partner has no valid email address on file, you will have to notify them yourself by other means.',
						'callback' => 'splash');
                                        _writelog("INFO","A partnership request on behalf of  " . $login_data['firstname'] . " " . $login_data['lastname'] . " has been sent to " . $prt['tgtID'] . ".");
                                        _confirm($message);
				else:
					// update the requests table just for the sake of completeness
					$sql    = "INSERT INTO partnerrequests (askUUID,tgtUUID,message) VALUES (?,?,?)";
                                        $sqlargs = array(
                                                $prt['askID'],
                                                $prt['tgtID'],
                                                $prt['message']
                                        );
                                        $query  = $CI->db->query($sql,$sqlargs);

                                        if($query === FALSE):
                                                $message = array('coarsemsg' => 'Update of table Partnerrequests failed during SQL query execution.',
                                                   'finemsg' => 'This is indicative of an underlying operating system or filesystem error - are you tapped out on diskspace?.',
                                                   'callback' => 'splash');
                                                _writelog("CRITICAL","SQL Server Error while updating partnerrequests table for " . $login_data['firstname'] . " " . $login_data['lastname']);
                                                _confirm($message);
                                        endif;

					// enter askID into tgtID's profile
					$sql    = "UPDATE userprofile SET profilePartner = ? WHERE useruuid = ?";
                                        $sqlargs = array(
                                                $prt['askID'],
                                                $prt['tgtID']
                                        );
                                        $query  = $CI->db->query($sql,$sqlargs);

                                        if($query === FALSE):
                                                $message = array('coarsemsg' => 'Update of table userprofile failed during SQL query execution.',
                                                   'finemsg' => 'This is indicative of an underlying operating system or filesystem error - got disk?.',
                                                   'callback' => 'splash');
                                                _writelog("CRITICAL","SQL Server Error while updating userprofile table for " . $login_data['firstname'] . " " . $login_data['lastname']);
                                                _confirm($message);
                                        endif;
					// enter tgtID into askID's profile
                                        $sql    = "UPDATE userprofile SET profilePartner = ? WHERE useruuid = ?";
                                        $sqlargs = array(
                                                $prt['tgtID'],
                                                $prt['askID']
                                        );
                                        $query  = $CI->db->query($sql,$sqlargs);

                                        if($query === FALSE):
                                                $message = array('coarsemsg' => 'Update of table userprofile failed during SQL query execution.',
                                                   'finemsg' => 'This is indicative of an underlying operating system or filesystem error - got disk?.',
                                                   'callback' => 'splash');
                                                _writelog("CRITICAL","SQL Server Error while updating userprofile table for " . $login_data['firstname'] . " " . $login_data['lastname']);
                                                _confirm($message);
                                        endif;

					// send 'partnered' message to offline message queue for both tgtID and askID from system
					$xmlmsgpayload = sprintf('<GridInstantMessage xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><fromAgentID>00000000-0000-0000-0000-000000000000</fromAgentID><fromAgentName>Virtual Muckymuck</fromAgentName><toAgentID>%s</toAgentID><dialog>19</dialog><fromGroup>false</fromGroup><message>Greeetings!\nYour partnership has been completed and is now a matter of record.</message><imSessionID>00000000-0000-0000-0000-000000000000</imSessionID><offline>0</offline><Position><X>128.00</X><Y>128.00</Y><Z>128.00</Z></Position><binaryBucket>TGl0dGxlZmllbGQvMTg0LzQ3LzIyAA==</binaryBucket><ParentEstateID>0</ParentEstateID><RegionID>00000000-0000-0000-0000-000000000000</RegionID><timestamp>0</timestamp></GridInstantMessage>',$login_data['UUID'],$login_data['firstname'] . " " . $login_data['lastname'],$prt['tgtID']);
                                        $sql    = "INSERT INTO offline_im (uuid,message,sent) VALUES (?,?,?)";
                                        $sqlargs = array(
                                                $prt['tgtID'],
                                                $xmlmsgpayload,
                                                '0'
                                        );
                                        $query  = $CI->db->query($sql,$sqlargs);

$xmlmsgpayload = sprintf('<GridInstantMessage xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><fromAgentID>00000000-0000-0000-0000-000000000000</fromAgentID><fromAgentName>Virtual Muckymuck</fromAgentName><toAgentID>%s</toAgentID><dialog>19</dialog><fromGroup>false</fromGroup><message>Greeetings!\nYour partnership has been completed and is now a matter of record.</message><imSessionID>00000000-0000-0000-0000-000000000000</imSessionID><offline>0</offline><Position><X>128.00</X><Y>128.00</Y><Z>128.00</Z></Position><binaryBucket>TGl0dGxlZmllbGQvMTg0LzQ3LzIyAA==</binaryBucket><ParentEstateID>0</ParentEstateID><RegionID>00000000-0000-0000-0000-000000000000</RegionID><timestamp>0</timestamp></GridInstantMessage>',$login_data['UUID'],$login_data['firstname'] . " " . $login_data['lastname'],$prt['tgtID']);
                                        $sql    = "INSERT INTO offline_im (uuid,message,sent) VALUES (?,?,?)";
                                        $sqlargs = array(
                                                $prt['askID'],
                                                $xmlmsgpayload,
                                                '0'
                                        );
                                        $query  = $CI->db->query($sql,$sqlargs);

                                        if($query === FALSE):
                                                $message = array('coarsemsg' => 'Update of table offline_im failed during SQL query execution.',
                                                   'finemsg' => 'This is indicative of an underlying operating system or filesystem error - are you tapped out on diskspace?.',
                                                   'callback' => 'splash');
                                                _writelog("CRITICAL","SQL Server Error while updating partnerrequests table for " . $login_data['firstname'] . " " . $login_data['lastname']);
                                                _confirm($message);
                                        endif;
                                        $message = array('coarsemsg' => 'Your partnership is completed.',
                                                'finemsg' => 'Your partnership has been completed and is now a matter of formal record.',
                                                'callback' => 'splash');
                                        _writelog("INFO","A partnership request acceptance on behalf of  " . $login_data['firstname'] . " " . $login_data['lastname'] . " seals the deal.");
                                        _confirm($message);

				endif;



				
			endif;
		endif;
		return;
	}


	function edituserbyid($id) 
	{
        _writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
			_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " userlevel: " . $login_data['userlevel'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		else:
			$resdata = $CI->_userById($id);
			$resdata['login_data'] = $login_data;
			$resdata['callback'] = 'usermgt/set_avatarproperties';
			$CI->load->view('header',array('login_data' => $login_data));
			$CI->load->view('accountprops_screen',$resdata);  
			$CI->load->view('control',array('login_data' => $login_data));
			$CI->load->view('footer');
			
		endif;
	}

	function _userById($id) 
	{
        _writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		
		$sql    = "SELECT * FROM UserAccounts LIMIT " . $id . ",1";
		$query  = $CI->db->query($sql);
		
		if($query == FALSE || $query->num_rows() == 0):
			return('');
		else:
			$data = $query->row_array();
			return($data);
		endif;
	}
	
	function usercreate() {
        _writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
		_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " userlevel: " . $login_data['userlevel'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		else:
			
			$context = array('callback' => 'splash',
							 'login_data' => $login_data,
							 'FirstName' => '',
							 'LastName' => '',
							 'UserLevel' => '0',
							 'Email' => '',
							 'UserFlags' => '0',
							 'UserTitle' => 'Badlands Rezzie',
							 'ScopeID' => '00000000-0000-0000-0000-000000000000',
							 'PrincipalID' => _uuid(),
							 'Created' => time()
							 );
			$CI->load->view('header');
			$CI->load->view('accountprops_screen',$context);
			$CI->load->view('control',array('login_data' => $login_data));
			$CI->load->view('footer');

		endif;
    }
    
	function newavatar() {
        _writelog("INFO","Entering " . __FUNCTION__ );
		_newavatar();
	}

	function uiGetUser() {
        _writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
				if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
            _writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		endif;
        
		$context = array('callback' => 'usermgt/userSearchAPI',
			'login_data' => $login_data
		);
		$CI->load->view('header');
		$CI->load->view('userlookup_screen',$context);
		$CI->load->view('footer');
	}
	
	function userSearchAPI() {
		_writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
            _writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		endif;

		$request_data['avname_first'] = $CI->input->post('firstname',TRUE);
		$request_data['avname_last'] = $CI->input->post('lastname',TRUE);
        $resdata = $CI->_userByName($request_data);
		if( $resdata == ''):
            $message = array(
            	'coarsemsg' => 'Requested Account Not Found.',
    	        'finemsg'   => 'The search failed to return results for the supplied criteria.',
                'callback'  => 'splash'
            );
            _confirm($message);
            return;
        else:
        	// resdata should contain an array loaded with the search result
        	// load up the view/data
        	$resdata['callback'] = 'usermgt/set_avatarproperties';
        	$CI->load->view('header');
			$CI->load->view('accountprops_screen',$resdata);
			$CI->load->view('control',array('login_data' => $login_data));
			$CI->load->view('footer');
        endif;
	}
	
	function _userByName() {
        _writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		
		if(func_num_args() <> 0):
			$avatar = func_get_arg(0);
			foreach($avatar as $key => $val) {
				_writelog("DEBUG","\$key = " . $key . " value = " . $val);
			}
				
			$request_data['avname_first'] = $avatar['avname_first'];
			$request_data['avname_last'] = $avatar['avname_last'];
		endif;
        
		$sql    = "SELECT * FROM UserAccounts WHERE FirstName = '" . $request_data['avname_first'] . "' and LastName = '" . $request_data['avname_last'] . "';";
		$query  = $CI->db->query($sql);
		
		if($query == FALSE || $query->num_rows() == 0):
			return('');
		else:
			$data = $query->row_array();
			foreach( $data as $key => $val) {
				_writelog("DEBUG","\$key = " . $key . " value = " . $val);
			}
			return($data);
		endif;
	}
	
	function upgradeaccount()
	{
		_writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE):
            _writelog("SECURITY","Access violation: Anonymous user attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		endif;
		
		$userdata = $CI->_userByName(array('FirstName' => $login_data['firstname'], 'LastName' => $login_data['lastname']));
		_writelog('DEBUG',$login_data['firstname'] . ' ' . $login_data['lastname']);
		// send email to potential registrant
		if($CI->options['enable_email'] == TRUE):
			$CI->email->from($CI->options['email_from_addr'], $CI->options['email_from_text']);
			$CI->email->to($userdata['Email']);
			$CI->email->cc($CI->options['email_from_ccaddr']);

			$CI->email->subject($CI->options['email_ack_subj']);
			$CI->email->message('Greetings ' . $userdata['FirstName'] . ' ' . $userdata['LastName'] . ',' . $CI->options['email_acctupgrade_ack']); 

			$CI->email->send();
			$context = array(
				'callback' => 'splash',
				'coarsemsg' => 'Upgrade Requested.',
				'finemsg' => 'Your upgrade to region operator status has been logged. You will be contacted with additional instructions.'
			);
			_confirm($context);
		else:
			$context = array(
				'callback' => 'splash',
				'coarsemsg' => 'Upgrade Requested.',
				'finemsg' => 'Your upgrade to region operator status has been logged. You will be contacted with additional instructions.'
			);
			_confirm($context);
		endif;
		_writelog('INFO','Account Upgrade Requested by user: ' . $userdata['FirstName'] . ' ' . $userdata['LastName']);
		return;
	}
	
	function new_avatarproperties() {
		_writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
            _writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		endif;

		$userdata = array(
						'FirstName' => $CI->input->post('firstname',TRUE),
						'LastName' => $CI->input->post('lastname',TRUE),
                        'PrincipalID' => $CI->input->post('principalid',TRUE),
                        'Email' => $CI->input->post('cemail',TRUE),
                        'UserLevel' => $CI->input->post('userlvl',TRUE),
                        'UserFlags' => $CI->input->post('userflags',TRUE),
                        'UserTitle' => $CI->input->post('usertitle',TRUE),
                        'ScopeID' => $CI->input->post('scopeid',TRUE)
                );
        
		// handle any changes to password
		$newpass = $CI->input->post('cpassword');
		$renewpass = $CI->input->post('crepassword');

		if(strlen($newpass)>0):
			if($newpass === $renewpass):
				$salt = sprintf('%s', md5(_uuid()));
				$calchash = md5(md5($newpass) . ":" . $salt);

				// update auth table with new password hash
				$sql = "INSERT INTO auth (UUID,passwordHash,passwordSalt,webLoginKey) VALUES (?,?,?,?);";
				$sqlargs = array(
					$userdata['PrincipalID'],
					$calchash,
					$salt,
				        '00000000-0000-0000-0000-000000000000'
				);
				_writelog("DEBUG",$sql);
				
				$query = $CI->db->query($sql,$sqlargs);
				if($query == FALSE): 
					// ohnoes, sql 3rr0r
					$message = array('coarsemsg' => 'SQL Server Error during administrative password change.',
										 'finemsg' => 'This is indicative of an underlying infrastructure problem with the SQL service.',
										 'callback' => 'splash');
					_writelog("CRITICAL","SQL Server Error while administratively updating password hash for user UUID: " . $userdata['PrincipalID'] . " " . $login_data['firstname'] . " " . $login_data['lastname']);
					_confirm($message);
					return;
				endif;
			else:
				$message = array('coarsemsg' => 'Passwords for password change do not match',
                                 'finemsg' => 'This is indicative of a console operator error.',
                                             'callback' => 'usermgt/usercreate');
                _confirm($message);
                return;
			endif;
		endif;

		$sql = "INSERT INTO UserAccounts (PrincipalID,FirstName,LastName,Email,UserLevel,UserFlags,UserTitle,ScopeID) VALUES (?,?,?,?,?,?,?,?)";
		$sqlargs = array(
			$userdata['PrincipalID'],
			$userdata['FirstName'],
			$userdata['LastName'],
			$userdata['Email'],
			$userdata['UserLevel'],
			$userdata['UserFlags'],
			$userdata['UserTitle'],
			$userdata['ScopeID']
		);
		$query = $CI->db->query($sql,$sqlargs);
		if($query === FALSE):
			$message = array('coarsemsg' => 'Administrative update of table UserAccounts failed during SQL query execution.',
                             'finemsg' => 'This is indicative of an underlying operating system or filesystem error - are you tapped out on diskspace?.',
                             'callback' => 'splash');
            _writelog("CRITICAL","SQL Server Error while administratively creating new user; UUID: " . $userdata['PrincipalID'] . " " . $login_data['firstname'] . " " . $login_data['lastname']);
            _confirm($message);
        else:
    		$message = array('coarsemsg' => 'Administrative update of table UserAccounts succeeded during SQL query execution.',
                             'finemsg' => 'The operation was a success.',
                             'callback' => 'splash');
            _writelog("INFO","Administrative update of UserAccounts table (account creation) succeeded for user UUID: " . $userdata['PrincipalID'] . " " . $login_data['firstname'] . " " . $login_data['lastname'] . " completed successfully");
            _confirm($message);
    	endif;
        return;
	}

	function purgeappearance() 
	{
		_writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE):
            _writelog("SECURITY","Access violation: Anonymous user attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		endif;
		
		$sql = sprintf("DELETE FROM Avatars WHERE PrincipalID='%s'", $login_data['UUID']);
		$query = $CI->db->query($sql);
		if($query === FALSE):
			$context = array('coarsemsg' => 'Administrative update of table UserAccounts failed during SQL query execution.',
                             'finemsg' => 'This is indicative of an underlying operating system or filesystem error - are you tapped out on diskspace?.',
                             'callback' => 'splash');
            _writelog("CRITICAL","SQL Server Error while administratively creating new user; UUID: " . $userdata['PrincipalID'] . " " . $login_data['firstname'] . " " . $login_data['lastname']);
            _confirm($context);
        else:
			 $context = array(
			 	 'coarsemsg' => "Appearance Purged. You should be ruthed on next login.",
			 	 'finemsg' => "Your appearance has been purged; you should be ruthed on next login.",
			 	 'callback' => 'splash'
			 );
            _writelog("INFO","Appearance Purged: " . $login_data['firstname'] . " " . $login_data['lastname']);
            _confirm($context);
		endif;
		return;
	}
	
	function set_avatarproperties() {
		_writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
            _writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		endif;

		$userdata = array(
                        'PrincipalID' => $CI->input->post('principalid',TRUE),
                        'Email' => $CI->input->post('cemail',TRUE),
                        'UserLevel' => $CI->input->post('userlvl',TRUE),
                        'UserFlags' => $CI->input->post('userflags',TRUE),
                        'UserTitle' => $CI->input->post('usertitle',TRUE),
                        'ScopeID' => $CI->input->post('scopeid',TRUE)
                );
        
		// handle any changes to password
		$newpass = $CI->input->post('cpassword');
		$renewpass = $CI->input->post('crepassword');

		if(strlen($newpass)>0):
			if($newpass === $renewpass):
				$query = $CI->db->query("SELECT passwordSalt FROM auth WHERE auth.UUID = '".$userdata['PrincipalID']."';");

				if($query->num_rows() !== 1):
					// ohnoes, not found or sql 3rr0r
					$message = array('coarsemsg' => 'auth table record not found during query for password salt',
                                     'finemsg' => 'This is indicative of an inconsistency in your opensimulator grid tables.',
                                             'callback' => 'splash');
                    _writelog("CRITICAL","auth table row for user not found during administrative password change exercise for user UUID: " . $userdata['PrincipalID'] . " " . $login_data['firstname'] . " " . $login_data['lastname']);
                    _confirm($message);
                    return;
				else:
					$db_row = $query->row_array();

					$db_salt = $db_row['passwordSalt'];

					$calchash = md5(md5($newpass) . ":" . $db_salt);

					// update auth table with new password hash
					$query = $CI->db->query("UPDATE auth SET passwordHash = '" . $calchash . "' WHERE auth.UUID = '".$userdata['PrincipalID']."';");
					if($query == FALSE): 
						// ohnoes, sql 3rr0r
						$message = array('coarsemsg' => 'SQL Server Error during administrative password change.',
                                             'finemsg' => 'This is indicative of an underlying infrastructure problem with the SQL service.',
                                             'callback' => 'splash');
                        _writelog("CRITICAL","SQL Server Error while administratively updating password hash for user UUID: " . $userdata['PrincipalID'] . " " . $login_data['firstname'] . " " . $login_data['lastname']);
                        _confirm($message);
                        return;
                    endif;
                endif;
			else:
				$message = array('coarsemsg' => 'Passwords for password change do not match',
                                 'finemsg' => 'This is indicative of a console operator error.',
                                             'callback' => 'usermgt/usercreate');
                _confirm($message);
                return;
			endif;
		endif;

		$sql = "UPDATE UserAccounts SET Email = ?, UserLevel = ?, UserFlags = ?, UserTitle = ?, ScopeID = ? WHERE PrincipalID = ?";
		$sqlargs = array(
			$userdata['Email'],
			$userdata['UserLevel'],
			$userdata['UserFlags'],
			$userdata['UserTitle'],
			$userdata['ScopeID'],
			$userdata['PrincipalID']
		);
		$query = $CI->db->query($sql,$sqlargs);
		if($query === FALSE):
			$message = array('coarsemsg' => 'Administrative update of table UserAccounts failed during SQL query execution.',
                             'finemsg' => 'This is indicative of an underlying operating system or filesystem error - are you tapped out on diskspace?.',
                             'callback' => 'splash');
            _writelog("CRITICAL","SQL Server Error while administratively updating password hash for user UUID: " . $userdata['PrincipalID'] . " " . $login_data['firstname'] . " " . $login_data['lastname']);
            _confirm($message);
            return;
        else:
    		$message = array('coarsemsg' => 'Administrative update of table UserAccounts succeeded during SQL query execution.',
                             'finemsg' => 'The operation was a success.',
                             'callback' => 'splash');
            _writelog("INFO","Administrative update of UserAccounts table succeeded for user UUID: " . $userdata['PrincipalID'] . " " . $login_data['firstname'] . " " . $login_data['lastname'] . " completed successfully");
            _confirm($message);
            return;
    	endif;
	}
	
	function pwchange() {
        _writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE):
            _writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		endif;
                      
		$newpass = $CI->input->post('password');
		$renewpass = $CI->input->post('repassword');

		if(strlen($newpass)>0):
			if($newpass === $renewpass):
				$query = $CI->db->query("SELECT passwordSalt FROM auth WHERE auth.UUID = '" . $login_data['UUID'] . "';");

				if($query->num_rows() !== 1):
					// ohnoes, not found or sql 3rr0r
					$message = array('coarsemsg' => 'auth table record not found during query for password salt',
                                     'finemsg' => 'This is indicative of an inconsistency in your opensimulator grid tables.',
                                     'callback' => 'splash');
                    _writelog("CRITICAL","Auth row not found for user UUID: " . $userdata['PrincipalID'] . " " . $login_data['firstname'] . " " . $login_data['lastname'] . " during password change excercise!");
                    _confirm($message);
                    return;
				else:
					$db_row = $query->row_array();

					$db_salt = $db_row['passwordSalt'];

					$calchash = md5(md5($newpass) . ":" . $db_salt);

					// update auth table with new password hash
					$query = $CI->db->query("UPDATE auth SET passwordHash = '" . $calchash . "' WHERE auth.UUID = '" . $login_data['UUID'] . "';");
					
					if($query == FALSE):
						// ohnoes, sql 3rr0r
						$message = array('coarsemsg' => 'Error during database update to user record.',
                                             'finemsg' =>'This is indicative of an inconsistency in your opensimulator grid tables, or trouble in the tablespace itself.',
                                             'callback' => 'splash');
                        _writelog("CRITICAL","SQL Server error updating auth table for user UUID: " . $userdata['PrincipalID'] . " " . $login_data['firstname'] . " " . $login_data['lastname']);
                        _confirm($message);
                    else:
                        // successful update
						$message = array('coarsemsg' => 'Password Updated.',
                                             'finemsg' =>'Your new password is effective immediately on your next login.',
                                             'callback' => 'splash');
                        _writelog("INFO","Password change exercise for user UUID: " . $userdata['PrincipalID'] . " " . $login_data['firstname'] . " " . $login_data['lastname'] . " successful");
                        _confirm($message);
                    endif;
                    return;
				endif;
			else:
				// password validation issue
				$message = array('coarsemsg' => 'Passwords for password change do not match',
                                             'finemsg' =>'This is indicative of a console operator error.',
                                             'callback' => 'splash');
                _confirm($message);
                return;
			endif;
		endif;
	}
	
	function avatarlist() {
        _writelog("INFO","Entering " . __FUNCTION__ );

		$CI = get_session();
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE || $login_data['userlevel'] < 250):
            _writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
        endif;
        
		//$route['avatarlist/:'] = 'avatarlist'; 
		$config['base_url'] = base_url().'index.php/usermgt/avatarlist/';
		$config['uri_segment'] = 3; 
		$config['total_rows'] = $CI->db->count_all('UserAccounts');
		$config['per_page'] = '15';
		$config['full_tag_open'] = '<div id="pagination">';
		$config['full_tag_close'] = '</div>';

		$CI->load->model('avatarlisting');

		$CI->pagination->initialize($config);
		$viewIndex = $CI->uri->segment($config['uri_segment'],0); 
		$data['results'] = $CI->avatarlisting->get_users($config['per_page'],$viewIndex);
		$data['viewindex'] = $viewIndex;
		// $this->table->set_heading('Avatar First Name','Avatar Last Name','Rezday','Email');
		
		$data['login_data'] = $login_data;
		$CI->load->view('header');
		$CI->load->view('avatarlist',$data);
		$CI->load->view('footer');
    }

}

/* End of file usermgt.php */
/* Location: ./application/controllers/usermgt.php */
?>
