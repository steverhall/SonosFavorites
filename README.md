# SonosFavorites
Lists all of your Sonos favorites (via node-sonos-http-api) for quick and easy playing.

Mobile web app for controlling Sonos easily. Requires an implementation of [jishi/node-sonos-http-api](https://github.com/jishi/node-sonos-http-api) to be running.

![](screenshot.png?raw=true&s=240)

# Configuration

The file `player.php` must be edited and the IP address of your node-sonos-http-api be placed in two different locations. Both javascript and PHP reference the player. 

## PHP IP address and room setting

Update the following with the settings appropriate for your setup. The $sonosapi variable should point to your node-sonos-http-api address.

The $roomname variable must be a valid room name for your system (ex: Kitchen, Office, LivingRoom). This will be the default location for the player.

```
<?php
$sonosapi = "http://192.168.1.38:5005";
$roomname = "Office"; //Default room
?>
```

## Javascript IP address and room setting

Find the lines in the `<script>` section of the `player.php` file and update with the same values as above.

```
var sonosapi = "http://192.168.1.38:5005";
var roomname = "Office";
```
