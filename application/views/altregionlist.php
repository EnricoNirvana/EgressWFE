<div id='regionlist1'>
<br/>
<div><center><b>Region List</b></center></div><br/>
<div id='container'>
<?php echo $this->table->generate($results); ?><br/>
<?php echo $this->pagination->create_links(); ?>
<br/>
</div>
</div>
<? _writelog("DEBUG","altregionlist partial view loaded"); ?>
