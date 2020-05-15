<?php
$sonosapi = "192.168.1.38:5005";
$roomname = "Office"; //Default room
?>
<html>

<head>
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<link rel="icon" type="image/png" href="favicon.png">
	<link rel="apple-touch-icon" href="favicon.png">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.pink-indigo.min.css" />
	<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
	<script>
		var sonosapi = "192.168.1.38:5005";
		var roomname = "Office";
		var speaker_location = GetParameterValues('location');

		window.onload = (event) => {
			if (!speaker_location) {
				speaker_location = roomname;
			}
			document.getElementById("app-title").innerHTML = speaker_location;
		}

		function setlocation(location) {
			speaker_location = location;
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

			if (el) {
				$('.mdl-list__item').css("background-color", "#fafafa");
				$(el).children().css("background-color", "#e32564aa");
			}

			var data = "hello";
			$.get(sonosapi + "/" + speaker_location + "/" + cmd, function(response) {
				data = response;
				$(el).children().css("background-color", "#dfdfdf");
			});

			return data;
		}
	</script>
	<style>
		.rowpressed {
			background-color: blue;
		}

		.float-button {
			position: fixed;
			right: 16px;
			bottom: 40px;
			transition: all 0.2s ease-in 0s;
			z-index: 9999;
		}

		.music-list {
			width: 100%;
		}

		.container {
			width: 100%;
		}

		body {
			background-color: #fafafa;
		}

		.columns {
			padding-top: 12px;
		}

		.mdl-button {
			font-size: 24px;
			height: 48px;
		}

		.mdl-list__item--two-line {
			height: 90px;
			padding-top: 0px;
		}

		.mdl-list__item--two-line .mdl-list__item-primary-content {
			height: 64px;
		}

		.mdl-layout__drawer-button .material-icons {
			line-height: 48px;
		}

		.mdl-chip__text .material-icons {
			font-size: 30px;
			color: #fff;
		}

		.mdl-chip__contact {
			background-color: rgb(233, 30, 99);
			border: none;
		}

		.info-panel {
			display: flex;
			align-items: center;
			justify-content: center;
			width: 100%;
			position: fixed;
			background-color: #FFF0;
			height: 64px;
			bottom: 0px;
		}

		.info-panel .playpause {
			box-shadow: 0 -2px 0px 0px rgba(0, 0, 0, .14), 0 -3px 1px -2px rgba(0, 0, 0, .2), 0 -4px 5px 0 rgba(0, 0, 0, .12);
			height: 90px;
			width: 90px;
			border-radius: 45px;
			z-index: 9999;
			align-text: center;
		}

		.info-panel-sub {
			width: 100%;
			background-color: rgb(233, 30, 99);
			color: white;
			height: 64px;
			position: fixed;
			bottom: -32px;
			box-shadow: 0 -2px 0px 0px rgba(0, 0, 0, .14), 0 -3px 1px -2px rgba(0, 0, 0, .2), 0 -4px 5px 0 rgba(0, 0, 0, .12);
		}
	</style>
</head>

<body>
	<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
		<header class="mdl-layout__header">
			<div class="mdl-layout__header-row">
				<span id="app-title" class="mdl-layout-title">Sonos Phone</span>
				<div class="mdl-layout-spacer"></div>
			</div>
		</header>
		<div class="mdl-layout__drawer">
			<span class="mdl-layout-title">Locations</span>
			<nav class="mdl-navigation">

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

					echo "<button class='mdl-button mdl-js-button mdl-button--primary ' onclick=\"setlocation('" . $value->coordinator->roomName . "')\">" . $value->coordinator->roomName . "</button>";
				}
				?>

			</nav>
		</div>

		<main class="mdl-layout__content">

			<?php

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $sonosapi . "/" . $rooname . "/favorites/detailed");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

			$result = curl_exec($curl);
			if (curl_error($curl)) {
				die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
			}

			curl_close($curl);
			$json = json_decode($result);
			echo "<ul class='music-list mdl-list'>";

			foreach ($json as $value) {
				if (isset($value->albumArtUri)) {
					echo "<a onclick=\"command('favorite/" . $value->title . "', this);\">";
					echo "<li class='mdl-list__item mdl-list__item--two-line'>";
					echo "<span class='mdl-list__item-primary-content'>";
					echo "<img style='width:80px' src=\"" . $value->albumArtUri . "\"/>";
					$response = new SimpleXMLElement($value->metadata);
					echo "<span style='padding-left: 8px'>" . $response->item[0]->children('dc', true)->title . "</span>";
					echo "</span></li>";
					echo "</a>";
				}
			}

			echo "</ul>";
			echo "<div style='height: 60px'></div>";


			?>

			<div class="info-panel">
				<button class="mdl-chip__contact playpause" onclick="command('playpause');">
					<span class="mdl-chip__text">
						<i class="material-icons">play_arrow</i>
						<i class="material-icons">pause</i>
					</span>
				</button>
				<div class="info-panel-sub">
					<button class="mdl-chip__contact" style="width: 30%; float:left;" onclick="command('previous');">
						<span class="mdl-chip__text">
							<i class="material-icons">skip_previous</i>
						</span>
					</button>
					<button class="mdl-chip__contact" style="width: 30%; float: right;" onclick="command('next');">
						<span class="mdl-chip__text">
							<i class="material-icons">skip_next</i>
						</span>
					</button>
				</div>
			</div>


	</div>
	</main>

	</div>
</body>

</html>