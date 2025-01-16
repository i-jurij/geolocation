<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__.'/vendor/autoload.php';

$geo = new Ijurij\Geolocation\Geolocation();

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
		<div id="data_by_location"><?php echo ((bool) $data) ? $data : ''; ?></div>

		<script>
			let url_from_coord = '/';
            let url_from_coord_yg = '/'; // can be undefined
			let url_from_db = '/';
			let url_save_to_backend = '<?php // echo $geo->post_url;?>';
		</script>
		<!-- <script src="build/geolocation2.js"></script>-->
	</body>
</html>