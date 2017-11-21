<?php

$aprs_server = "127.0.0.1";
$msg="";

$lat =@$_REQUEST["lat"];
$lon =@$_REQUEST["lon"];	
$alt =@$_REQUEST["alt"];	// m
$speed =@$_REQUEST["speed"];  // m/s
$bearing =@$_REQUEST["bearing"];
$call =@$_REQUEST["call"];
$ts =@$_REQUEST["ts"];
$msg =@$_REQUEST["msg"];

if(($lat=="") || ($lon=="")) {
	echo "NO LAT/LON";
	exit(0);
}

if(strlen($ts)!=2) 
	$ts="/$";

if($call=="") 
	$call = "LOCUS";

$s = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
socket_connect($s, $aprs_server, 14580 );
$N = 'N';
if($lat < 0) {
	$lat = - $lat;
	$N = 'S';
}
$E = 'E';
if($lon < 0) {
	$lon = -$lon;
	$E = 'W';
}	
$m = $call.">LOC2US:!";
$m = $m.sprintf("%02d%05.2f%s%s", floor($lat), ($lat-floor($lat))*60, $N, substr($ts,0,1));
$m = $m.sprintf("%03d%05.2f%s%s", floor($lon), ($lon-floor($lon))*60, $E, substr($ts,1,1));
$m = $m.sprintf("%03d/%03d/A=%05d", bearing, $speed*2.237, alt*3.28);
$m = $m.sprintf("%s%s", $msg, "\r\n");
	echo $m;
//	echo "<p>";
socket_send ($s, $m, strlen($m), 0);
?>
OK
