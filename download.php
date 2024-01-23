<?php
require("video_data.php");
header('Content-Type: text/csv; charset=utf-16');
header('Content-Disposition: filename="data.csv"');

echo("Video ID, Views, Probability, Children, Child Paths, Parents, Paths\n");
foreach($GLOBALS["data"] as $videoID => $currentVideo) {
	echo(
		$videoID . "," . 
		$currentVideo->views . "," . 
		$currentVideo->probability() . "," . 
		implode(";",$currentVideo->children) . "," . 
		implode(";",$currentVideo->child_path) . "," .
		implode(";",$currentVideo->parents) . "," .
		implode(";",$currentVideo->paths) . "," .
		"\n"
	);
}
?>