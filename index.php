<?php
// Error Handling
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Load Secrets
require_once("secrets.php");

###### Create Video Tree #####
require_once("video_data.php");


?>

<!DOCTYPE html>
<html>
<head>
	<title>CGP Grey: Rock Paper Scissors - A Statistical Analysis</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
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
			
			<!-- Statistics -->
			
			<p>
				<b><?=count(array_keys($GLOBALS["data"]))?></b> videos<br/>
				<b><?php
					// Compute total video views
					$total_views = 0;
					foreach($GLOBALS["data"] as $vid){
						$total_views += $vid->views;
					}
					echo(number_format($total_views));
				?></b> total views
			</p>
			
			<!-- Add Tip on Using Arrows -->
			<div class="row">
				<p><i>Tip: Use <a href="#PmWQmZXYd74"><i class="bi bi-caret-down" style="font-size: 35px; color: red;"></i></a> and <a href="#PmWQmZXYd74"><i class="bi bi-caret-down" style="font-size: 35px; color: green;"></i></a> to navigate more easily!</i></p>
			</div>
			
			<!-- Visual Controls -->
			<input type="button" class="btn btn-secondary" value="Show/Hide Children & Parents" onclick="toggle_family()"/>
			<input type="button" class="btn btn-secondary" value="Show/Hide All Paths" onclick="toggle_paths()"/>
			
			
			<!-- About -->
			<br/><br/>
			<p>Made by <a href="https://github.com/athuler" target="__blank">athuler</a> with much blood, sweats, and so many tears.</p>
			<div>
				<a href="https://github.com/athuler/CgpGreyRockPaperScissors" target="__blank" class="col-auto">Source</a> |
				<a href="download.php">Download Data</a> |
				<a href="https://github.com/athuler/CgpGreyRockPaperScissors/issues" target="__blank">Report a Bug</a> |
				<a href="https://github.com/sponsors/athuler" target="__blank" class="col-auto"><img src="https://img.shields.io/static/v1?label=Sponsor&message=%E2%9D%A4&logo=GitHub&color=%23fe8e86"/></a>
			</div>
		</div>
		
	</div>
	
	<!-- Connectors Between Blocks -->
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
		
		<div
			class="video <?php if($video->ending){echo("ending");}?>"
			id="<?=$vid_id?>"
			style="<?php
						if($video->alignment() > 0)
						{
							?>margin-right:<?php echo($video->alignment());
						}
						else
						{
							?>margin-left:<?php echo($video->alignment()*-1);
						}
					?>px;"
		>
			<b><?=$vid_id?></b> <a href="https://youtube.com/watch?v=<?=$vid_id?>" target="__blank"><svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" fill="currentColor" class="bi bi-box-arrow-up-right" viewBox="0 0 16 16">
	<path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/>
	<path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/>
</svg></a><br/>
			
			<p class="paths"><u>Paths</u>:<?=implode(", ",$video->paths)?><br/></p>
			
			<u>Actually</u>: <?=number_format($video->views/$GLOBALS["data"][$first_vid]->views, 5)?> /
			<?=number_format($video->views)?> views<br/>
			
			<u>Predicted</u>: <?=number_format($video->probability(),5)?> /
			<?=number_format($video->probability() * $GLOBALS["data"][$first_vid]->views)?> views<br/>
			
			<p class="children"><u>Children</u>:<br/><?=implode("<br/>",$video->children)?></p>
			
			<p class="parents"><br/><u>Parents</u>:<br/><?=implode("<br/>",$video->parents)?></p>
			
			<?php if($video->ending){?>
			<br/><h3>END</h3>
			<?php } ?>
			<br/>
			<!-- Connector for path trace -->
			<?php if(
					count($video->children) == 2 and
					$video->child_path[0] == "L" and
					$video->child_path[1] == "W"
					) { ?>
			
			
			
			
			<div class="row">
				<div class="offset-3 col-3" id="<?=$vid_id?>-L" style="">
					Lose<br/>
					<a href="#<?=$video->children[0]?>">
						<i class="bi bi-caret-down" style="font-size: 35px; color: red;"></i>
					</a>
				</div>
				<div class="col-3" id="<?=$vid_id?>-W" style="">
					Win<br/>
					<a href="#<?=$video->children[1]?>">
						<i class="bi bi-caret-down" style="font-size: 35px; color: green;"></i>	
					</a>
				</div>
			</div>
			<?php } else if(
					count($video->children) == 2 and
					$video->child_path[0] == "E" and
					$video->child_path[1] == "B"
					) { ?>
			<div class="row">
				<div class="offset-3 col-3"	id="<?=$vid_id?>-E">
					End<br/>
					<a href="#<?=$video->children[0]?>">
						<i class="bi bi-caret-down" style="font-size: 35px; color: grey;"></i>
					</a>
				</div>
				<div class="col-3"	id="<?=$vid_id?>-B">
					Billion!<br/>
					<a href="#<?=$video->children[1]?>">
						<i class="bi bi-caret-down" style="font-size: 35px; color: grey;"></i>
					</a>
				</div>
			</div>
			<?php } else if(
					count($video->children) == 2 and
					$video->child_path[0] == "E" and
					$video->child_path[1] == "T"
					) { ?>
			<div class="row">
				<div class="offset-3 col-3"	id="<?=$vid_id?>-E">
					End<br/>
					<a href="#<?=$video->children[0]?>">
						<i class="bi bi-caret-down" style="font-size: 35px; color: grey;"></i>
					</a>
				</div>
				<div class="col-3"	id="<?=$vid_id?>-T">
					Trillion!!<br/>
					<a href="#<?=$video->children[1]?>">
						<i class="bi bi-caret-down" style="font-size: 35px; color: grey;"></i>
					</a>
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
					Rock<br/>
					<a href="#<?=$video->children[0]?>">
						<i class="bi bi-caret-down" style="font-size: 35px; color: grey;"></i>
					</a>
				</div>
				<div class="col-4"	id="<?=$vid_id?>-P">
					Paper<br/>
					<a href="#<?=$video->children[1]?>">
						<i class="bi bi-caret-down" style="font-size: 35px; color: grey;"></i>
					</a>
				</div>
				<div class="col-4"	id="<?=$vid_id?>-S">
					Scissors<br/>
					<a href="#<?=$video->children[2]?>">
						<i class="bi bi-caret-down" style="font-size: 35px; color: grey;"></i>
					</a>
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
		
		const maxWidth = document.body.scrollWidth - window.innerWidth;
		console.log("% page " + (window.pageXOffset * 100) / maxWidth);
		
		//console.log("Rect x: " + rect.x);
		console.log("Rect Left: " + rect.left);
		console.log("Rect Right: " + rect.right);
		console.log("Scroll Width: " + document.body.scrollWidth);
		console.log("Screen Width: " + screen.width);
		//var scrollHorizDist = window.scrollX + rect.left - (rect.left + rect.right)/2;
		//var scrollHorizDist = window.scrollX + rect.left - rect.left;
		var scrollHorizDist = (document.body.scrollWidth - window.innerWidth) / 2;
		
		console.log(scrollHorizDist);
		window.scroll(scrollHorizDist,0); 
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
		margin-right:0px;
		padding:10px;
		font-size:16px;
	}
	
	body {
		width:30000px;
		
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
		margin-top:50px;
		margin-bottom:250px;
	}
	
	
	</style>
	
</body>
<!-- https://www.anychart.com/blog/2020/07/22/network-graph-javascript/ -->