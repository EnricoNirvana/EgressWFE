<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// options and configuration points specific to Egress

$config['options']['acct_moderation'] = TRUE;		// auto-create accounts == false setting
$config['options']['feeds_enabled'] = TRUE;		// enable user-generated news feeds


$config['options']['assets_path'] = 'assets';       
$config['options']['css_path'] = 'css';       
$config['options']['js_path'] = 'js';        
$config['options']['img_path'] = 'img';      
$config['options']['fonts_path'] = 'fonts';  

$config['options']['css_spec'] = 'flourine.css';         // select css file from the css folder

$config['options']['site_title'] = 'Littlefield';
$config['options']['loglevel'] = 'ALL';		// ALL INFO WARN DEBUG CRITICAL SYSTEM ERROR POLICY

$config['options']['email_from_text'] = 'Littlefield Warden';
$config['options']['email_from_addr'] = 'root@lfgrid.com';
$config['options']['email_from_ccaddr'] = 'wbalazic@gmail.com';
$config['options']['email_acctreq_ack'] = '

Greetings from Littlefield Grid!

Your account request has been recieved and the wardens have been notified.

You will recieve notification at such time as your account has been approved.

Please check spam box before inquiring concerning the status of your request.

Thanks!
Littlefield Grid Wardens
';

$config['options']['email_acctreq_apr'] = '

Greetings once again from Littlefield Grid!

Your account request has been approved and is now active.

You may log in to the website or grid at anytime using the credentials supplied with your account request.

Point your viewer to the following grid URI:

http://grid.lfgrid.com:8002/

Welcome to the fold!


Cheers,
Littlefield Grid Wardens
';

$config['options']['email_acctupgrade_ack'] = '

Greetings once again from Littlefield Grid!

Your request for an account upgrade has been received.

A Littlefield Grid Warden will contact you in this regard in time. Please monitor your inbox and spam folders.

In the meantime, you may continue to log in to the website or grid at anytime using the credentials supplied with your account request.


Cheers,
Littlefield Grid Wardens
';

// one might wish to give the subjects more distinction or personality than I have included here
$config['options']['email_ack_subj'] = 'Account Request Received';
$config['options']['email_apr_subj'] = 'Account Request Approved';
$config['options']['enable_email'] = TRUE;

$config['options']['ufwsupport'] = TRUE;


// the below are legacy but retained for reference

/* $config['public']['map'] = FALSE;					// map not public
$config['public']['userlist'] = FALSE;				// user list is not public
$config['public']['regionlist'] = FALSE;			// user list is not public
$config['public']['profiles'] = FALSE;				// profiles are not public
$config['public']['groups'] = FALSE;				// groups are not public
*/
/* End of file Egress.php */
/* Location: ./application/config/Egress.php */
?>
