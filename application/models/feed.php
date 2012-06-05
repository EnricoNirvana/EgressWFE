<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feed extends CI_Model {

	function __construct() {
		parent::__construct();
 	       _writelog("INFO","Feeds Model Loaded");
	}

	function getPostById($uuid)
	{
		_writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_instance();
		$sql = "SELECT * FROM feeds WHERE postid = ?";
		$sqlargs = array($uuid);
		$query = $CI->db->query($sql,$sqlargs);
		$row = $query->result_array();
		return($row);
	}
	
    function commentwrite($comment)
    {
		_writelog("INFO","Entering " . __FUNCTION__ );
	    	$CI = get_session();
		$sql = "INSERT INTO feeds (postparentid,posterid,postid,postmarkup,chronostamp,visibility,comment,commentlock,editlock,feedgroup) VALUES (?,?,?,?,?,?,?,?,?,?)";
		$sqlargs = array(
			$comment['postparentid'],
			$comment['posterid'],
			$comment['postid'],
			$comment['postmarkup'],
			$comment['chronostamp'],
			$comment['visibility'],
			$comment['comment'],
			$comment['commentlock'],
			$comment['editlock'],
			$comment['feedgroup']
		);
		$query = $CI->db->query($sql,$sqlargs);
    }

	function commentLock($uuid)
	{
		_writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$sql = "UPDATE feeds SET commentlock = TRUE WHERE postid = ?";
		$sqlargs = array(
			$uuid
		);
		$CI->db->query($sql,$sqlargs);		
	}
	
	function commentUnLock($uuid)
	{
		_writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$sql = "UPDATE feeds SET commentlock = FALSE WHERE postid = ?";
			$sqlargs = array(
			$uuid
		);
		$CI->db->query($sql,$sqlargs);				
	}
	
	function removePostAndComments($uuid)
	{
		_writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$sql = "DELETE FROM feeds WHERE postid = ?";
			$sqlargs = array(
			$uuid
		);
		$CI->db->query($sql,$sqlargs);				

		$sql = "DELETE FROM feeds WHERE postparentid = ?";
			$sqlargs = array(
			$uuid
		);
		$CI->db->query($sql,$sqlargs);				
	}
	
	function getFeedBlock($feedtype)
	{
		_writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$feedlist = array();

		$sql = "SELECT * FROM feeds WHERE feedgroup = '" . $feedtype . "' AND visibility = '1' AND comment = '0'";
		$query = $CI->db->query($sql);
		$feed = $query->result_array();
		return($feed);
	}

	function getCommentBlock($postid)
	{
		_writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();
		$comments = array();
		$sql = "SELECT * FROM feeds WHERE feedgroup = 'gridfeed' AND visibility = '1' AND comment = '1' AND postparentid = ?";

		$sqlargs = array(
			$postid
		);

		$query = $CI->db->query($sql,$sqlargs);
		return($query->result_array());
	}

	public function getFeedBlockWithComments($userlevel,$usertype,$feedtype)
	{
		_writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();

        	$gridfeedreaders = $CI->config->item('gridfeedreaders');
		$sitefeedreaders = $CI->config->item('sitefeedreaders');
		$regfeedreaders = $CI->config->item('regfeedreaders');
		$userfeedreaders = $CI->config->item('userfeedreaders');
		$levels = $CI->config->item('levels');
		$feedlist = array();
		
		if($userlevel == $levels[$usertype]):
			$gridfeed = self::getFeedBlock($feedtype);
			
			if(count($gridfeed) != 0):
				foreach($gridfeed as $post)
				{
					array_push($feedlist,$post);

					$comments = self::getCommentBlock($post['postid']);
					
					if(count($comments) > 0):
						foreach($comments as $comment)
						{
							array_push($feedlist,$comment);
						}
					endif;
				}
			endif;
			return($feedlist);
		else:
			return;
		endif;
	}

	function getAllReadablePosts($userlevel)
	{
		_writelog("INFO","Entering " . __FUNCTION__ );
		$CI = get_session();

        	$gridfeedreaders = $CI->config->item('gridfeedreaders');
		$sitefeedreaders = $CI->config->item('sitefeedreaders');
		$regfeedreaders = $CI->config->item('regfeedreaders');
		$userfeedreaders = $CI->config->item('userfeedreaders');
		$levels = $CI->config->item('levels');
		$feedlist = array();
		
		if(isLoggedIn() == FALSE):
			return(self::getFeedBlock('gridfeed'));
		endif;
		
		foreach(array('user','regionadmin','siteadmin','gridadmin') as $level)
		{
			foreach(array('gridfeed','regfeed','sitefeed','userfeed') as $feedtype)
			{
				if($userlevel >= $levels[$level]):
					$feeds = self::getFeedBlockWithComments($userlevel,$level,$feedtype);
					if(count($feeds) > 0):
						foreach($feeds as $feed)
						{
							array_push($feedlist,$feed);
						}
					endif;
				endif;
			}
		}
		return($feedlist);
	}
}

?>
