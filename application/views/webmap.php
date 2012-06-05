<?$options=$this->config->item('options');?>
<?=$this->load->view('header',$options);?>
<div id="workpane">
<div id="intface">
<?$this->load->view('dasmap',$mappath);?>
</div>
<br><br>
<? $options['login_data'] = $login_data; $this->load->view('control',$options);?>
</div>
<?=$this->load->view('footer');?>
<? _writelog("DEBUG","webmap view loaded"); 
_writelog("DEBUG","UUID: " . ($login_data['UUID'] == '' ? 'Null' : $login_data['UUID'])); ?>

