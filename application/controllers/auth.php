<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends SH_Controller {
    
    function __construct()
    {
            parent::__construct();
           
            _writelog("INFO","Auth Controller Loaded");
    }

    function login() {
        $CI = get_session();
        _writelog("INFO","Entering Auth/login");

        $login_data = array(
            'session_status' => FALSE,
            'firstname' => $CI->input->post('fname',TRUE),
            'lastname' => $CI->input->post('lname',TRUE),
            'userlevel' => '',
            'passwordhash' => '',
            'salt' => '',
            'UUID' => '',
            'IP' => $CI->session->userdata('ip_address')
        );

        session_set('login_data',$login_data);
        
        $password = $CI->input->post('pw',TRUE);    // THIS COULD CONCIEVABLY CAUSE ISSUES BY XSS FILTER MODIFYING INPUT PASSWORDS

        $query = $CI->db->query("SELECT PrincipalID,UserLevel FROM UserAccounts WHERE UserAccounts.FirstName = '" . $login_data['firstname'] . "' and UserAccounts.LastName = '". $login_data['lastname'] . "';");

        if($query->num_rows() !== 1):
            // ohnoes, not found or sql 3rr0r
                        $context = array('callback' => 'splash',
                                        'coarsemsg' => 'ACCESS DENIED.',
                                        'finemsg' => 'User not found or incorrect password. Epic Fail.'
                                           );
            _writelog("INFO","User Not Found for " . $login_data['firstname'] . " " . $login_data['lastname'] . "@" . $login_data['IP']);
            _confirm($context);
            return;
        else:
            $db_row = $query->row_array();

            $db_UUID = $db_row['PrincipalID'];
            $db_userlevel = $db_row['UserLevel'];

            $query = $CI->db->query("SELECT passwordSalt, passwordHash FROM auth WHERE auth.UUID = '$db_UUID';");

            if($query->num_rows() !== 1):
                // ohnoes, not found or sql 3rr0r
                $context = array('callback' => 'splash',
                                'coarsemsg' => 'No credentials exist for the given user.',
                                  'finemsg' => 'This condition is indicative of an inconsistency in your opensimulator grid database.'
                );
                _writelog("SYSTEM","No Password Salt recorded for user: " . $login_data['firstname'] . " " . $login_data['lastname'] . "@" . $login_data['IP']);
                _confirm($context);
                return;
            else:
                $db_row = $query->row_array();

                $db_salt = $db_row['passwordSalt'];
                $db_hash = $db_row['passwordHash'];

                $calchash = md5(md5($password) . ":" . $db_salt);

                if($db_hash === $calchash): // log 'em in
                    $login_data['session_status'] = TRUE;
                    $login_data['userlevel'] = $db_userlevel;
                    $login_data['passwordhash'] = $db_hash;
                    $login_data['salt'] = $db_salt;
                    $login_data['UUID'] = $db_UUID;
                    $login_data['IP'] = $CI->session->userdata('ip_address');

                    $CI->setLoginData($login_data);

                    _writelog("SYSTEM","Auth Successful for " . $login_data['firstname'] . " " . $login_data['lastname'] . "@" . $login_data['IP']);
                    _writelog("SYSTEM","Processing policy/aggreement");

                                        // _confirm($context);

                    $sql = "SELECT policyver FROM policies ORDER BY policyver DESC LIMIT 1";
                    $query = $CI->db->query($sql);
                    if($query->num_rows()>0):
                        _writelog("INFO","Have policy available");

                        // check versioning if there are any policy documents, otherwise ignore until the next login when there are
                        $db_row = $query->row_array();
                        $currentver = $db_row['policyver'];
                        
                        $sql = "SELECT policyver FROM agreements WHERE UserUUID=?";
                        $sqlargs = array($db_UUID);
                        $query = $CI->db->query($sql,$sqlargs);
                        if($query->num_rows()<1):
                            _writelog("POLICY","No agreement for user: " . $login_data['firstname'] . " " . $login_data['lastname'] . "@" . $login_data['IP']);
                            _writelog("POLICY","Policy review triggered for user: " . $login_data['firstname'] . " " . $login_data['lastname'] . "@" . $login_data['IP']);
                            redirect('auth/updatepolicy');
                            return;
                        else:
                            $db_row = $query->row_array();
                            if($db_row['policyver'] != $currentver):
                                _writelog("POLICY","agreement out of date for user: " . $login_data['firstname'] . " " . $login_data['lastname'] . "@" . $login_data['IP']);
                                _writelog("POLICY","Policy review triggered for user: " . $login_data['firstname'] . " " . $login_data['lastname'] . "@" . $login_data['IP']);
                                redirect('auth/updatepolicy');
                                return;
                            endif;
                            _writelog("POLICY","Agreement on file for user: " . $login_data['firstname'] . " " . $login_data['lastname'] . "@" . $login_data['IP']);
                        endif;
                    endif;
                    
                    $context = array('callback' => 'splash', 
                                         'coarsemsg' => 'Welcome.',
                                         'finemsg' => 'Welcome back, ' . $login_data['firstname'] . '.',
                                         'login_data' => $CI->login_data
                                        );
                    $sql = "UPDATE accountreqs SET ipaddress= ? WHERE uuid= ?";
                       $sqlargs = array(
                       $login_data['IP'],
                       $login_data['UUID']
                    ); 
                    $query = $CI->db->query($sql,$sqlargs);
                       
                    // process profile image for site thumbnail, write to accountreqs
		    $imageId="00000000-0000-0000-0000-000000000000";
                    $sql = "SELECT profileImage from userprofile WHERE useruuid = ?";
                    $sqlargs = array($login_data['UUID']);
                    $query = $CI->db->query($sql,$sqlargs);
                    $db_row = $query->row_array();
                    if(($query->num_rows()<1)):
                        $imageId = "6ad4a23f-d9ba-46a9-bd0f-ea2b0dc5ae71";
                    else:
			if($imageId == "00000000-0000-0000-0000-000000000000"):
			    $imageId = "6ad4a23f-d9ba-46a9-bd0f-ea2b0dc5ae71";
			else:
                            $imageId = $db_row['profileImage'];
			endif;
                    endif;
                    
                    _writelog("INFO","user profile image ID: " . $imageId);

                    $image = getTexture($imageId);
                    $image->resizeImage('110','90',Imagick::FILTER_LANCZOS,1);
                    _saveProfileThumb($image,$login_data['UUID']);
                       
                    _writelog("INFO","Login user: " . $login_data['firstname'] . " " . $login_data['lastname'] . "@" . $login_data['IP']);
                   else: // epic credentials fail
                       $context = array('callback' => 'splash',
                                      'coarsemsg' => 'ACCESS DENIED.',
                                      'finemsg' => 'User not found or incorrect password. Epic Fail.',
                                         'login_data' => $CI->login_data
                                         );
                       _writelog("INFO","Credentials FAILED for user: " . $login_data['firstname'] . " " . $login_data['lastname'] . "@" . $login_data['IP']);

                endif;
                   _confirm($context);
            endif;
        endif;
    }

    function logout() {
        $CI = get_session();
        _writelog("INFO","Entering Auth/logout");
        _writelog("INFO","Logout user: " . $login_data['firstname'] . " " . $login_data['lastname'] . "@" . $login_data['IP']);
        $login_data = array(
                               'session_status' => FALSE,
                               'firstname' => '',
                               'lastname' => '',
                               'userlevel' => '',
                               'passwordhash' => '',
                               'salt' => '',
                               'UUID' => ''
                );

        $CI->setLoginData($login_data);
        redirect('splash');
    }
    
    function updatepolicy() {
        $CI = get_session();
        _writelog("INFO","Entering Auth/updatepolicy");
        
        //Ckeditor's configuration
        $policy['ckeditor1'] = array(
 
            //ID of the textarea that will be replaced
            'id'     =>     'auptextrev',
            'path'    =>    'assets/js/ckeditor',
 
            //Optional values
            'config' => array(
                'toolbar'     =>     "Full",     //Using the Full toolbar
                'width'     =>     "920px",    //Setting a custom width
                'height'     =>     '125px',    //Setting a custom height
                'toolbar'     =>     array(    //Setting a custom toolbar
                    array('Preview','Print','-','Copy','-','Find','SelectAll','-','TextColor','BGColor','Maximize')
                ),
                'readOnly' => 'TRUE'
            )
        );
 
        $policy['ckeditor2'] = array(
 
            //ID of the textarea that will be replaced
            'id'     =>     'tostextrev',
            'path'    =>    'assets/js/ckeditor',
 
            //Optional values
            'config' => array(
                'width'     =>     "920px",    //Setting a custom width
                'height'     =>     '125px',    //Setting a custom height
                'toolbar'     =>     array(    //Setting a custom toolbar
                    array('Preview','Print','-','Copy','-','Find','SelectAll','-','TextColor','BGColor','Maximize')
                )
            )
        );     
        
        $login_data = $CI->getLoginData();
        if(isLoggedIn() == FALSE):
            redirect('splash');
        endif;
        
        // retrieve latest policy version and AUP/TOS documents
        $sql = "SELECT policyver FROM policies ORDER BY policyver DESC LIMIT 1";
        $query = $CI->db->query($sql);
        $thing = $query->row_array();
        $policy['version'] = $thing['policyver'];

        $sql = "SELECT policytext FROM policies WHERE policytype='AUP' ORDER BY policyver DESC LIMIT 1";
        $query = $CI->db->query($sql);
        $thing = $query->row_array();
        $policy['auptext'] = $thing['policytext'];

        $sql = "SELECT policytext FROM policies WHERE policytype='TOS' ORDER BY policyver DESC LIMIT 1";
        $query = $CI->db->query($sql);
        $thing = $query->row_array();
        $policy['tostext'] = $thing['policytext'];
        
        $policy['login_data'] = $login_data;

        // process the policy acceptance form, with validation
        
        $CI->form_validation->set_rules('acceptpolicy','Policy Acceptance','required');
        
        if ($CI->form_validation->run() == FALSE) {
            $CI->load->view('acceptpolicy',$policy);
        } else {
            $sql = "SELECT policyver FROM agreements WHERE UserUUID= ?";
            $sqlargs = array($login_data['UUID']);
            $query = $CI->db->query($sql,$sqlargs);
            
            if($query->num_rows()<1):
                $sql = "INSERT INTO agreements VALUES(?,?)";
                $sqlargs = array(
                    $login_data['UUID'],
                    $policy['version'] 
                );
                $query = $CI->db->query($sql,$sqlargs);
            else:
                $sql = "UPDATE agreements SET policyver=? WHERE UserUUID=?";
                $sqlargs = array(
                    $policy['version'],
                    $login_data['UUID']
                );
                $query = $CI->db->query($sql,$sqlargs);
            endif;
            
            $context = array('callback' => 'splash',
                    'coarsemsg' => 'Policy Agreement Updated.',
                    'finemsg' => 'Your policy agreement has been updated. You should be able to access all features per your user level at this time.');
            _writelog("POLICY","Administrative Policy Edit Completed for user: " . $login_data['firstname'] . " " . $login_data['lastname'] . "@" . $login_data['IP']);
            _confirm($context);
        }
    }

    function accountreq() {
        $CI = get_session();
        _writelog("INFO","Entering Auth/accountreq");

        $req_data = array();
        
        $req_data['avfirst']          = $CI->input->post('rafname',TRUE);
        $req_data['avlast']           = $CI->input->post('ralname',TRUE);
        $req_data['rlfirst']          = $CI->input->post('rrfname',TRUE);
        $req_data['rllast']           = $CI->input->post('rrlname',TRUE);
        $req_data['rpassword']        = $CI->input->post('rpassword',TRUE);
        $req_data['rvpassword']       = $CI->input->post('rvpassword',TRUE);
        $req_data['remail']           = $CI->input->post('remail',TRUE);
        
        $timenow = time();
        $uuid = $this->_uuid();

        $pass1 = $req_data['rpassword'];
        $pass2 = $req_data['rvpassword'];

        // validate form data
        if(($pass1 !== $pass2)||($pass1 == NULL)):
            // validation fails
            echo '<script type="text/javascript" src="../../assets/js/jquery-1.5.1.min.js"></script><script type="text/javascript" src="../../assets/js/jquery.js"></script><script type="text/javascript" src="../../assets/js/jquerymodaldialog.js"></script><script type="text/javascript" src="../../assets/js/prettify.js"></script><script type="text/javascript" language="JavaScript">$.modaldialog.error("Such a bit twiddler.");</script>';
        else:
            // validation succeeds, check for pre-existing account with same avatar name
            $sql = "SELECT PrincipalID from UserAccounts WHERE FirstName = ? AND LastName = ?";
            $sqlargs = array(
                $req_data['avfirst'],
                $req_data['avlast']
            );
            $query = $CI->db->query($sql,$sqlargs);
            if($query == TRUE):
                // an account already exists with the requested avatar name
                $context = array('callback' => 'splash',
                        'coarsemsg' => 'User Name is taken.',
                        'finemsg' => 'Account or Account Request already exists with the requested username.');
                _writelog("INFO","Requested account for " . $req_data['rlfirst'] . " " . $req_data['rllast'] . "/" . $req_data['avfirst'] . " " . $req_data['avlast'] . "@" . $login_data['IP'] . " failed, username exists");
                _confirm($context);
            else:
                // no account exists with requested avatar name
                $salted = sprintf('%s', md5($this->_uuid()));
                $hash = md5(md5($pass1) . ":" . $salted);

                $sql = "INSERT INTO accountreqs (uuid,chronostamp,avFirst,avLast,email,RLfirst,RLlast,passwordhash,notes,salt,status) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
                $sqlargs = array(
                    $uuid,
                    $timenow,
                    $request_data['avname_first'],
                    $request_data['avname_last'],
                    $request_data['email'],
                    $request_data['RL_First'],
                    $request_data['RL_Last'],
                    $hash,
                    '',
                    $salted,
                    'unmoderated'
                );
                $query = $CI->db->query($sql,$sqlargs);
                if($query !== TRUE):
                      // Error inserting into table accountreqs during SQL query execution.',
                      // This is indicative of an underlying operating system or filesystem issue -- have you an impacted disk?'
                    $context = array('callback' => 'splash',
                        'coarsemsg' => 'SQL Query failed.',
                        'finemsg' => 'Account Request failed to be written due to an underlying infrastructure issue.');
                      _writelog("CRITICAL","SQL Query failed during account request for " . $req_data['rlfirst'] . " " . $req_data['rllast'] . "/" . $req_data['avfirst'] . " " . $req_data['avlast'] . "@" . $login_data['IP'] . " failed, SQL query error");
                      _confirm($context);

                else:
                      // Account request filed. Your account request has been filed. If your account request is approved by an admin
                      //  it will become activated at that time. If automatic request moderation is on, then your account is active now.
                    $context = array('callback' => 'splash',
                        'coarsemsg' => 'Account request filed.',
                        'finemsg' => 'Your Account Request has been filed. If your account request is approved by an admin, it will become activated at that time. If automatic request moderation is on, then your account is active now.');
                      _writelog("INFO","Account request for " . $req_data['rlfirst'] . " " . $req_data['rllast'] . "/" . $req_data['avfirst'] . " " . $req_data['avlast'] . "@" . $login_data['IP'] . " successful");
                      _confirm($context);

                endif;
            endif;
        endif;
    }
}
?>
