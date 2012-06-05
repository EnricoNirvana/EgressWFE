<div id="workpane">
<div id="intface" class="left">
<b>Create User Account</b>
<?=form_open('usermgt/newavatar'); ?><br/><br/>
			Avatar Firstname: <?=form_input(array('name'        => 'firstname',
				              'id'          => 'firstname',
				              'maxlength'   => '64',
				              'size'        => '25',
				            )); ?><br/><br/>
			Avatar Lastname: <?=form_input(array('name'        => 'lastname',
				              'id'          => 'lastname',
				              'maxlength'   => '64',
				              'size'        => '25',
				            )); ?><br/><br/>
			Password: <?=form_password(array('name'        => 'cpassword',
					      'id'          => 'cpassword',
					      'maxlength'   => '25',
				              'size'        => '25',
					      'type'	    => 'password'
				            )); ?><br/><br/>
			Password (again): <?=form_password(array('name'        => 'crepassword',
					      'id'          => 'crepassword',
					      'maxlength'   => '25',
				              'size'        => '25',
					      'type'	    => 'password'
				            )); ?><br/><br/>
			User Level: <?=form_input(array('name'        => 'userlvl',
				              'id'          => 'userlvl',
				              'maxlength'   => '5',
				              'size'        => '5',
				            )); ?><br/><br/>
			email address: <?=form_input(array('name'        => 'cemail',
				              'id'          => 'cemail',
				              'maxlength'   => '64',
				              'size'        => '25',
				            )); ?><br/><br/>
			User Flags: <?=form_input(array('name' => 'userflags',
						'id' => 'userflags',
						'maxlength' => 5,
						'size' => 5,
					    )); ?><br/><br/>
			User Title: <?=form_input(array('name' => 'usertitle',
						'id' => 'usertitle',
						'maxlength' => 64,
						'size' => 15,
					    )); ?><br/><br/>
			Scope ID: <?=form_input(array('name' => 'scopeid',
						'id' => 'scopeid',
						'maxlength' => 36,
						'size' => 36,
					    )); ?><br/><br/>
	<input type="submit" name=commit value='Create User Account'><input type="button" name=abandon value='Abandon Account Creation' onclick="redirect('/');"><br/><br/>
</div>
<? $CI = get_session(); $CI->load->view('control'); ?>
</div>
<? _writelog("DEBUG","userdetail_screen partial view loaded"); ?>

