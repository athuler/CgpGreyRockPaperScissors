<?php
require("secrets.php");

class video {
	public $paths = [];
	#public $children;
	#public $child_path;
	#public $child_prob;
	#public $probabity = 0;
	public $views = 1;
	public $parents = [];
	#public $child_path;
	#public $ending;
	
	public function __construct(
		public $children = [],
		public $child_path = ["L", "W"],
		public $ending = False
	) {
		$this->children = $children;
		$this->child_path = $child_path;
		#$this->child_prob = $child_prob;
		$this->ending = $ending; 
	}
	
	function set_views($views = 1) {
		$this->views = $views;
	}
	
	function add_path($new_paths) {
		$this->paths = array_unique(array_merge($this->paths, $new_paths));
		sort($this->paths);
	}
	
	function add_parent($new_parent) {
		array_push($this->parents, $new_parent);
		$this->parents = array_unique($this->parents);
	}
	
	function probability() {
		$total_prob = 0;
		foreach($this->paths as $path) {
			$L_count = substr_count($path, 'L'); // Lose
			$W_count = substr_count($path, 'W'); // Win
			$R_count = substr_count($path, 'R'); // Rock
			$P_count = substr_count($path, 'P'); // Paper
			$S_count = substr_count($path, 'S'); // Scissors
			$E_count = substr_count($path, 'E'); // End
			$B_count = substr_count($path, 'B'); // To A Billion
			$T_count = substr_count($path, 'T'); // To A Trillion
			$total_prob += 
				(2/3)**$L_count *
				(1/3)**$W_count *
				(1/3)**$R_count *
				(1/3)**$P_count *
				(1/3)**$S_count *
				(1/2)**$E_count *
				(1/2)**$B_count *
				(1/2)**$T_count;
		}
		return($total_prob);
	}
	
	
	function alignment() {
		$align = 0;
		$inc_win = 200;
		$inc_lose = 275;
		$path = str_split(end($this->paths));
		//echo($path);
		
		foreach($path as $letter) {
			//print("Letter-" . $letter);
			switch($letter) {
				case "W":
					$align = $align + -1 * $inc_win;
					break;
				case "L":
					$align = $align + $inc_lose;
					break;
				default:
					$align = $align;
			}
		}
		return($align);
	}
	
}
# CHILDREN: [LOSE, WIN]
$GLOBALS["data"] = [];
$GLOBALS["data"]["PmWQmZXYd74"] = new video(
	["CPb168NUwGc", "Ul8r0Thgx44"]);
$GLOBALS["data"]["PmWQmZXYd74"]->add_path(["-"]);
$GLOBALS["data"]["CPb168NUwGc"] = new video(
	["jDQqv3zkbIQ", "RVLUX6BUEJI"]);
$GLOBALS["data"]["jDQqv3zkbIQ"] = new video(
	["HXtheRKAkIw", "RVLUX6BUEJI"]);
$GLOBALS["data"]["HXtheRKAkIw"] = new video(
	["3qoxLsQ9464","b41_jrE8jFw"]);
$GLOBALS["data"]["3qoxLsQ9464"] = new video(
	["xjo-L59q8K4","b41_jrE8jFw"]);
$GLOBALS["data"]["xjo-L59q8K4"] = new video(
	["dzK444eg53c","b41_jrE8jFw"]);
$GLOBALS["data"]["dzK444eg53c"] = new video(
	["83hQScodfDA","LLZJ-U1UB5M"]);
$GLOBALS["data"]["LLZJ-U1UB5M"] = new video(ending: True); // END
$GLOBALS["data"]["83hQScodfDA"] = new video(
	["TFlsl2ZkBlI","j-jqX7AdQT8"]);
$GLOBALS["data"]["TFlsl2ZkBlI"] = new video(
	["4ojQK570hDA","j-jqX7AdQT8"]);
$GLOBALS["data"]["4ojQK570hDA"] = new video(
	["WQ9wBn2Qk14","j-jqX7AdQT8"]);
$GLOBALS["data"]["WQ9wBn2Qk14"] = new video(
	["hT-25A8LFAE","j-jqX7AdQT8"]);
$GLOBALS["data"]["hT-25A8LFAE"] = new video(
	["e8zbuI-qJX4","j-jqX7AdQT8"]);
