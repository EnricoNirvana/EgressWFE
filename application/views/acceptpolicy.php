<?$options=$this->config->item('options');?>
<?=$this->load->view('header',$options);?>
<div id="workpane">
<div id="intface">
<?=$this->load->view('interface'); ?>
<? echo form_open('auth/updatepolicy', array('name' => 'content')); ?> 
<? echo '<br>Service Policies Updated to version ' . $version; ?><br>
<textarea class=ckeditor1 name=auptextrev id=auptextrev cols=110 rows=10><?=$auptext; ?></textarea>
<? echo display_ckeditor($ckeditor1);
?>
<textarea class=ckeditor2 name=tostextrev id=tostextrev cols=110 rows=10><?=$tostext; ?></textarea>
<? echo display_ckeditor($ckeditor2); 
?><br>
<?= form_checkbox('acceptpolicy', 'acceptpolicy', FALSE); ?>Accept
<br><?= form_submit('submit','Submit');?>
<? form_close(); ?>
</div>
<br><br></div>
<?=$this->load->view('footer');?>
<? _writelog("DEBUG","acceptpolicy view loaded"); 
_writelog("DEBUG","UUID: " . ($login_data['UUID'] == '' ? 'Null' : $login_data['UUID'])); ?>




