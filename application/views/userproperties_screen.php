<?=$this->load->view('header');?>
<?=$this->load->view('webstats');?>
<div id="workpane">
<b>User Account Properties</b>
<div id="interface">
<?=form_open($callback); ?>
<br/><br/>
Avatar Firstname: <?=$firstName; ?>
<br/><br/>
Avatar Lastname: <?=$lastName; ?>
<br/><br/>
Avatar UUID: <?=$PrincipalID; ?>
<br/><br/>
Rezday: <?=date("l, F d, Y h:i" ,$Created); ?>
<br/><br/>
Password: <?=form_password(array('name'        => 'password',
					      'id'          => 'password',
					      'maxlength'   => '25',
					      'value'       => '',
				              'size'        => '25',
					      'type'	    => 'password'
				            )); ?>
<br/><br/>
Password: <?=form_password(array('name'        => 'repassword',
					      'id'          => 'repassword',
					      'maxlength'   => '25',
					      'value'       => '',
				              'size'        => '25',
					      'type'	    => 'password'
				            )); ?> (confirm)
<br/><br/>
User Level: <?=form_input(array('name'        => 'userlvl',
				              'id'          => 'userlvl',
				              'maxlength'   => '5',
					      'value' => $UserLevel,
				              'size'        => '5',
				            )); ?>
<br/><br/>
email address: <?=form_input(array('name'        => 'email',
				              'id'          => 'email',
				              'maxlength'   => '64',
					      'value' => $Email,
				              'size'        => '25',
				            )); ?>
<br/><br/>
User Flags: <?=form_input(array('name'        => 'userflags',
				              'id'          => 'userflags',
				              'maxlength'   => '5',
					      'value' => $UserFlags,
				              'size'        => '5',
				            )); ?>
<br/><br/>
User Title: <?=form_input(array('name'        => 'usertitle',
				              'id'          => 'usertitle',
				              'maxlength'   => '64',
					      'value' => $UserTitle,
				              'size'        => '25',
				            )); ?>
<br/><br/>
Scope ID: <?=form_input(array('name'        => 'scopeid',
				              'id'          => 'scopeid',
				              'maxlength'   => '36',
					      'value' => $ScopeID,
				              'size'        => '36',
				            )); ?>
<br/><br/>
Service URLs: <?=form_input(array('name'        => 'serviceurls',
				              'id'          => 'serviceurls',
				              'maxlength'   => '255',
					      'value' => $ServiceURLs,
				              'size'        => '64',
				            )); ?>
<?=form_hidden('PrincipalID',$PrincipalID); ?>
<br/><br/>
<input type="submit" name=commit value='Submit'><td colspan=2>
</div>
<?=$this->load->view('control',$login_data);?>
</div>
<?=$this->load->view('footer');?>
<? _writelog("DEBUG","userproperties_screen view loaded"); 
_writelog("DEBUG","UUID: " . ($login_data['UUID'] == '' ? 'Null' : $login_data['UUID'])); ?>

