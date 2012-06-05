<?$options=$this->config->item('options');?>
<?=$this->load->view('header',$options);?>
<div id="workpane">
<div id="intface">
<?=$this->load->view('interface');?>
<?= form_open('policy/updateAUP', array('name' => 'content')); ?> 
<? echo '<br>Editing ' . $auptext['policytype'] . ' version ' . $auptext['policyver']; ?><br><br>
<textarea name="auptext" id="auptext"><? echo $auptext['policytext']; ?></textarea>
<?php echo display_ckeditor($ckeditor); ?>
<?= form_hidden('policytype',$auptext['policytype']); ?>
<?= form_hidden('policyversion',$auptext['policyver']); ?>
<? form_close(); ?>
</div>
<br><br>
<? $options['login_data'] = $login_data; $this->load->view('control',$options);?>
</div>
<?=$this->load->view('footer');?>
<? _writelog("DEBUG","AUPEdit view loaded"); 
_writelog("DEBUG","UUID: " . ($login_data['UUID'] == '' ? 'Null' : $login_data['UUID'])); ?>