$GLOBALS["data"]["e8zbuI-qJX4"] = new video(
	["xCb7UVssqlY","j-jqX7AdQT8"]);
$GLOBALS["data"]["xCb7UVssqlY"] = new video(
	["54ZevZGGXZw","j-jqX7AdQT8"]);
$GLOBALS["data"]["54ZevZGGXZw"] = new video(
	["d84UbmiyBOs","j-jqX7AdQT8"]);
$GLOBALS["data"]["d84UbmiyBOs"] = new video(
	["X9jKHujmt1M","j-jqX7AdQT8"]);
$GLOBALS["data"]["X9jKHujmt1M"] = new video(
	["SeX6WzVRZ4Y","j-jqX7AdQT8"]);
$GLOBALS["data"]["SeX6WzVRZ4Y"] = new video(
	["j8fHcBHeKwk","j-jqX7AdQT8"]);
$GLOBALS["data"]["j-jqX7AdQT8"] = new video(
	["LvcxrEP2U-o", "oOufgnObuhQ"]);
$GLOBALS["data"]["LvcxrEP2U-o"] = new video(
	["dU22iL1ZsWQ"], child_path:["C"]);
$GLOBALS["data"]["dU22iL1ZsWQ"] = new video(
	[], ending: True); // END
$GLOBALS["data"]["j8fHcBHeKwk"] = new video(
	["VtWv7m270kY","b6_QOYNf73g"]);
$GLOBALS["data"]["VtWv7m270kY"] = new video(
	["LSHMwceP0X8","b6_QOYNf73g"]);
$GLOBALS["data"]["LSHMwceP0X8"] = new video(
	["BvL-kq_LLsI","b6_QOYNf73g"]);
$GLOBALS["data"]["BvL-kq_LLsI"] = new video(
	["KIcQP_OL0-0","b6_QOYNf73g"]);
$GLOBALS["data"]["KIcQP_OL0-0"] = new video(
	["ei5WZihztGk","b6_QOYNf73g"]);
$GLOBALS["data"]["ei5WZihztGk"] = new video(
	["74E6BTyhv_c","b6_QOYNf73g"]);
$GLOBALS["data"]["74E6BTyhv_c"] = new video(
	["YnACGEG1tTc","b6_QOYNf73g"]);
$GLOBALS["data"]["YnACGEG1tTc"] = new video(
	["7Jp8Xp_9v90","b6_QOYNf73g"]);
$GLOBALS["data"]["7Jp8Xp_9v90"] = new video(
	["ILrJDLjx6sA","b6_QOYNf73g"]);
$GLOBALS["data"]["ILrJDLjx6sA"] = new video(
	["sJXuw8QM0W4","b6_QOYNf73g"]);
$GLOBALS["data"]["sJXuw8QM0W4"] = new video(
	["Gh_preEUg74","b6_QOYNf73g"]);
$GLOBALS["data"]["Gh_preEUg74"] = new video(
	["YVJh73INvXk","b6_QOYNf73g"]);
$GLOBALS["data"]["YVJh73INvXk"] = new video(
	["9HDYmP-l_oM","b6_QOYNf73g"]);
$GLOBALS["data"]["9HDYmP-l_oM"] = new video(
	["ugkWE2cy370","b6_QOYNf73g"]);
$GLOBALS["data"]["ugkWE2cy370"] = new video(
	["F-j5y5dyPDo","b6_QOYNf73g"]);
$GLOBALS["data"]["F-j5y5dyPDo"] = new video(
	["tlTOyDEZGUU","b6_QOYNf73g"]);
$GLOBALS["data"]["b6_QOYNf73g"] = new video(
	["LvcxrEP2U-o", "oOufgnObuhQ"]);
$GLOBALS["data"]["tlTOyDEZGUU"] = new video(
	["GG6AZGhLCS4","wUjs_vVwh68"]);
$GLOBALS["data"]["GG6AZGhLCS4"] = new video(
	[], ending:True); // END
$GLOBALS["data"]["wUjs_vVwh68"] = new video(
	[], ending:True); // END
$GLOBALS["data"]["RVLUX6BUEJI"] = new video(
	["0odtRIBvjes", "t0hJIw19ChI"]);
