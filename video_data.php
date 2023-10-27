<?php

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
		$this->paths=array_merge($this->paths, $new_paths);
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
			$total_prob += 
				(2/3)**$L_count *
				(1/3)**$W_count *
				(1/3)**$R_count *
				(1/3)**$P_count *
				(1/3)**$S_count *
				(1/2)**$E_count *
				(1/2)**$B_count;
		}
		return($total_prob);
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
	["dU22iL1ZsWQ"], child_path:["-"]);
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
	["dU22iL1ZsWQ"], child_path:["-"]);
$GLOBALS["data"]["hhDh6_RD7tU"] = new video(
	["dU22iL1ZsWQ"], child_path:["-"]);
$GLOBALS["data"]["87zN8iWo5pU"] = new video(
	["s3rUNS68AKs","K1kVsxsnYyc"], child_path:["E","B"]);
$GLOBALS["data"]["s3rUNS68AKs"] = new video(
	[], ending:True); // END
$GLOBALS["data"]["K1kVsxsnYyc"] = new video(
	[], ending:True); // BILLION
$GLOBALS["data"]["dU22iL1ZsWQ"] = new video(
	[], ending:True); // BILLION
$GLOBALS["data"]["_mrAeT9kpPM"] = new video(
	["r9-jSTCiHd0","hoaLwPc571E"]);
$GLOBALS["data"]["r9-jSTCiHd0"] = new video(
	["0odtRIBvjes","RVLUX6BUEJI"]);
$GLOBALS["data"]["hoaLwPc571E"] = new video(
	["qYaLoO40kjM","z8zjvT8Qx8U"]);
$GLOBALS["data"]["qYaLoO40kjM"] = new video(
	[]);
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

?>