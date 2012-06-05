<div id="interface">Enter an IP Address<br/>
<div id="workpane">
	<?=form_open($callback); ?>
			<label id='firstname'>IP Address : </label>
			<?=form_input(array('name'        => 'ipaddress',
				              'id'          => 'ipaddress',
				              'value'       => '192.168.0.1',
				              'maxlength'   => '16',
				              'size'        => '16'
				            )); ?><br/><br/>
<div align=left><?=form_submit('getip','Submit'); ?></div>
<?=form_close();?>
<br/>
</div>
<? _writelog("DEBUG","ipaddress_screen partial view loaded"); ?>

