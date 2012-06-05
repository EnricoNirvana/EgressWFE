<div id="workpane">
User Lookup<br><br>
<div id="intface" class="listbody"><br>
	<?=form_open($callback); ?>
			<label id='firstname'>Firstname : </label>
			<?=form_input(array('name'        => 'firstname',
				              'id'          => 'firstname',
				              'value'       => '',
				              'maxlength'   => '60',
				              'size'        => '30',
				              'style'       => 'width:50%'
				            )); ?><br/><br/>
			 <label id='lastname'> Lastname : </label>
			 <?=form_input(array('name'        => 'lastname',
				              'id'          => 'lastname',
				              'value'       => '',
				              'maxlength'   => '60',
				              'size'        => '30',
				              'style'       => 'width:50%'
				            )); ?><br/><br/>
<?=form_submit('getuser','Submit'); ?></div>
<?=form_close();?>
<?
$this->load->view('control',array('login_data' => $login_data));
?>
</div>
<? _writelog("DEBUG","userlookup_screen partial view loaded"); ?>

