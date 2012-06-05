<?=$this->load->view('header');?>
<?=$this->load->view('webstats');?>
<div id=displaypane>
<div id="indent1">Bingo.<br><br>You may <?=anchor('login','Log In');?> if you have an account already, or you may <?=anchor('usermgt/newaccount','Establish');?> an account.<br><br></div>
</div><?=$this->load->view('footer');?>
<? _writelog("DEBUG","splash_message_open view loaded"); 
_writelog("DEBUG","UUID: " . ($login_data['UUID'] == '' ? 'Null' : $login_data['UUID'])); ?>

