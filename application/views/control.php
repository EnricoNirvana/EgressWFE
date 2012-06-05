<div id="intface" class="breadcrumbs">
<? 
if($login_data['session_status'] != FALSE):
    echo "Welcome back " . $login_data['firstname'] . ". You may <a href=" . base_url() . "index.php/auth/logout>log out here</a> at any time. Use this link <a href=" . base_url() . "index.php/splash> to return to the front page</a>";
else:    
    echo "You may <a href=javascript:void(0); onclick=javascript:$('#login_form').toggle();$('#acctreq_form').hide();>Log In</a> if you have an account already, or you may <a href=javascript:void(0); onclick=javascript:$('#acctreq_form').toggle();$('#login_form').hide();> Request</a> an account.";
endif;
?>
</div>
<? _writelog("DEBUG","control partial view loaded"); ?>

