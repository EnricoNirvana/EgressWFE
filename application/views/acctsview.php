<div id="workpane"><em>User Accounts</em>
<div id="intface">
<? $CI = get_session(); 
    foreach($results->result_array() as $row):
		$CI->load->view('disp_accts',$row);
    endforeach; ?>
 <br><br><div align="center"><?= $CI->pagination->create_links(); ?></div>
</div><? $CI->load->view('control'); ?></div>
<? _writelog("DEBUG","acctsview partial view loaded"); ?>

