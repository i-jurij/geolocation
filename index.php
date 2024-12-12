<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__.'/vendor/autoload.php';

// for js fetch for shoose city from list from db
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && $_SERVER['HTTP_SEC_FETCH_SITE'] == 'same-origin'
    && isset($_SERVER['HTTP_X_FROMDB'])
    && strtolower($_SERVER['HTTP_X_FROMDB']) == 'shoosefromdb'
) {
    $fromDb = new Geolocation\Php\Front();
    $fromDb->getAll();
    exit;
} elseif (isset($_GET['coord']) && $_SERVER['HTTP_SEC_FETCH_SITE'] == 'same-origin') {
    $fromCoord = new Geolocation\Php\Front();
    $fromCoord->fromCoord();
    exit;
} else {
    $geo = new Geolocation\Php\Back();
    $location = $geo->getLocation();
    ?>

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
			<link rel="stylesheet" type="text/css" href="node_modules/oswc2_styles/oswc2_styles.min.css">
		</head>

		<body>

		<noscript>
			<style type="text/css">
				.pagecontainer {display:none;}
			</style>
			<div class="noscriptmsg">
				You don't have javascript enabled.
			</div>
		</noscript>

		<div id="location_div"></div>

		<script>
			let url_from_coord = '/';
			let url_from_db = '/';
			let yapikey = ''; // use only if city not found in db and must be undefined or empty if not use
			let city_from_back = '<?php echo !empty($location['city']) ? $location['city'] : 'Местоположение'; ?>';
			let region_from_back = '<?php echo !empty($location['region']) ? $location['region'] : ''; ?>';
		</script>
		<script src="build/geolocation.js"></script>
		</body>
		</html>
	<?php
}
?>