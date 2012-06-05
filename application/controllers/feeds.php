<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// feeds controller

class Feeds extends SH_Controller {
	
	function __construct()
	{
		parent::__construct();
		_writelog("INFO","Feeds controller loaded"); 
	}

	function index()
	{
		$CI = get_session();
		
		$CI->load->model('feed');

		$feeds['posts'] = $CI->feed->getAllReadablePosts($login_data['userlevel']);	
		
		$CI->load->view('feeds',array('login_data' => $login_data, 'feeds' => $feeds));
    }

    function post()
    {
    	_writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$uuid = $CI->uri->segment(3);
		
		//Ckeditor's configuration
		$CI->data['ckeditor'] = array(
 
			//ID of the textarea that will be replaced
			'id' 	=> 	'comment',
			'path'	=>	'assets/js/ckeditor',
 
			//Optional values
				'config' => array(
				'toolbar' 	=> 	"DocToolbar", 	//Using the Full toolbar
				'width' 	=> 	"905px",	//Setting a custom width
				'height' 	=> 	'275px',	//Setting a custom height
 
			)
		);
		
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE):
			_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		else:
			$levels = $CI->config->item('levels');
			$CI->load->model('feed');
			if($login_data['userlevel'] == $levels['gridadmin']):
				$CI->data['feedgroup'] = 'gridfeed';
			elseif($login_data['userlevel'] == $level['siteadmin']):
				$CI->data['feedgroup'] = 'sitefeed';
			elseif($login_data['userlevel'] == $level['regionadmin']):
				$CI->data['feedgroup'] = 'regionfeed';
			elseif($login_data['userlevel'] == $level['user']):
				$CI->data['feedgroup'] = 'userfeed';
			endif;
			
			$CI->data['login_data'] = $login_data;
			$CI->data['postparentid'] = $uuid;
			$CI->data['comment'] = '0';
			$CI->data['visibility'] = '1';
			
			
			$CI->load->view('post',$CI->data);
		endif; 
    }
    
    function postcommit()
    {
    	$CI = get_instance();
    	
    	$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE):
			_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		else:
			$CI->load->model('feed');    	
			$postparentid = $CI->input->post('postparentid');
			$postid = _uuid();			
			$postmarkup = $CI->input->post('comment');
			$chronostamp = time();
			$visibility = '1';
			$comment = '1';
			$commentlock = '0';
			$editlock = '0';
			$feedgroup = $CI->input->post('feedgroup');      
			$poster = $CI->input->post('posterid');
			$comment = 	array(
					  'posterid' => $poster,
					  'postparentid' => $postparentid,
					  'postid' => $postid,
					  'postmarkup' => $postmarkup,
					  'chronostamp' => $chronostamp,
					  'visibility' => $visibility,
					  'comment' => $comment,
					  'commentlock' => $commentlock,
					  'editlock' => $editlock,
					  'feedgroup' => $feedgroup
				);
			
			
			$CI->feed->commentwrite($comment);
		endif;
		redirect('feeds/feedme');
	}
	
    function commentpost()
    {
    	_writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$uuid = $CI->uri->segment(3);
		
		//Ckeditor's configuration
		$CI->data['ckeditor'] = array(
 
			//ID of the textarea that will be replaced
			'id' 	=> 	'comment',
			'path'	=>	'assets/js/ckeditor',
 
			//Optional values
				'config' => array(
				'toolbar' 	=> 	"DocToolbar", 	//Using the Full toolbar
				'width' 	=> 	"905px",	//Setting a custom width
				'height' 	=> 	'275px',	//Setting a custom height
 
			)
		);
		
		$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE):
			_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		else:
			$CI->load->model('feed'); 
			$CI->data['feed'] = array('feeds' => $CI->feed->getPostById($uuid));

			$CI->data['login_data'] = $login_data;
			$CI->data['postparentid'] = $uuid;
			$CI->data['comment'] = '1';
			$CI->data['visibility'] = '1';
			$CI->data['feedgroup'] = $CI->data['feed']['feeds'][0]['feedgroup'];
			
			$CI->load->view('comment', $CI->data);
		endif; 
    }
    
    function commentcommit()
    {
    	$CI = get_instance();
    	
    	$login_data = $CI->getLoginData();
		if(isLoggedIn() == FALSE):
			_writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function"); 
            redirect('splash');
            return;
		else:
		
		
		
			$CI->load->model('feed');    	
			$postparentid = $CI->input->post('postparentid');
			$postid = _uuid();			
			$postmarkup = $CI->input->post('comment');
			$chronostamp = time();
			$visibility = '1';
			$comment = '1';
			$commentlock = '0';
			$editlock = '0';
			$feedgroup = $CI->input->post('feedgroup');      
			$poster = $CI->input->post('posterid');
			$comment = 	array(
					  'posterid' => $poster,
					  'postparentid' => $postparentid,
					  'postid' => $postid,
					  'postmarkup' => $postmarkup,
					  'chronostamp' => $chronostamp,
					  'visibility' => $visibility,
					  'comment' => $comment,
					  'commentlock' => $commentlock,
					  'editlock' => $editlock,
					  'feedgroup' => $feedgroup
				);
			
			
			$CI->feed->commentwrite($comment);
		endif;
		redirect('feeds/feedme');
	}
	
    function deletepost($uuid)
    {
    	$CI = get_session();
    	
    	$CI->load->model('feed');
    	
    	$CI->feed->removePostAndComments($uuid);
    	
    	redirect('feeds/feedme');
    }
    
    function lockpost($uuid)
    {
    	$CI = get_session();
    	
    	$CI->load->model('feed');
    	
    	$CI->feed->commentLock($uuid);

    	redirect('feeds/feedme');
    }
    
    function unlockpost($uuid)
    {
    	$CI = get_session();
    	
    	$CI->load->model('feed');
    	
    	$CI->feed->commentUnLock($uuid);

    	redirect('feeds/feedme');
    }

    function feedme()
    {
	$CI = get_session();
	$login_data = $CI->getLoginData();
         
	if(!isLoggedIn()):
           _writelog("SECURITY","Access violation: user " . $login_data['firstname'] . " " . $login_data['lastname'] . " attempted to access the " . __FUNCTION__ . " function"); 
           redirect('splash');
           return;
	endif;

	// load model
	$CI->load->model('feed');

	// load all readable posts
		
	$feeds = $CI->feed->getAllReadablePosts($login_data['userlevel']);

	// load the view
	$CI->load->view('header');
	$CI->load->view('feeds',array('login_data' => $login_data, 'feeds' => $feeds));
	$CI->load->view('footer');
    }
}
?>
