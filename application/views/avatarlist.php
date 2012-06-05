<div id="workpane"><em>Browse Accounts</em>
<div id="intface">
<?  $offset = $viewindex; foreach($results->result_array() as $row):
		$row['viewindex'] = $offset;
		$this->load->view('disp_accts',$row);
		$offset = $offset + 1;
    endforeach; ?>
<br><br><div align="center"><?= $this->pagination->create_links(); ?></div>
</div><? $this->load->view('control',array('login_data',$this->login_data)); ?></div>
<? _writelog("DEBUG","avatarlist partial view loaded"); ?>

