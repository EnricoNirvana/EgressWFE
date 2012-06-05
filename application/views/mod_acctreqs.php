<?=$this->load->view('header');?>
<div id="workpane">
<div id="intface" class="left">
<?=form_open('registration/update_request'); ?>
Avatar Firstname: <?=$avFirst;?><br>
Avatar Lastname: <?=$avLast;?><br>
email address: <?=$email;?><br>
RL First Name: <?=$RLfirst;?><br>
RL Last Name: <?=$RLlast;?><br>
Status: <?=$status;?><br>
Notes: <br><?=form_textarea(array('name'        => 'notes',
					      'id'          => 'notes',
					      'rows'   => '10',
					      'cols'    => '60',
				              'size'        => '255',
				              'value'      => $notes
				    )); ?>
<br><br><?=form_radio(array('id' => 'action',
			    'name' => 'action',
			    'value' => 'accept'			    
));?> - Approve
<br><?=form_radio(array('id' => 'action',
			'name' => 'action',
			'value' => 'hold'
));?> - Hold
<br><?=form_radio(array('id' => 'action',
			'name' => 'action',
			'value' => 'reject'
));?> - Reject
<br><?=form_radio(array('id' => 'action',
			'name' => 'action',
			'value' => 'update'
));?> - Update Notes
<input type="hidden" name="id" id="id" value="<?=$id;?>">
<br><br><input type="submit" name=commit value='Update Account Request'> <?=anchor('registration/mod_accountreq','Back to Account Moderation');?>
</div>
<?=$this->load->view('control',array('login_data' => $login_data));?>
</div>
<?=$this->load->view('footer');?>
<? _writelog("DEBUG","mod_acctreqs partial view loaded"); ?>

