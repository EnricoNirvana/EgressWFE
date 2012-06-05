<?=$this->load->view('header');?>
<?=$this->load->view('webstats');?>
<div id=page1>
<b>Change Password</b>
	<?=form_open('auth/changecreds'); ?>
			Old Password: <?=form_password(array('name'        => 'oldpass',
				              'id'          => 'oldpass',
				              'maxlength'   => '30',
				              'size'        => '15',
					      'type'	    => 'password'
				            )); ?><br><br>
			New Password: <?=form_password(array('name'        => 'newpass',
				              'id'          => 'newpass',
				              'maxlength'   => '30',
				              'size'        => '15',
					      'type'	    => 'password'
				            )); ?><br><br>
			New Password: <?=form_password(array('name'        => 'renewpass',
					      'id'          => 'renewpass',
					      'maxlength'   => '30',
				              'size'        => '15',
					      'type'	    => 'password'
				            )); ?> (confirm)<br>
  	 <input type="submit" name=commit value='Update Password' action='newcreds/changecreds'> <input type="button" name=abandon value='Abandon Password Change' onclick="redirect('splash');">
</div>
        <?=$this->load->view('footer');?>
<? _writelog("DEBUG","newcreds_screen partial view loaded"); ?>

