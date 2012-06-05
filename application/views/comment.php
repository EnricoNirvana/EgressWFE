<?
$options=$this->config->item('options');
$this->load->view('header',$options);
?>
<div id="workpane">
<div id="intface">
<? 
$this->load->view('interface');
$this->load->view('feeds',$feed);

echo form_open('feeds/commentcommit', array('name' => 'content'));?>
<textarea name='comment' id='comment'>'Enter Comment Here'</textarea><? 
echo display_ckeditor($ckeditor);?>
</textarea>
<?
echo form_hidden(
	array('postparentid' => $postparentid,
		'feedgroup' => $feedgroup,
		'posterid' => $login_data['UUID']
		)
	); 

echo form_close(); 
?>
</div>
<br><br>
<? 
$options['login_data'] = $login_data; $this->load->view('control',$options);
?>
</div>
<?
$this->load->view('footer');
_writelog("DEBUG","comment view loaded"); 
_writelog("DEBUG","UUID: " . ($login_data['UUID'] == '' ? 'Null' : $login_data['UUID'])); 
?>

