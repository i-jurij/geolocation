# A part of oswc framework for geolocation
## Description
The application tries to get the name of the city and region, passes it in the response.
Javascript checks LocalStorage and, if there is saved data about the city, displays it on the page. In this case, the data received from the server is ignored.
If there is no data about the city in LocalStorage, and the server has provided it, Javascript uses it to display it on the screen and save it in LocalStorage.
If both LocalStorage and the server have not provided data, Javascript tries to get it from the coordinates, first in the database, then using the yandex-geocoder API.
If the data is not received, the user is asked to select their location independently.
The received data is stored in LocalStorage.

## Install
### Upload
It contain two parts: php and js, also it need upload into vendor and node_modules directory   
for ease of autoloading.   
PHP part that can be install by composer (composer.json) and   
JS part that can be install by npm (package.json).   

Create:   
**composer.json**:   
```
{
    ...
    "repositories": [
        {
            "url": "https://github.com/i-jurij/geolocation.git",
            "type": "git"
        }
    ],
    "require": {
        "i-jurij/geolocation": "~1.0"
    }
}
```
**package.json**:   
```
{
    ...
    "dependencies": {
        "geolocation": "github:i-jurij/geolocation"
  }
}
```

Then run `composer install` and `npm install` from command line into your project directory.   

### Put to app
Add to some place in your php controller   
```
$geo = new Geolocation\Php\Back();
$location = $geo->getLocation();
```   
Then pass $location on template.  

And add to your page or template
```
	<div id="location_div"></div>

	<script>
        let url_from_coord = 'index.php';
		let url_from_db = 'index.php';
		let yapikey = 'your_yandex_map_api_key'; // use only if city not found in db and can be not configured		
		let city_from_back = '<?php echo !empty($location['city']) ? $location['city'] : 'Местоположение'; ?>';
		let region_from_back = '<?php echo !empty($location['region']) ? $location['region'] : ''; ?>';
	</script>
	<script  type="module" src="build/geolocation.min.js"></script>
```

Then app will try get city and out it to page.  
If not then app will try get city by coord from db, if it has no result   
then user must shoose city from list.   
It is ajax request.  
For processing it add next text to your ajax script or controller (don't forget to specify the routes in ajax_url):  
for getting city from coord   
``` 
    $fromCoord = new Geolocation\Php\Front();
    $fromCoord->fromCoord();
``` 
for getting city from db   
```
    $fromDb = new Geolocation\Php\Front();
    $fromDb->getAll();
```
Methods "fromCoord" and "getAll" return json into response and exit. 


#### Example
If your site not use MVC model example is into `index.php` into root directory.   

If MVC:   
Controllers (or presenters) method could be like this:
```
function index(){
    $geo = new Geolocation\Php\Back();
    $location = $geo->getLocation();
    $this->view->generate(View::index, $location);
}
``` 
For shoosing city from list i use fetch request to class Front.  
Js variable "url_from_db" use for getting url to controller that will return json responce.   
For this don't forget to specify the routes in your framework (eg Route('url_from_coord', 'Controller:asyncFromCoord')) for getting city from coordinates and for shoosing from city list.   
Also controllers methods for async request processing could be like this:  
``` 
function asyncFromCoord(): void {
    $fromCoord = new Geolocation\Php\Front();
    $fromCoord->fromCoord();
}
``` 
```
function asyncFromDb(): void {
    $fromDb = new Geolocation\Php\Front();
    $fromDb->getAll();
}
```

Then template or View:
to head put link to [oswc2_styles](https://github.com/i-jurij/oswc2_styles) 
```
<link rel="stylesheet" type="text/css" href="www/oswc2_styles/oswc2_styles.min.css">
```
```
	<div id="location_div"></div>

	<script>
        let url_from_coord = 'url_for_ajax_processing'; // or {Url::('Controller:asyncGeo')} eg
        let url_from_db = 'index.php';
        let yapikey = 'your_yandex_map_api_key'; // can be not configured
		let city_from_back = '<?php echo !empty($location['city']) ? $location['city'] : 'Местоположение'; ?>';
		let region_from_back = '<?php echo !empty($location['region']) ? $location['region'] : ''; ?>';
	</script>
	<script  type="module" src="build/geolocation.min.js"></script>
```

## Work
Example is into `index.php` into root directory.   
Example can be run from root directory of module:   
```
cd rootDirectory;
php -S 127.0.01:8000
```   
then open in browser `127.0.01:8000`   

If all right app out city name to element with id "location_div" and put location to localstorage   
`localStorage.setItem('locality', JSON.stringify(data_object));`  
when   
`data_object = { city: 'city name', adress: 'region name', id: 'id of city from db table' };`    
It can be use for data getting from back throw ajax eg.  