<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
//
// operations/options ACLs
//
*/

// the following describes what view may be viewed by whom,
// and under what conditions, as follows:
//
// $acl = array(
//            array(
//              'view' => 'viewname',             // name of an application view
//              'login_level' => loginlevel,      // (an integer coupled to UserAccounts.UserLevel)
//                              'logged_in' => true_or_false,     // boolean, public or private, logically literal
//              'allowed' => true_or_false        // boolean, logically literal
//            ),
//            array(
//              'view' => 'viewname',             // name of an application view
//              'login_level' => loginlevel,      // (an integer coupled to UserAccounts.UserLevel)
//                              'logged_in' => true_or_false,     // boolean, public or private, logically literal
//              'allowed' => true_or_false        // boolean, logically literal
//            ),
//                     .
//                     .
//                     .
//            array(
//              'view' => 'viewname',             // name of an application view
//              'login_level' => loginlevel,      // (an integer coupled to UserAccounts.UserLevel)
//                              'logged_in' => true_or_false,     // boolean, public or private, logically literal
//              'allowed' => true_or_false        // boolean, logically literal
//            )
//        ) ;
//
$config['levels'] = array(
					  'unpriveleged' => 0,
                      'user' => 5,
                      'regionadmin' => 75,
                      'siteadmin' => 100,
                      'gridadmin' => 250
        	   );

/* End of file acl-levels-config.php */
/* Location: ./application/config/acl-levels-config.php */
?>
