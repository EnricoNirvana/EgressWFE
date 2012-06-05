<div id=workpane>
<div id=intface class=feeds>
<?
	$this->load->view('interface');

	$image = new Imagick();

	foreach($feeds as $feed)
	{
	
		echo "<table>";
		echo "<tr><td>Poster:" . _getUserNameByID($feed['posterid']) . "</td><td align=right>Posted: " . date(DATE_W3C,$feed['chronostamp']) . "</td></tr>";
		echo "<tr><td>";
		echopng(_getUserProfileThumbByID($feed['posterid']));
		echo "</td><td class=float: top>";
		echo $feed['postmarkup'];
		echo "</td></tr>";
		if(isLoggedIn()):
			echo "<tr>";
			if(($feed['commentlock'] == '1') || ($feed['comment'] == '0')):
				echo "<td align=center><a href=commentpost/" . $feed['postid'] . "/" . $feed['feedgroup'] ."/" . $feed['posterid'] . ">comment</a></td>";
			else:
				if($feed['commentlock'] == '1'):
					echo "<td align=center>comments locked</td>";
				endif;
			endif;
			
			if($login_data['UUID'] == $feed['posterid']):
				echo "<td align=center><a href=editpost/" . $feed['postid'] . ">edit post</a> / <a href=deletepost/" . $feed['postid'] . ">remove post</a> / ";
				if($feed['commentlock']):
					echo "<a href=unlockpost/" . $feed['postid'] . ">unlock post</a></td>";
				else:
					echo "<a href=lockpost/" . $feed['postid'] . ">lock post</a></td>";
				endif;
			endif;
		else:
			echo "<tr><td colspan=2 align=center>Log in to comment</td></tr>";
		endif;
		echo "</table>";
		
	}
	$image->destroy();
	
	echo "<p>No more posts in this feed<hr>";
?></div>
<?
$this->load->view('control',array('login_data' => $login_data));
?>
</div>
<? _writelog("DEBUG","feeds partial view loaded"); ?>