$GLOBALS["data"]["t0hJIw19ChI"] = new video(
	["0odtRIBvjes", "e6zLBO0vez8"]);
$GLOBALS["data"]["e6zLBO0vez8"] = new video(
	["0odtRIBvjes", "WR7AbrjBZNI"]);
$GLOBALS["data"]["WR7AbrjBZNI"] = new video(
	["0odtRIBvjes", "fMyFx3bFW-s"]);
$GLOBALS["data"]["fMyFx3bFW-s"] = new video(
	["0odtRIBvjes", "hi4166mPpmA"]);
$GLOBALS["data"]["hi4166mPpmA"] = new video(
	["7VmxQumJAL4", "SxWZDOgaIog"]);
$GLOBALS["data"]["SxWZDOgaIog"] = new video(
	["7VmxQumJAL4", "-D_g1k0IzTQ"]);
$GLOBALS["data"]["-D_g1k0IzTQ"] = new video(
	["7VmxQumJAL4", "AnsaswKGPHk"]);
$GLOBALS["data"]["AnsaswKGPHk"] = new video(
	["7VmxQumJAL4", "8UKflLZq61E"]);
$GLOBALS["data"]["8UKflLZq61E"] = new video(
	["7VmxQumJAL4", "tAcIxmJOA9o"]);
$GLOBALS["data"]["tAcIxmJOA9o"] = new video(
	["QxC-EQAsTuM", "yQKjsA90kpc","_WKzx6tClQw"], child_path:["R","P","S"]);
$GLOBALS["data"]["QxC-EQAsTuM"] = new video(
	["LvcxrEP2U-o", "b41_jrE8jFw"]);
$GLOBALS["data"]["_WKzx6tClQw"] = new video(
	["LvcxrEP2U-o", "b41_jrE8jFw"]);
$GLOBALS["data"]["yQKjsA90kpc"] = new video(
	["s3rUNS68AKs","K1kVsxsnYyc"], child_path:["E", "B"]);
$GLOBALS["data"]["7VmxQumJAL4"] = new video(
	["LvcxrEP2U-o","b41_jrE8jFw"]);
$GLOBALS["data"]["Ul8r0Thgx44"] = new video(
	["fWOtjGJvlGI", "_mrAeT9kpPM"]);
$GLOBALS["data"]["fWOtjGJvlGI"] = new video(
	["0odtRIBvjes","RVLUX6BUEJI"]);
$GLOBALS["data"]["0odtRIBvjes"] = new video(
	["LvcxrEP2U-o","b41_jrE8jFw"]);
$GLOBALS["data"]["b41_jrE8jFw"] = new video(
	["LvcxrEP2U-o","oOufgnObuhQ"]);
$GLOBALS["data"]["oOufgnObuhQ"] = new video(
	["LvcxrEP2U-o","N7UCPssq-X8"]);
$GLOBALS["data"]["N7UCPssq-X8"] = new video(
	["LvcxrEP2U-o","FlwMxN9-mec"]);
$GLOBALS["data"]["FlwMxN9-mec"] = new video(
	["LvcxrEP2U-o","ghJAsm9W3k0"]);
$GLOBALS["data"]["ghJAsm9W3k0"] = new video(
	["LvcxrEP2U-o","55nbeaYL7hQ"]);
$GLOBALS["data"]["55nbeaYL7hQ"] = new video(
	["LvcxrEP2U-o","dB8-XaRclhk"]);
$GLOBALS["data"]["dB8-XaRclhk"] = new video(
	["LvcxrEP2U-o","ddWvzSxz4AA"]);
$GLOBALS["data"]["ddWvzSxz4AA"] = new video(
	["LvcxrEP2U-o","0xFOAtGBdUg"]);
$GLOBALS["data"]["0xFOAtGBdUg"] = new video(
	["LvcxrEP2U-o","HSdwcDFDyQY"]);
$GLOBALS["data"]["HSdwcDFDyQY"] = new video(
	["LvcxrEP2U-o","Q5kgEN3rb_c"]);
$GLOBALS["data"]["Q5kgEN3rb_c"] = new video(
	["LvcxrEP2U-o","pteggMrRnk4"]);
