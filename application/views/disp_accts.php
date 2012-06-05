<? 
    $check = $viewindex % 2;
    if(floor($check) < 0.1):
	    echo '<div id=whitebar>';
    else:
	    echo '<div id=bluebar>';
    endif;
    $outputline = sprintf(" - %'_-20.20s / %'_-20.20s / %'_8.1s / %'_-10.10s", $FirstName, $LastName, $UserLevel, $UserTitle );
    echo anchor('usermgt/edituserbyid/' . $viewindex, 'Update Account ') . $outputline;
?></div>
<? _writelog("DEBUG","disp_accts partial view loaded"); ?>

