<div id="workpane"><em>Account Requests</em><br><br>
<div id="intface" class="listbody">
<?foreach($results->result_array() as $row):
		$this->load->view('disp_acctreqs',$row);
    endforeach; ?>
 <br><br><div align="center"><?= $this->pagination->create_links(); ?></div>
</div><? $this->load->view('control',array('login_data',$this->login_data)); ?></div>
<? _writelog("DEBUG","reqsview partial view loaded"); ?>

