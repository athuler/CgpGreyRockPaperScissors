<?php
// Content Headers
header('Content-Type: text/csv; charset=utf-16');
header('Content-Disposition: filename="CgpGreyRockPaperScissorsData.csv"');

// Load Secrets & Video Data
require_once("secrets.php");
require_once("video_data.php");

// Build Parents and Paths
build_parents_and_paths("PmWQmZXYd74");

// Fetch Video Views
$view_result = fetch_video_views(10);

// Output CSV Header
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