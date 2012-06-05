<?$options=$this->config->item('options');?>
<?=$this->load->view('header',$options);?>
<div id="workpane">
<div id="intface" class="mainmenu">
<?=$coarsemsg;?><br><?=$finemsg;?><br><br>
<br><br>
<?=anchor($callback,'Continue');?><br><br>
</div>
<? $this->load->view('control',$this->login_data);?>
</div>
<? $this->load->view('footer');?>
<? _writelog("DEBUG","confirm view loaded"); 
_writelog("DEBUG","UUID: " . ($this->login_data['UUID'] == '' ? 'Null' : $this->login_data['UUID'])); ?>

