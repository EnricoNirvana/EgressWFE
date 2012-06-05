<div id="workpane">
<b>Update Account Properties</b><br />
<div id="intface" class="left">
<?=form_open($callback); ?>
			Avatar Firstname: <?=form_input(array(
								'name'        	=> 'firstname',
				            	'id'          	=> 'firstname',
				            	'maxlength'   	=> '64',
				            	'size'        	=> '25',
				            	'value'			=> $FirstName
				            )); ?><br/>
			Avatar Lastname: <?=form_input(array(
								'name'        	=> 'lastname',
				            	'id'          	=> 'lastname',
				            	'maxlength'   	=> '64',
				            	'size'        	=> '25',
				            	'value' 		=> $LastName				              
				            )); ?><br/>
			Password: <?=form_password(array(
								'name'        	=> 'cpassword',
					      		'id'          	=> 'cpassword',
					      		'maxlength'   	=> '25',
					      		'size'        	=> '25',
					      		'type'	    	=> 'password',
					      		'value'			=> ''
				            )); ?><br/>
			Password (again): <?=form_password(array(
								'name'        	=> 'crepassword',
					      		'id'          	=> 'crepassword',
					      		'maxlength'   	=> '25',
					      		'size'	        => '25',
					      		'type'	    	=> 'password',
					      		'value'			=> ''
				            )); ?><br/>
			User Level: <?=form_input(array(
								'name'        	=> 'userlvl',
				              	'id'          	=> 'userlvl',
				              	'maxlength'   	=> '5',
				              	'size'        	=> '5',
				              	'value'			=> $UserLevel
				            )); ?><br/>
			email address: <?=form_input(array(
								'name'        	=> 'cemail',
								'id'          	=> 'cemail',
								'maxlength'   	=> '64',
								'size'        	=> '25',
								'value'			=> $Email
				            )); ?><br/>
			User Flags: <?=form_input(array(
								'name' 			=> 'userflags',
								'id' 			=> 'userflags',
								'maxlength' 	=> 5,
								'size' 			=> 5,
								'value'			=> $UserFlags
							)); ?><br/>
			User Title: <?=form_input(array(
								'name' 			=> 'usertitle',
								'id' 			=> 'usertitle',
								'maxlength' 	=> 64,
								'size' 			=> 15,
								'value' 		=> $UserTitle
							)); ?><br/>
			Scope ID: <?=form_input(array(
								'name' 			=> 'scopeid',
								'id'	 		=> 'scopeid',
								'maxlength' 	=> 36,
								'size' 			=> 36,
								'value'			=> $ScopeID
							)); ?><br/>
			<input name='principalid' id='principalid' type='hidden' value='<?=$PrincipalID;?>'><br/><br/>
			<input name='created' id='created' type='hidden' value='<?=$Created;?>'><br/><br/>
 
	<input type="submit" name=commit value='Submit'><br/>
</div>
</div>
<? _writelog("DEBUG","accountprops_screen partial view loaded"); ?>

