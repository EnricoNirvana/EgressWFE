<?php

function session_set($key,$val) {
 __sessionstart();
 $CI =& get_instance();
 return $CI->session->set_userdata($key, $val);
}

function session_get($key) {
 __sessionstart();
 $CI =& get_instance();
 return $CI->session->userdata($key);
}

function get_session() {
 __sessionstart();
 $CI =& get_instance();
 return $CI;
}

function session_clear($key) {
 __sessionstart();
 $CI =& get_instance();
 return $CI->session->unset_userdata($key); 
}


function __sessionstart() {
 if(!isset($GLOBALS['sessioninit'])) {
  $GLOBALS['sessioninit'] = true;
  $CI =& get_instance();
  $CI->load->library('session');
 }
}

?>
