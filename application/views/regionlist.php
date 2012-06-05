<?=$this->load->view('header');?>
<?=$this->load->view('webstats');?>
<div id="page2" align=center>
<b>Region List</b><br><br>
<b>Region Name - X Loc - Y Loc - Owner Name</b><br><hr>
<?php foreach($query->result() as $row): ?>
<?=$row->regionName?> - <?=$row->locX?> - <?=$row->locY?> - <?=$row->FirstName?> <?=$row->LastName?><br>
<?php endforeach; ?><hr>
</div>
<?=$this->load->view('footer');?>
<? _writelog("DEBUG","regionlist partial view loaded"); ?>

