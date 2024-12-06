<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="utf-8" />
	<title>Geo Location</title>
	<meta name="description" content="Geolocation back and front">
	<META NAME="keywords" CONTENT="geolocation">
	<meta HTTP-EQUIV="Content-type" CONTENT="text/html; charset=UTF-8">
	<meta HTTP-EQUIV="Content-language" CONTENT="ru-RU">
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
	<meta name="author" content="ijuij" >
	<!-- <link rel="icon" href="favicon.png" /> -->
</head>

<body style="width:100%;background-color:whitesmoke;color:black;">

	<?php
    error_reporting(E_ALL);
	ini_set('display_errors', '1');

	require_once __DIR__.'../vendor/autoload.php';

	use Geo\Location;

	$location = new Location();
	?>
	<script src="../build/geolocation.min.js"></script>
</body>

</html>