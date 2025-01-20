<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
// ini_set('log_errors', 1);
// ini_set('error_log', __DIR__.DIRECTORY_SEPARATOR.'error.log');

require_once __DIR__.'/vendor/autoload.php';

$geo = new Ijurij\Geolocation\Geolocation();
// url for getting data by locality
$geo->url_location_to_server = '/';
// provider for getting locality by ip (can be not set, default GeoPlugin)
$geo->ip_provider = 'geoplugin'; // geoplugin, sypexgeo
// for language of provider answer
$geo->lang = 'ru';
// if you plan to use yandex geocoder (in most cases this is not necessary)
/*
$geo->yandex_api_key = '';
$geo->yandex_format = 'json';
$geo->yandex_kind = 'locality';
$geo->yandex_results = 1;
*/

?>

<!DOCTYPE html>
<html lang="ru">

	<head>
		<meta charset="utf-8" />
		<title>Geolocation</title>
		<meta name="description" content="Geolocation back and front">
		<META NAME="keywords" CONTENT="geolocation">
		<meta HTTP-EQUIV="Content-type" CONTENT="text/html; charset=UTF-8">
		<meta HTTP-EQUIV="Content-language" CONTENT="ru-RU">
		<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
		<meta name="author" content="i-jurij" >
		<!-- <link rel="icon" href="favicon.png" /> -->
		<link rel="stylesheet" type="text/css" href="https://cdn.statically.io/gh/i-jurij/oswc2_styles/refs/heads/main/oswc2_styles.min.css">
	</head>

	<body>
        <!-- for outputting city and location -->
		<div id="location_div"><?php print_r($geo->run()); ?></div>

        <!-- for outputting data from server after sending location to server -->
		<div id="data_by_location"><?php echo (isset($data)) ? $data : ''; ?></div>

		<script>
            // url or route for js fetch to geolocation class
			let url_for_fetch = '/';
			let url_save_to_backend = '<?php echo $geo->url_location_to_server; ?>';
		</script>
		<!-- <script src="build/geolocation2.js"></script>-->
	</body>
</html>