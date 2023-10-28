<?php
// Error Handling
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Load Secrets
require_once("secrets.php");

###### Create Video Tree #####
require_once("video_data.php");
$first_vid = "PmWQmZXYd74";


###### Build Paths & Parents #####
foreach($GLOBALS["data"] as $video_id => $video) {
	$num_children = count($video->children);
	
	for($j = 0; $j < $num_children; $j ++) {
		
		
		// Add Parent
		$GLOBALS["data"][$video->children[$j]]->add_parent($video_id);
		
		// Add New Path(s)
		$new_paths = [];
		foreach($video->paths as $current_path) {
			array_push($new_paths,
				$current_path . $video->child_path[$j]
			);
		}
		$GLOBALS["data"][$video->children[$j]]->add_path(array_unique($new_paths));
		
	}
}


###### Fetch Video Views ######
// Check last time videos were checked
$last_query_file = "last_query.txt";
$views_cache_file = "views_cache.txt";

if(!file_exists($last_query_file)) {
	// Time of Last Query Doesn't Exist
	file_put_contents($last_query_file, serialize(new DateTime()));
}

if(!file_exists($views_cache_file)) {
	// Views Cache Doesn't Exist
	file_put_contents($views_cache_file, serialize([]));
}
	

$date_last_query = unserialize(file_get_contents($last_query_file));

//Check time since last query
$time_difference_minutes = $date_last_query->diff(new DateTime)->i;

if($time_difference_minutes >= 1) {
	// If it's been more than 1 minute, cache video data

	// Process videos in batches of 50
	$all_ids = array_chunk(
				array_keys($GLOBALS["data"]),
				50);

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$all_responses = [];
	foreach($all_ids as $id_list) {

		curl_setopt_array($curl, [
			CURLOPT_URL => "https://youtube.googleapis.com/youtube/v3/videos?part=statistics&id=" . implode(",", $id_list) . "&key=" . $GLOBALS["API_KEY"],
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => [],
		]);
		$response = json_decode(curl_exec($curl), true);
		if (array_key_exists("items", $response)){
			$all_responses = array_merge($all_responses,$response["items"]);
		}
	}
	// Cache Video Data
	file_put_contents($views_cache_file, serialize($all_responses));
	// Update Time file
	file_put_contents($last_query_file, serialize(new DateTime()));
} else {
	// Get Data from file
	$all_responses = unserialize(file_get_contents($views_cache_file));
}

//var_dump($all_responses);
//exit();

// Save video views to tree
foreach($all_responses as $vid) {
	$GLOBALS["data"][$vid["id"]]->set_views($vid["statistics"]["viewCount"]);
}



//var_dump($GLOBALS["data"]);
//exit();

?>

<!DOCTYPE html>
<html>
<head>
	<title>CGP Grey: Rock Paper Scissors - A Statistical Analysis</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-J4H5B1ENEE"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', 'G-J4H5B1ENEE');
	</script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>
<!--<body class="text-center container-fluid overflow-x-scroll">-->
<body class="text-center overflow-x-auto">
	<div class="row justify-content-center">
		<div class="col-auto ">
			<!-- Title -->
			<h1 id="title">CGP Grey: Rock Paper Scissors</h1>
			<h2>A Visual and Statistical Overview</h2>
			<p><i>Last Updated: <?=$date_last_query->format("y/m/d - H:i")?></i></p>
			<br/><br/>
			
			<!-- Visual Controls -->
			<h3>Controls:</h3>
			<input type="button" class="btn btn-primary" value="Show/Hide Children & Parents" onclick="toggle_family()"/>
			<input type="button" class="btn btn-primary" value="Show/Hide All Paths" onclick="toggle_paths()"/>
			
			<!-- Statistics -->
			<br/><br/>
			<h3>Some statistics:</h3><p>
			<b><?=count(array_keys($GLOBALS["data"]))?></b> videos<br/>
			<b><?php
				// Compute total video views
				$total_views = 0;
				foreach($GLOBALS["data"] as $vid){
					$total_views += $vid->views;
				}
				echo(number_format($total_views));
			?></b> total views
			
			</p><br/><br/>
			
			<!-- About -->
			<h3>About this Project:</h3>
			<p>Made by <a href="https://github.com/athuler" target="__blank">athuler</a> with much blood, sweats, and so many tears.</p>
			<p>Bug? Something wrong? <a href="https://github.com/athuler/CgpGreyRockPaperScissors/issues" target="__blank">Report it!</a></p>
			
			<div class="row justify-content-center">
			<a href="https://github.com/athuler/CgpGreyRockPaperScissors" target="__blank" class="col-auto">Source</a>
			
			<a href="https://github.com/sponsors/athuler" target="__blank" class="col-auto"><img src="https://img.shields.io/static/v1?label=Sponsor&message=%E2%9D%A4&logo=GitHub&color=%23fe8e86"/></a>
			</div>
		</div>
	</div>
	<br/>
	<div id="svgContainer" style="margin: 0px 0px;">
		<svg id="svg1" width="0" height="0" >
			<?php
			###### Add Arrows #####
			foreach($GLOBALS["data"] as $video_id => $video) {
				$num_children = count($video->children);
				for($j = 0; $j < $num_children; $j ++) {
					if($video->child_path[$j] == "W") {
						$color = "green";
					} else if($video->child_path[$j] == "L") {
						$color = "red";
					} else {
						$color = "grey";
					}
				?>
				<path
					id="<?=$video_id . "-" . $video->children[$j]?>"
					d="M0 0"		 
					stroke="<?=$color?>" 
					fill="none"
					stroke-width="<?=$video->views/$GLOBALS["data"][$first_vid]->views*125?>px";/>
					<?#=round($video->probability()*50)?>
				<?php
				}
			}
			?>
		</svg>
	</div>
	
	<!-- Video Blocks -->
	<?php
	
