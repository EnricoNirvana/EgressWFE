<? 
    $check = $id % 2;
    if(floor($check) < 0.1):
	    echo '<div id=whitebar>';
    else:
	    echo '<div id=bluebar>';
    endif;
    $outputline = sprintf(" - %'_-8.8s / %'_-8.8s / %'_-12.12s / %'_-8.8s / %'_-8.8s / %'_-8.8s / %'_-50.50s", $avFirst, $avLast, $email, $RLfirst, $RLlast, $status, $notes);
    echo anchor('registration/mod_request/' . $id, 'Mod Request ') . $outputline;
?></div>
<? _writelog("DEBUG","disp_acctreqs partial view loaded"); ?>