$GLOBALS["data"]["pteggMrRnk4"] = new video(
	["D8iP2qINaSE","hhDh6_RD7tU","87zN8iWo5pU"],child_path:["R","P","S"]);
$GLOBALS["data"]["D8iP2qINaSE"] = new video(
	["dU22iL1ZsWQ"], child_path:["C"]);
$GLOBALS["data"]["hhDh6_RD7tU"] = new video(
	["dU22iL1ZsWQ"], child_path:["C"]);
$GLOBALS["data"]["87zN8iWo5pU"] = new video(
	["s3rUNS68AKs","K1kVsxsnYyc"], child_path:["E","B"]);
$GLOBALS["data"]["s3rUNS68AKs"] = new video(
	[], ending:True); // END
$GLOBALS["data"]["K1kVsxsnYyc"] = new video(
	["AgHpWh77STQ","wf6sqW38AmM"], ending:False); // BILLION
$GLOBALS["data"]["wf6sqW38AmM"] = new video(
	["AgHpWh77STQ","j92TH0iaCrE"]);
$GLOBALS["data"]["j92TH0iaCrE"] = new video(
	["AgHpWh77STQ","r8LgYG67bCA"]);
$GLOBALS["data"]["r8LgYG67bCA"] = new video(
	["AgHpWh77STQ","DZLnWKM90nQ"]);
$GLOBALS["data"]["DZLnWKM90nQ"] = new video(
	["AgHpWh77STQ","aSjsXUdaIgQ"]);
$GLOBALS["data"]["aSjsXUdaIgQ"] = new video(
	["AgHpWh77STQ","HunlKDzXNv0"]);
$GLOBALS["data"]["HunlKDzXNv0"] = new video(
	["s3rUNS68AKs","gv7_NTC_Rgs"], child_path:["E","T"]);
$GLOBALS["data"]["gv7_NTC_Rgs"] = new video(
	["d0R5Csv7ogU","sbgMHxUkfFI"]);
$GLOBALS["data"]["sbgMHxUkfFI"] = new video(
	["d0R5Csv7ogU","4Nk29OAqZTw"]);
$GLOBALS["data"]["4Nk29OAqZTw"] = new video(
	["d0R5Csv7ogU","OjHzloSmLZg"]);
$GLOBALS["data"]["OjHzloSmLZg"] = new video(
	["d0R5Csv7ogU","I32ZGazBqWY"]);
$GLOBALS["data"]["I32ZGazBqWY"] = new video(
	["d0R5Csv7ogU","-bGMZAWuL1o"]);
$GLOBALS["data"]["-bGMZAWuL1o"] = new video(
	["d0R5Csv7ogU","7GEmEWf1KgY"]);
$GLOBALS["data"]["7GEmEWf1KgY"] = new video(
	["d0R5Csv7ogU","4wUukNXczpM"]);
$GLOBALS["data"]["4wUukNXczpM"] = new video(
	[], ending:True); // END
$GLOBALS["data"]["d0R5Csv7ogU"] = new video(
	[], ending:True); // END
$GLOBALS["data"]["AgHpWh77STQ"] = new video(
	[], ending:True); // END
$GLOBALS["data"]["dU22iL1ZsWQ"] = new video(
	[], ending:True); // END
$GLOBALS["data"]["_mrAeT9kpPM"] = new video(
	["r9-jSTCiHd0","hoaLwPc571E"]);
$GLOBALS["data"]["r9-jSTCiHd0"] = new video(
	["0odtRIBvjes","RVLUX6BUEJI"]);
$GLOBALS["data"]["hoaLwPc571E"] = new video(
	["qYaLoO40kjM","z8zjvT8Qx8U"]);
$GLOBALS["data"]["qYaLoO40kjM"] = new video(
	["0odtRIBvjes", "RVLUX6BUEJI"]);
$GLOBALS["data"]["z8zjvT8Qx8U"] = new video(
	["qYaLoO40kjM","uHoYnV9JX4w"]);
$GLOBALS["data"]["uHoYnV9JX4w"] = new video(
	["qYaLoO40kjM","jRDLtKUsq8U"]);
$GLOBALS["data"]["jRDLtKUsq8U"] = new video(
	["qYaLoO40kjM","RXy0Kc1Cl9s"]);
