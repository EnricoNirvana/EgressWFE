<div id="login_form" class="login_form" style="display: none">
                        <?= form_open('auth/login', array('name' => 'login')) 
                        . form_fieldset()
                        . form_label('Firstname: ','fname',array('id' => 'fname_label'))
                        . form_input(array(   'name' => 'fname',
                                              'id'          => 'fname',
                                              'value'       => '',
                                              'maxlength'   => '60',
                                              'size'        => '30',
                                              'class'       => 'text-input')) . br()
                         . form_label('Firstname: ','fname',array('class' => 'error','id' => 'fname_error'))
                         . form_label('Lastname: ','lname',array('id' => 'lname_label')) 
                         . form_input(array(  'name' => 'lname',
                                              'id'          => 'lname',
                                              'value'       => '',
                                              'maxlength'   => '60',
                                              'size'        => '30',
                                              'class'       => 'text-input')) . br() 
                         . form_label('Lastname: ','lname',array('class' => 'error','id' => 'lname_error'))
                         . form_label('Password: ','pw',array('id' => 'pw_label')) 
                         . form_password(array('name'     => 'pw',
                                              'id'          => 'pw',
                                              'value'       => '',
                                              'maxlength'   => '30',
                                              'size'        => '15',
                                              'class'       => 'text-input')) . br()
                         . form_label('Password: ','pw',array('class' => 'error','id' => 'pw_error'))
                         . br() . form_submit(array('type' => 'submit','name' => 'submit','class' => 'loginbutton','id' => 'loginbutton','value' => 'Log In')) . br(2) 
                         . form_fieldset_close() 
                         . form_close() ;?>
</div>

<div id="acctreq_form" class="acctreq_form" style="display: none">
                         <?=form_open('registration/writeregis',array('name' => 'acctreq')) 
                         . form_fieldset() 
                         . form_label('Avatar name (first): ','afname',array('id' => 'afname_label')) 
                         . form_input(array(  'name'       => 'afname',
                                              'id'         => 'afname', 
                                              'value'      => '',
                                              'maxlength'  => '64', 
                                              'size'       => '25',
                                              'class'      => 'text-input'))
                         . form_label('Avatar name (first): ','afname',array('class' => 'error','id' => 'afname_error')) 
                         . form_label(' (last): ','alname',array('id' => 'alname_label'))
                         . form_input(array('name' => 'alname', 
                                              'id' => 'alname', 
                                              'value' => '',
                                              'maxlength' => '64', 
                                              'size' => '25',
                                              'class' => 'text-input'))
                         . form_label(' (last): ','alname',array('class' => 'error','id' => 'alname_error')) . br()
                         . form_label('Real Name (first): ','rfname',array('id' => 'rfname_label'))
                         . form_input(array('name' => 'rfname', 
                                              'id' => 'rfname', 
                                              'class' => 'text-input',
                                              'maxlength' => '45', 
                                              'size' => '25'))
                         . form_label('Real Name (first): ','rfname',array('class' => 'error','id' => 'rfname_error')) 
                         . form_label('(last): ','rlname',array('id' => 'rlname_label'))
                         . form_input(array('name' => 'rlname', 
                                              'id' => 'rlname', 
                                              'class' => 'text-input',
                                              'maxlength' => '45', 
                                              'size' => '25'))
                         . form_label('(last): ','rlname',array('class' => 'error','id' => 'rlname_error')) 
                         . form_label('Password: ','password',array('id' => 'password_label'))
                         . form_password(array('name' => 'password',
                                              'id' => 'password', 
                                              'class' => 'text-input',
                                              'maxlength' => '25', 
                                              'size' => '25', 
                                              'type' => 'password')) 
                         . form_label('Password: ','password',array('class' => 'error','id' => 'password_error')) 
                         . form_label('(confirm): ','repassword',array('id' => 'repassword_label'))
                         . form_password(array('name' => 'repassword', 
                                              'id' => 'repassword', 
                                              'class' => 'text-input',
                                              'maxlength' => '25', 
                                              'size' => '25', 
                                              'type' => 'password')) 
                         . form_label('(conform): ','repassword',array('class' => 'error','id' => 'repassword_error')) . br()
                         . form_label('eMail Address: ','email',array('id' => 'email_label'))
                         . form_input(array('name' => 'email', 
                                              'id' => 'email', 
                                              'class' => 'text-input',
                                              'maxlength' => '64', 
                                              'size' => '25'))
                         . form_label('eMail Address: ','email',array('class' => 'error','id' => 'email_error')) . br()
                         . br(3) . form_submit(array('type' => 'submit', 'name' => 'submit', 'class' => 'acctreqbutton', 'id' => 'acctreqbutton','value' => 'Request User Account')) .  nbs(3) 
                         . form_button(array(        'type' => 'reset', 'name' => 'cancel', 'class' => 'acctreqcancelbutton', 'id' => 'acctreqcancelbutton','content' => 'Abandon Account Request','onClick' => 'location.href = &quot;splash&quot;;')) 
                         . form_fieldset_close()
                         . form_close();?>
