<?$options=$this->config->item('options');?>
<?=$this->load->view('header',$options);?>
<div id="workpane">
<div id="intface">
<?=$this->load->view('interface');?>
<?= form_open('policy/updateTOS', array('name' => 'content')); ?> 
<? echo '<br>Editing ' . $tostext['policytype'] . ' version ' . $tostext['policyver']; ?><br><br>
<textarea name="tostext" id="tostext" ><? echo $tostext['policytext']; ?></textarea>
<?php echo display_ckeditor($ckeditor); ?>
</textarea>
<?= form_hidden('policytype',$tostext['policytype']); ?>
<?= form_hidden('policyversion',$tostext['policyver']); ?>
<? form_close(); ?>
</div>
<br><br>
<? $options['login_data'] = $login_data; $this->load->view('control',$options);?>
</div>
<?=$this->load->view('footer');?>
<? _writelog("DEBUG","TOSEdit view loaded"); 
_writelog("DEBUG","UUID: " . ($login_data['UUID'] == '' ? 'Null' : $login_data['UUID'])); ?>

