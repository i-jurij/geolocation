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
			<meta name="author" content="i-jurij" >
			<!-- <link rel="stylesheet" type="text/css" href="https://cdn.statically.io/gh/i-jurij/oswc2_styles/refs/heads/main/oswc2_styles.min.css"> -->
			<link rel="stylesheet" type="text/css" href="oswc2_styles.min.css">
		</head>

		<body>

		<div id="location_div"><?php echo $data['html']; ?></div>
        <div id="data_by_location"><?php echo $data['dataFromModel']; ?></div>

		<script>
            // url or route for js fetch to geolocation class
			let url_js_fetch = 'url_js_fetch'; // Url::get('Geo', 'jsFetch') eg
			let url_location_to_server_js = 'location_to_server_js'; 
		</script>
		<script type="module" src="geolocation.js"></script>
		</body>
		</html>
