<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
//
// various html block elements that will see frequent reuse in view construction
//
*/

$html_macro['head'] = '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><link rel="icon" href="<?=base_url();?>favicon.ico">';
$html_macro['head_cheese'] = '<meta http-equiv="content-script-type" content="text/javascript"><script type="text/javascript" src="<?=base_url();?>assets/scripts/jquery.js"></script><script> function redirect(url){ location.href = url; }</script>';
$html_macro['body'] = '</head><body>';


/* End of file html-macros-config.php */
/* Location: ./application/config/html-macros-config.php */
