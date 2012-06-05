<?php
$resolution = $this->GetValue("resolution", 2, 128, 8);
$height = $this->GetValue("size", 256, 4096, 1024);
// $img_url =  site_url() . "/map/mapit/" . $resolution . "/" . $height . "/";

$map_tag = $this->mk_map_tag($resolution,$height);
?>
<a href="javascript: void(); onMouseover=javascript:tip(Ocean,lightblue) onMouseout=javascript:hidetip() onMousemove=javascript:movetip()" border="0" usemap="#mapcoords">
<img src="../../assets/img/region.png" width="<?=($resolution*$height)-($resolution*2);?>" height="<?=$height;?>"> 
</a>
<? _writelog("DEBUG","quickmap partial view loaded"); ?>

