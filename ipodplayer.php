<?php
$sonosapi = "http://192.168.1.38:5005";
$roomname = "Office"; //Default room
?>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<link rel="icon" type="image/png" href="favicon.png">
	<link rel="apple-touch-icon" href="favicon.png">
	<script>
		var sonosapi = "http://192.168.1.38:5005";
		var roomname = "Office";
		var speaker_location = GetParameterValues('location');

		window.onload = (event) => {
			if (!speaker_location) {
				speaker_location = roomname;
			}
			document.getElementById("app-title").innerHTML = speaker_location;
		}

		function setlocation() {
            var myLocation =  document.getElementById("locationdropdown");
			speaker_location = locationdropdown.options[locationdropdown.selectedIndex].value;
			document.getElementById("app-title").innerHTML = speaker_location;
		}

		function GetParameterValues(param) {

			var url = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
			for (var i = 0; i < url.length; i++) {
				var urlparam = url[i].split('=');
				if (urlparam[0] == param) {
					return urlparam[1];
				}
			}
		}

		function command(cmd, el) {
			'use strict()';


			var data = "hello";
			$.get(sonosapi + "/" + speaker_location + "/" + cmd, function(response) {
				data = response;
			});

			return data;
		}
	</script>
	<style>
        * {cursor: pointer;}
    </style>
</head>

<body>
	<div>
		<select onchange="setlocation()" id="locationdropdown">
        <?php

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $sonosapi . "/zones");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($curl);
            if (curl_error($curl)) {
                die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
            }

            curl_close($curl);
            $zones = json_decode($result);
            foreach ($zones as $value) {

                echo "<option value=\"" . $value->coordinator->roomName . "\">" . $value->coordinator->roomName . "</option>";
            }
        ?>
        </select>
		

		<main class="mdl-layout__content">

			<?php

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $sonosapi . "/" . $roomname . "/favorites/detailed");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

			$result = curl_exec($curl);
			if (curl_error($curl)) {
				die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
			}

			curl_close($curl);
			$json = json_decode($result);
			

			foreach ($json as $value) {
				if (isset($value->albumArtUri)) {
                    echo "<div style='line-height:86px; '>";
                    echo "<span style='display: inline-block; vertical-align:middle; line-height: normal;'>";
                    echo "<a style='height:60px' onclick=\"command('favorite/" . $value->title . "', this);\">";
                    echo "<img style='width:60px' src=\"" . $value->albumArtUri . "\"/>";
                    echo "</a>";
					$response = new SimpleXMLElement($value->metadata);
                    echo "<span style='padding-left: 8px; font-size: 18px; height:80px; display: inline-block; vertical-align:middle;'>";
                    echo "<a href='' style='height:60px' onclick=\"command('favorite/" . $value->title . "', this);\">";
                    echo $response->item[0]->children('dc', true)->title;
                    echo "</a></span>";
					echo "</span>";
                    
                    echo "</div>";
				}
			}	
			?>

			<div>
                <button style="height: 50px; width: 50px;" onclick="command('previous');">
					PREV
				</button>
				<button style="height: 50px; width: 80px;" onclick="command('playpause');">
                    PLAY/PAUSE				
                </button>
				
				<button style="height: 50px; width: 50px;" onclick="command('next');">
                    NEXT
				</button>
			</div>


	</div>
	</main>

	</div>
</body>

</html>