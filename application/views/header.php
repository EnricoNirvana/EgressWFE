<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" media="screen" type="text/css" href="<?=base_url();?><?$options = $this->config->item('options'); $css_file = $options['css_spec']; $css_path = $options['css_path']; $assets_path = $options['assets_path']; echo $assets_path . '/' . $css_path . '/' . $css_file;?>">
	<link rel="icon" href="<?=base_url();?>favicon.ico">
	<title>Egress Grid Management</title>
	<meta http-equiv="content-script-type" content="text/javascript">
	<script type='text/javascript' src='<?=base_url();?>assets/js/jquery-1.5.1.min.js'></script>
	<script type='text/javascript' src='<?=base_url();?>assets/js/egress.js'></script>
</head>
<?$options=$this->config->item('options'); ?>

<body id="bodypatterns">

                <div id="rootpanel">

                        <div id="headerpane">

                                <div id="headerparts" class="logo"><?= $options['site_title'] ?></div>

                                <div id="headerparts" class="stats"><? $this->load->view('webstats');?></div>

                                <div id="headerparts" class="accent"><hr></div>

                                <div id="headerparts" class="greets">A grid management system for ROBUST-based OpenSimulator grids</div>

                        </div>
<? _writelog("DEBUG","header partial view loaded"); ?>

