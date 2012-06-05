<?
$this->load->view('header');?>
<div id="workpane">
<div id="intface" class="mainmenu">
<?php
	$levels = $this->config->item('levels');
	$this->load->view('interface');

	if (isLoggedIn() == TRUE): // test presence of valid creds in session data
	?><div class="interface" id="mainmenu"><?
		if((integer) $login_data['userlevel'] >= (integer) $levels['gridadmin']):    // emit links to grid admin functionality
			_writelog("INFO","Grid Admin Login: " . $login_data['firstname'] . " " . $login_data['lastname'] . "@" . $login_data['IP']);
			echo "<br>Grid Admin<br>";
			echo "<a href=javascript:void(0); onclick=javascript:$('#accountadminmenu').show();$('#mainmenu').hide();>Account Management</a><br>";
			echo "<a href=javascript:void(0); onclick=javascript:$('#gridadminmenu').show();$('#mainmenu').hide();>Grid Management</a><br>";
			echo "<a href=javascript:void(0); onclick=javascript:$('#siteadminmenu').show();$('#mainmenu').hide()>Site Management</a><br>";
		endif;
		if((integer) $login_data['userlevel'] >= (integer) $levels['siteadmin']):    // emit links to site admin functionality
			_writelog("INFO","Site Admin Login: " . $login_data['firstname'] . " " . $login_data['lastname'] . "@" . $login_data['IP']);
			echo "<br>Site Admin<br>";
			echo "<a href=javascript:void(0); onclick=javascript:$('#feedsadminmenu').show();>Feeds Management</a><br>";
			echo "<a href=javascript:void(0); onclick=javascript:$('#filesadminmenu').show();>Files Management</a><br>";
		endif;
		if((integer) $login_data['userlevel'] >= (integer) $levels['regionadmin']):    // emit links to region operator functionality
			_writelog("INFO","Region Admin Login: " . $login_data['firstname'] . " " . $login_data['lastname'] . "@" . $login_data['IP']);
			echo "<br>Region Admin<br>";
			echo "<a href=javascript:void(0); onclick=javascript:$('#feedsadminmenu').show();>NewsFeeds</a><br>";
			echo "<a href=gridmgt/regionlist>Region List</a><br>";
		endif;
		if((integer) $login_data['userlevel'] >= (integer) $levels['unpriveleged']):    // emit links to user functionality if account is not disabled                       
			_writelog("INFO","User Login: " . $login_data['firstname'] . " " . $login_data['lastname'] . "@" . $login_data['IP']);
			echo "<br>Manage your account<br>";
			echo "<a href=javascript:void(0); onclick=javascript:$('#changepw').show();$('#mainmenu').hide();>Password Change</a><br>";
			echo "<a href=map/webmap>Grid Map</a><br>";
			echo "<a href=usermgt/purgeappearance>Purge Appearance</a><br>";
			echo "<a href=javascript:void(0); onclick=javascript:$('#newsfeeds').show();$('#mainmenu').hide();>NewsFeeds</a><br>";
			echo "<a href=javascript:void(0); onclick=javascript:$('#partnering').show();$('#mainmenu').hide();>Partnering</a><br>";
			echo "<a href=javascript:void(0); onclick=javascript:$('#userprofile').show();>Edit Profile</a><br>";
			echo "<a href=javascript:void(0); onclick=javascript:$('#groups').show();>Manage your Groups</a><br>";
			echo "<a href=usermgt/upgradeaccount>Upgrade to Region Operator</a>";
		endif;
		if((integer)$login_data['userlevel'] < (integer) $levels['unpriveleged']): 	    // emit banned avatar notice
			_writelog("INFO","Banned account denied: " . $login_data['firstname'] . " " . $login_data['lastname'] . "@" . $login_data['IP']);
			echo "This account has been administratively disabled.<br>Pleased contact your grid operator to resolve outstanding issues with this account.<br>";
		endif;
		?></div><?
   else:
		redirect('splash');
	endif;?>
</div>
<?=$this->load->view('control',array('login_data' => $login_data));?>
</div>
<?=$this->load->view('footer');?>
<?	_writelog("DEBUG","functionality view loaded"); 
	_writelog("DEBUG","UUID: " . ($login_data['UUID'] == '' ? 'Null' : $login_data['UUID']));
?>