###### Display Video Tree #####
$current_vids = [$first_vid];
$shown_vids = [];
while($current_vids != []) {
	#echo(implode(", ", $current_vids) . "<br/>");
	echo("<div class='row flex-row flex-nowrap justify-content-center video-row'>");
	$next_vids = [];
	$just_showed_vids = [];
	foreach($current_vids as $vid_id) {
		
		$video = $GLOBALS["data"][$vid_id];
		
		
		##### Whether to Display This Video Now #####
		
		// Check if it's been displayed before
		if(in_array($vid_id, $shown_vids)) {
			continue;
		}
		
		// Check all its parents have been displayed
		$parents_list = $video->parents;
		foreach($video->parents as $parent) {
			if(in_array($parent, $shown_vids)) {
				$parents_list = array_diff($parents_list, [$parent]);
			}
		}
		if(count($parents_list) != 0){
			array_push($next_vids,$vid_id);
			#echo("Not displaying " . $vid_id . ": Missing parent(s) " . implode(",", $parents_list) );
			continue;
		}
		
		array_push($just_showed_vids, $vid_id);
		
		?>
		
		<div class="video <?php if($video->ending){echo("ending");}?>" id="<?=$vid_id?>" style="margin-right:<?=count($video->parents) * 50 + count($video->children) * 150?>px;magin-top:<?=random_int(10,100)?>px;">
			<b><?=$vid_id?></b> <a href="https://youtube.com/watch?v=<?=$vid_id?>" target="__blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-up-right" viewBox="0 0 16 16">
	<path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/>
	<path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/>
</svg></a><br/>
			
			<p class="paths"><u>Paths</u>:<?=implode(", ",$video->paths)?><br/></p>
			
			<u>Actually</u>: <?=number_format($video->views/$GLOBALS["data"][$first_vid]->views, 3)?> /
			<?=number_format($video->views)?> views<br/>
			
			<u>Predicted</u>: <?=number_format($video->probability(),3)?> /
			<?=number_format($video->probability() * $GLOBALS["data"][$first_vid]->views)?> views<br/>
			
			<p class="children"><u>Children</u>:<br/><?=implode("<br/>",$video->children)?></p>
			
			<p class="parents"><u>Parents</u>:<br/><?=implode("<br/>",$video->parents)?></p>
			
			<?php if($video->ending){?>
			<br/><h3>END</h3>
			<?php } ?>
			
			<!-- Connector for path trace -->
			<?php if(
					count($video->children) == 2 and
					$video->child_path[0] == "L" and
					$video->child_path[1] == "W"
					) { ?>
			<div class="row">
				<div class="offset-3 col-3" id="<?=$vid_id?>-L">Lose<br/><a href="#<?=$video->children[0]?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
	<path d="M3.204 5h9.592L8 10.481 3.204 5zm-.753.659 4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z" stroke="red"/>
</svg></a></div>
				<div class="col-3" id="<?=$vid_id?>-W">Win<br/><a href="#<?=$video->children[1]?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
	<path d="M3.204 5h9.592L8 10.481 3.204 5zm-.753.659 4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z"	stroke="green"/>
</svg></a></div>
			</div>
			<?php } else if(
					count($video->children) == 2 and
					$video->child_path[0] == "E" and
					$video->child_path[1] == "B"
					) { ?>
			<div class="row">
				<div class="offset-3 col-3"	id="<?=$vid_id?>-E">
				End<br/><a href="#<?=$video->children[0]?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
	<path d="M3.204 5h9.592L8 10.481 3.204 5zm-.753.659 4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z" stroke="grey"/>
</svg></a>
				</div>
				<div class="col-3"	id="<?=$vid_id?>-B">
				Billion!<br/><a href="#<?=$video->children[1]?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
	<path d="M3.204 5h9.592L8 10.481 3.204 5zm-.753.659 4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z" stroke="grey"/>
</svg></a>
				</div>
			</div>
			<?php } else if(
					count($video->children) == 2 and
					$video->child_path[0] == "E" and
					$video->child_path[1] == "T"
					) { ?>
			<div class="row">
				<div class="offset-3 col-3"	id="<?=$vid_id?>-E">
				End<br/><a href="#<?=$video->children[0]?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
	<path d="M3.204 5h9.592L8 10.481 3.204 5zm-.753.659 4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z" stroke="grey"/>
</svg></a>
				</div>
				<div class="col-3"	id="<?=$vid_id?>-T">
				Trillion!!<br/><a href="#<?=$video->children[1]?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
	<path d="M3.204 5h9.592L8 10.481 3.204 5zm-.753.659 4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z" stroke="grey"/>
</svg></a>
				</div>
			</div>
			<?php } else if(
					count($video->children) == 3 and
					$video->child_path[0] == "R" and
					$video->child_path[1] == "P" and
					$video->child_path[2] == "S"
					) { ?>
			<div class="row">
				<div class="col-4"	id="<?=$vid_id?>-R">
				Rock<br/><a href="#<?=$video->children[0]?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
	<path d="M3.204 5h9.592L8 10.481 3.204 5zm-.753.659 4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z" stroke="grey"/>
</svg></a>
				</div>
				<div class="col-4"	id="<?=$vid_id?>-P">
				Paper<br/><a href="#<?=$video->children[1]?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
	<path d="M3.204 5h9.592L8 10.481 3.204 5zm-.753.659 4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z" stroke="grey"/>
</svg></a>
				</div>
				<div class="col-4"	id="<?=$vid_id?>-S">
				Scissors<br/><a href="#<?=$video->children[2]?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
	<path d="M3.204 5h9.592L8 10.481 3.204 5zm-.753.659 4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z" stroke="grey"/>
</svg></a>
				</div>
			</div>
			<?php } ?>
		</div>
		<?php
		
		// Save Children of this Video For Next Row
		$next_vids = array_merge($next_vids, $video->children);
	}
	echo("</div>");
	
	//echo("<br/><br/><br/><br/>");
	$current_vids = array_unique($next_vids);
	$shown_vids = array_unique(array_merge($shown_vids, $just_showed_vids));
}

	
	?>
	
	
	


	<script src="svgDraw.js"></script>
	<script>
	function connectAll() {
		<?php
		###### Add Arrow Connections #####
		foreach($GLOBALS["data"] as $video_id => $video) {
			$num_children = count($video->children);
	
			for($j = 0; $j < $num_children; $j ++) {
			#foreach($video->children as $child) {
				$child_id = $video->children[$j];
				$child_lose_win = $video->child_path[$j];
				if($child_lose_win == "W" or $child_lose_win == "L" or $child_lose_win == "E" or $child_lose_win == "B" or $child_lose_win == "R" or $child_lose_win == "P" or $child_lose_win == "S") {
					$child_lose_win = "-" . $child_lose_win;
				} else {
					$child_lose_win = "";
				}
			?>
			connectElements(
				$("#svg1"),
				$("#<?=$video_id . "-" . $child_id?>"),
				$("#<?=$video_id.$child_lose_win?>"),
				$("#<?=$child_id?>"));
			<?php
			}
		}
		?>
	}
	
	function Scrolldown() {
		var rect = document.getElementById("title").getBoundingClientRect();
		console.log((rect.right+rect.left)/2);
		console.log(document.body.scrollWidth);
		window.scroll(document.body.scrollWidth*0.25,0); 
	}

	window.onload = Scrolldown;
	
	function toggle_family() {
		elements = document.getElementsByClassName("children");
		for (var i = 0; i < elements.length; i++) {
			elements[i].style.display = elements[i].style.display == 'inline' ? 'none' : 'inline';
		}
		elements = document.getElementsByClassName("parents");
		for (var i = 0; i < elements.length; i++) {
			elements[i].style.display = elements[i].style.display == 'inline' ? 'none' : 'inline';
		}
		
		$(document).ready(function() {
			// reset svg each time 
			$("#svg1").attr("height", "0");
			$("#svg1").attr("width", "0");
			connectAll();
		});

		$(window).resize(function () {
			// reset svg each time 
			$("#svg1").attr("height", "0");
			$("#svg1").attr("width", "0");
			connectAll();
		});
	}
	function toggle_paths() {
		elements = document.getElementsByClassName("paths");
		for (var i = 0; i < elements.length; i++) {
			elements[i].style.display = elements[i].style.display == 'inline' ? 'none' : 'inline';
		}
		
		$(document).ready(function() {
			// reset svg each time 
			$("#svg1").attr("height", "0");
			$("#svg1").attr("width", "0");
			connectAll();
		});

		$(window).resize(function () {
			// reset svg each time 
			$("#svg1").attr("height", "0");
			$("#svg1").attr("width", "0");
			connectAll();
		});
	}
	
	document.getElementById('title').focus();
	
	</script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
	
	<style>
	
	#svgContainer { 
		z-index: -10;
		position:absolute;
		/*background-color:white;*/
		opacity: 0.4;
	}

	div{ opacity: 1; }
	
	.video {
		width:320px;
		margin:50px;
		padding:10px;
		font-size:16px;
		margin-right:200px;
	}
	
	body {
		width:3000px;
		
	}
	
	.ending {
		border-style:solid;
	}
	
	.paths {
		display:none;
	}
	
	.children, .parents {
		display:none;
	}
	
	.video-row {
		margin-bottom:150px;
	}
	
	</style>
	
</body>
<!-- https://www.anychart.com/blog/2020/07/22/network-graph-javascript/ -->