$GLOBALS["data"]["RXy0Kc1Cl9s"] = new video(
	["yCwdjfzxI4I","v3oXQrWu-PA"]);
$GLOBALS["data"]["v3oXQrWu-PA"] = new video(
	["yCwdjfzxI4I","bN5M3caw6b8"]);
$GLOBALS["data"]["bN5M3caw6b8"] = new video(
	["yCwdjfzxI4I","DDmnplXv6pY"]);
$GLOBALS["data"]["DDmnplXv6pY"] = new video(
	["yCwdjfzxI4I","6auFOPOuHuE"]);
$GLOBALS["data"]["6auFOPOuHuE"] = new video(
	["yCwdjfzxI4I","0M39bd9euEI"]);
$GLOBALS["data"]["0M39bd9euEI"] = new video(
	["E3pdr5hNBe4","YohvsF9mF3g","AGL2OMZzn2g"], child_path:["R","P","S"]);
$GLOBALS["data"]["E3pdr5hNBe4"] = new video(
	["s3rUNS68AKs","K1kVsxsnYyc"], child_path:["E","B"]);
$GLOBALS["data"]["YohvsF9mF3g"] = new video(
	["0odtRIBvjes","RVLUX6BUEJI"]);
$GLOBALS["data"]["AGL2OMZzn2g"] = new video(
	["0odtRIBvjes","RVLUX6BUEJI"]);


$GLOBALS["data"]["yCwdjfzxI4I"] = new video(
	["0odtRIBvjes","RVLUX6BUEJI"]);


/**
 * Fetch and cache video views from YouTube API
 * @param int $cache_duration_minutes How long to cache the data (in minutes)
 * @return array Returns array with keys: 'curl_error', 'curl_success_message', 'date_last_query'
 */
function fetch_video_views($cache_duration_minutes = 10) {
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

	if($time_difference_minutes >= $cache_duration_minutes) {
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

	return [
		'curl_error' => $curl_error,
		'curl_success_message' => $curl_success_message,
		'date_last_query' => $date_last_query
	];
}

/**
 * Build parent relationships and paths for all videos using BFS traversal
 * @param string $first_vid The ID of the first video to start from
 */
function build_parents_and_paths($first_vid) {
	###### Build Parents #####
	foreach($GLOBALS["data"] as $video_id => $video) {
		$video = $GLOBALS["data"][$video_id];
		$num_children = count($video->children);

		// For each child of this video
		for($j = 0; $j < $num_children; $j ++) {
			$child_id = $video->children[$j];

			// Add Video as Parent to its children
			$GLOBALS["data"][$child_id]->add_parent($video_id);
		}
	}

	###### Build Paths #####
	// Use BFS to process nodes in topological order (parents before children)
	$queue = [$first_vid];
	$processed = [];

	while (count($queue) > 0) {
		$video_id = array_shift($queue);

		// Skip if already processed
		if (in_array($video_id, $processed)) {
			continue;
		}

		// Check if parents are processed
		if (count($GLOBALS["data"][$video_id]->parents) > 0) {
			$all_parents_processed = true;
			foreach ($GLOBALS["data"][$video_id]->parents as $parent_id) {
				if (!in_array($parent_id, $processed)) {
					$all_parents_processed = false;
					break;
				}
			}
			if (!$all_parents_processed) {
				// Re-enqueue for later processing
				array_push($queue, $video_id);
				continue;
			}
		}

		// Process current video
		$video = $GLOBALS["data"][$video_id];
		$num_children = count($video->children);

		for($j = 0; $j < $num_children; $j ++) {
			$child_id = $video->children[$j];

			// Add New Path(s)
			$new_paths = [];
			foreach($video->paths as $current_path) {
				array_push($new_paths,
					$current_path . $video->child_path[$j]
				);
			}
			$new_paths = array_unique($new_paths);
			sort($new_paths);
			$GLOBALS["data"][$child_id]->add_path($new_paths);

			// Add child to queue for processing
			if (!in_array($child_id, $queue) && !in_array($child_id, $processed)) {
				array_push($queue, $child_id);
			}
		}

		// Mark this node as processed
		array_push($processed, $video_id);
	}
}

?>