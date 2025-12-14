<?php
// Error Handling
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Configuration
$CACHE_DURATION_MINUTES = 10; // How long to cache video view data (in minutes)

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
	file_put_contents($last_query_file, serialize(new DateTime()));
}

if(!file_exists($views_cache_file)) {
	file_put_contents($views_cache_file, serialize([]));
}

$date_last_query = unserialize(file_get_contents($last_query_file));

//Check time since last query
$time_difference_minutes = $date_last_query->diff(new DateTime)->i;

$curl_error = null;
$curl_success_message = null;

if($time_difference_minutes >= $CACHE_DURATION_MINUTES) {
	// Process videos in batches of 50
	$all_ids = array_chunk(
				array_keys($GLOBALS["data"]),
				50);

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$all_responses = [];
	$curl_success = true;

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

		$result = curl_exec($curl);

		// Check for cURL errors
		if($result === false) {
			$curl_error = curl_error($curl);
			$curl_success = false;
			break;
		}

		$response = json_decode($result, true);

		// Check for JSON decode errors or API errors
		if($response === null || !array_key_exists("items", $response)) {
			$curl_error = "API returned invalid response or error";
			$curl_success = false;
			break;
		}

		$all_responses = array_merge($all_responses, $response["items"]);
	}

	// Only update cache if API call was successful
	if($curl_success && count($all_responses) > 0) {
		$date_last_query = new DateTime();
		file_put_contents($views_cache_file, serialize($all_responses));
		file_put_contents($last_query_file, serialize($date_last_query));
		$curl_success_message = "Successfully fetched data from YouTube API.";
	} else {
		// Fall back to cached data
		$all_responses = unserialize(file_get_contents($views_cache_file));
	}
} else {
	// Get Data from file
	$all_responses = unserialize(file_get_contents($views_cache_file));
}

// Save video views to tree
foreach($all_responses as $vid) {
	$GLOBALS["data"][$vid["id"]]->set_views($vid["statistics"]["viewCount"]);
}

// Compute total video views
$total_views = 0;
foreach($GLOBALS["data"] as $vid){
	$total_views += $vid->views;
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>CGP Grey: Rock Paper Scissors - A Statistical Analysis</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, viewport-fit=cover">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
	<link rel="stylesheet" href="cytoscape-styles.css">

	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-J4H5B1ENEE"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', 'G-J4H5B1ENEE');
	</script>

	<script src="cytoscape.min.js"></script>
</head>
<body>
	<div id="header">
		<h1>CGP Grey: Rock Paper Scissors</h1>
		<h2>Interactive Network Visualization</h2>
	</div>

	<div id="breadcrumb">
		<div>
			<span><?=count(array_keys($GLOBALS["data"]))?></span> videos |
			<span><?=number_format($total_views)?></span> total views |
			Click any node to explore
		</div>
		<div id="last-updated">
			Updated <?php
				$now = new DateTime();
				$diff = $date_last_query->diff($now);
				if ($diff->days > 0) {
					echo $diff->days . ' day' . ($diff->days > 1 ? 's' : '') . ' ago';
				} elseif ($diff->h > 0) {
					echo $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
				} elseif ($diff->i > 0) {
					echo $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
				} else {
					echo 'Just now';
				}
			?>
		</div>
	</div>

	<div id="controls">
		<div class="btn-group">
			<button class="control-btn" onclick="resetView(window.cy, window.firstVideoId)"><i class="bi bi-arrows-angle-contract"></i> Reset View</button>
			<button class="control-btn" onclick="fitToScreen(window.cy)"><i class="bi bi-fullscreen"></i> Fit All</button>
			<button class="control-btn" onclick="centerOnStart(window.firstVideoId, window.cy)"><i class="bi bi-house"></i> Go to Start</button>
		</div>

		<div class="legend">
			<div class="legend-item">
				<div class="legend-color" style="background: #22c55e;"></div>
				<span>Win</span>
			</div>
			<div class="legend-item">
				<div class="legend-color" style="background: #ef4444;"></div>
				<span>Lose</span>
			</div>
			<div class="legend-item">
				<div class="legend-color" style="background: #666;"></div>
				<span>Other</span>
			</div>
			<div class="legend-item">
				<div class="legend-color" style="background: #fbbf24; width: 30px; height: 6px;"></div>
				<span>Ending</span>
			</div>
		</div>

		<div class="btn-group">
			<button class="control-btn" onclick="changeLayout('breadthfirst', window.cy, window.firstVideoId)">Tree Layout</button>
			<button class="control-btn" onclick="changeLayout('cose', window.cy, window.firstVideoId)">Force Layout</button>
			<button class="control-btn" onclick="changeLayout('circle', window.cy, window.firstVideoId)">Circle Layout</button>
		</div>
	</div>

	<div id="cy"></div>

	<div id="info-panel">
		<span class="close-btn" onclick="closeInfoPanel()">&times;</span>
		<h3 id="info-video-id">Video Info</h3>
		<div id="info-content"></div>
	</div>

	<div id="footer">
		<p>Made by <a href="https://github.com/athuler" target="_blank">athuler</a> |
		<a href="https://github.com/athuler/CgpGreyRockPaperScissors/issues" target="_blank">Report Bug</a> |
		<a href="https://github.com/athuler/CgpGreyRockPaperScissors" target="_blank">Source</a> |
		<a href="https://github.com/sponsors/athuler" target="_blank">
			<img src="https://img.shields.io/static/v1?label=Sponsor&message=%E2%9D%A4&logo=GitHub&color=%23fe8e86" style="vertical-align: middle;"/>
		</a>
		</p>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
	<script src="cytoscape-graph.js"></script>

	<?php if($curl_error !== null): ?>
	<script>
		console.warn('YouTube API fetch failed: <?=addslashes($curl_error)?>. Using cached data instead.');
	</script>
	<?php endif; ?>

	<?php if($curl_success_message !== null): ?>
	<script>
		console.log('<?=addslashes($curl_success_message)?>');
	</script>
	<?php endif; ?>

	<script>
		// Prepare graph data from PHP
		const videoData = <?php echo json_encode($GLOBALS["data"]); ?>;
		const firstVideoId = "<?=$first_vid?>";
		const firstVideoViews = <?=$GLOBALS["data"][$first_vid]->views?>;

		// Make available globally for onclick handlers
		window.firstVideoId = firstVideoId;
		window.firstVideoViews = firstVideoViews;

		// Initialize graph
		const cy = initializeGraph(videoData, firstVideoId, firstVideoViews);
		window.cy = cy;

		// Node click handler
		cy.on('tap', 'node', function(evt) {
			const node = evt.target;
			showInfoPanel(node, firstVideoViews);
			highlightNode(node, cy);
		});

		// Click on background to deselect
		cy.on('tap', function(evt) {
			if (evt.target === cy) {
				closeInfoPanel();
				clearHighlights(cy);
			}
		});

		// Global function for navigating to nodes (called from info panel)
		window.navigateToNode = function(nodeId) {
			navigateToNode(nodeId, cy);
		};

		// Initial center on start node
		setTimeout(() => {
			cy.center(cy.$(`#${firstVideoId}`));
		}, 100);
	</script>
</body>
</html>