</div>
<div id="accountadminmenu" class="submenu" style="display: none">
          <a href=registration/mod_accountreq>Moderate account requests</a><br>
          <a href=usermgt/usercreate>Create Account</a><br>
          <a href=usermgt/uiGetUser>Account Properties</a><br>
          <a href=usermgt/avatarlist>Browse Accounts</a><br>
          <a href=usermgt/uiAccountBan>Reactivate or Deactivate Account</a><br>
          <? 
          $options=$this->config->item('options'); 
          if($options['ufwsupport'] == TRUE):
          echo "<a href=javascript:void(0); onclick=javascript:$('#ipbansmenu').show();$('#accountadminmenu').hide()>IP Ban</a><br>";
          endif; ?>
          	</div>
<div id="gridadminmenu" class="submenu" style="display: none">
          <a href=policy/AUPEdit>Update AUP</a><br>
          <a href=policy/TOSEdit>Update TOS</a><br>
</div>
<div id="siteadminmenu" class="submenu" style="display: none">
          <a href=javascript:void(0); onClick=javascript:$('#siteadminmenu').hide();$('#mainmenu').show();>Close</a><br>              
</div>


<div id="partnering" class="properties" style="display: none">
<?=form_open('usermgt/partnering',array('name' => 'partner_name')) 
                         . form_fieldset() 
                         . form_label('Desired Partner&quot;s Name: ','partner_name',array('id' => 'partnername_label'))
                         . form_input(array('name' => 'partner_name',
                                              'id' => 'partner_name', 
                                              'class' => 'text-input',
                                              'maxlength' => '255', 
                                              'size' => '100')) 
			 . br()
                         . form_label('Message to desired partner: ','partner_msg',array('id' => 'partnermsg_label'))
                         . form_input(array('name' => 'partner_msg',
                                              'id' => 'partner_msg', 
                                              'class' => 'text-input',
                                              'maxlength' => '255', 
                                              'size' => '100')) 
                         . br(8) . form_submit(array('type' => 'submit', 'name' => 'submit', 'class' => 'partnerreqbutton', 'id' => 'partnerreqbutton','value' => 'Request Partner')) .  nbs(3) 
                         . form_button(array(        'type' => 'reset', 'name' => 'cancel', 'class' => 'partnerreqcancelbutton', 'id' => 'partnerreqcancelbutton','content' => 'Abandon Partner Request','onClick' => 'location.href = &quot;splash&quot;;')) 
                         . form_fieldset_close()
                         . form_close() . br();?>
Note that your desired partner will need a valid email address on file if they are to recieve notification. If they don't, you'll need to ask them to partner via other means.<br>Your request will be valid in the system in either case.
</div>



<div id="changepw" class="properties" style="display: none">
<?=form_open('usermgt/pwchange',array('name' => 'pwchange')) 
                         . form_fieldset() 
                         . form_label('Password: ','password',array('id' => 'password_label'))
                         . form_password(array('name' => 'password',
                                              'id' => 'password', 
                                              'class' => 'text-input',
                                              'maxlength' => '25', 
                                              'size' => '25', 
                                              'type' => 'password')) 
                         . form_label('Password: ','password',array('class' => 'error','id' => 'password_error')) 
                         . form_label('(confirm): ','repassword',array('id' => 'repassword_label'))
                         . form_password(array('name' => 'repassword', 
                                              'id' => 'repassword', 
                                              'class' => 'text-input',
                                              'maxlength' => '25', 
                                              'size' => '25', 
                                              'type' => 'password')) 
                         . form_label('(conform): ','repassword',array('class' => 'error','id' => 'repassword_error')) . br()
                         . br(3) . form_submit(array('type' => 'submit', 'name' => 'submit', 'class' => 'acctreqbutton', 'id' => 'acctreqbutton','value' => 'Change Password')) .  nbs(3) 
                         . form_button(array(        'type' => 'reset', 'name' => 'cancel', 'class' => 'acctreqcancelbutton', 'id' => 'acctreqcancelbutton','content' => 'Abandon Password Change','onClick' => 'location.href = &quot;splash&quot;;')) 
                         . form_fieldset_close()
                         . form_close();?>
</div>
<div id="ipbansmenu" class="submenu" style="display: none">
          <a href=usermgt/showBans>Display blocked IPs</a><br>
          <a href=usermgt/banUI>Block IP</a><br>
          <a href=usermgt/unbanUI>Remove IP Block</a><br>
</div>
<div id="newsfeeds" class="submenu" style="display: none">
          <a href=feeds/post>Post to Feed</a><br>
          <a href=feeds/feedme>Read Feed</a><br>
</div>

<?   _writelog("DEBUG","interface partial view loaded"); ?>

