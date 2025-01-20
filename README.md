# Модуль геолокации 
# A part of oswc framework for geolocation
## Описание
## Description
Приложение пытается определить местоположение пользователя.     
При первом запросе от клиента приложение (php часть) определяет местонахождение браузера по его ip  
и выдает HTML в ответе. Если местонахождение не определено, JS пытается найти его по координатам.   
Если местоположение не определено, пользователь может указать его вручную (только РФ).   

При отключенном JS данные формы       
`[ 'city' => 'city name', 'region' => 'region name', 'district': 'district name' }`   
отправляются по адресу заданному в   
`(new new Ijurij\Geolocation\Geolocation())->url_location_to_server = 'your_url';`.   

PHP часть приложения полученные данные хранит на сервере в сессионных переменных   
$_SESSION['city'] и $_SESSION['region'].

JS проверяет наличие сохраненных данных о местоположении в LocalStorage   
(`{ city: 'city name', region: 'region name'}`), и, если они там есть,   
выводит их на страницу и передает эти данные на сервер для возможной обработки по адресу, указанному на странице (или шаблоне) (`let url_save_to_backend = '<?php echo $geo->url_location_to_server; ?>';`).  

Если данных о местоположении в LocalStorage нет, JS часть пытается получить координаты браузера   
и по этим координатам определить местоположение сначала через запрос на сервер к базе данных,   
потом через яндекс геокодер (отключен по умочанию).  

Если вы хотите использовать яндекс геокодер (в большинстве случаев такой необходимости не возникает), 
после создания объекта класса геолокации, установите необходимые значения (указаны по умолчанию):
```   
$geo = new Ijurij\Geolocation\Geolocation();
// if you plan to use yandex geocoder (in most cases this is not necessary)
$geo->yandex_api_key = '';
$geo->yandex_format = 'json';
$geo->yandex_kind = 'locality';
$geo->yandex_results = 1;
```

Если местоположение так и не определено - предлагает пользователю выбрать его из списка городов РФ.   

После выбора города JS сохраняет данные в LocalStorage и передает их на сервер для возможной обработки   
по адресу, указанному на странице (или шаблоне) (`url_location_to_server`, для каждой страницы может быть   
указан свой url, для передачи данных в разные контроллеры или модели, например для получения списка    
товаров по определенному городу на одной странице и списка магазинов в этом городе на другой странице).   
Также передается http заголовок 'X_TOBACKEND' = 'toBackend'.   

Кроме выбранного пользователем города, JS часть сохраняет в LocalStorage копию базы с данными о городах РФ (55Кб), сортированную по округам и регионам, чтобы избежать запросов на сервер при возможном выборе другого местоположения пользователем.


The application tries to get the name of the city and region, passes it in the response.
Javascript checks LocalStorage and, if there is saved data about the city, displays it on the page. In this case, the data received from the server is ignored.
If there is no data about the city in LocalStorage, and the server has provided it, Javascript uses it to display it on the screen and save it in LocalStorage.
If both LocalStorage and the server have not provided data, Javascript tries to get it from the coordinates, first in the database, then using the yandex-geocoder API.
If the data is not received, the user is asked to select their location independently.
The received data is stored in LocalStorage and receive to server backend. 

!!!!!
## Install
It contain two parts:   
php and js (+ small css into js),   
it can be install by composer (composer.json) or npm (package.json) or both.   

Create:   
**composer.json**:   
```
{
    ...
    "repositories": [
        {
            "url": "https://github.com/i-jurij/geolocation2.git",
            "type": "vcs"
        }
    ],
    "require": {
        "i-jurij/geolocation2": "dev-main"
    }
}
```

or   

**package.json**:   
```
{
    ...
    "dependencies": {
        "geolocation2": "github:i-jurij/geolocation2"
  }
}
```

Then run `composer install` or `npm install` from command line into your project directory.   
But you must resolve import from "node_modules" directory in first way (composer) and   
resolve automatic autoloading of php class from "vendor" directory in second way.  

For automatic resolving autoloading of php class from "vendor" directory and   
javascript import from "node_modules" directory you can `composer install` and `npm install` both execute.   
Package weight ~50Kb.  

### Dependencies
CSS   
[oswc2_styles](https://github.com/i-jurij/oswc2_styles)  
Put to head    
`<link rel="stylesheet" type="text/css" href="https://cdn.statically.io/gh/i-jurij/oswc2_styles/refs/heads/main/oswc2_styles.min.css">`    
or   
[Picnic CSS](https://github.com/franciscop/picnic)   
Put to head    
`<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/picnic/7.1.0/picnic.min.css">`    
Or get css other ways (npm | github | source + webpack, rollup, gulp etc)   

JS part of this module use small css with [autoComplete.js](https://github.com/TarekRaafat/autoComplete.js) that inject into code until minify with rollup.   
If your optimizator (minimizator) extract css from js then need link css manually from assets dir eg:   
`<link rel="stylesheet" type="text/css" href="assets/css/geolocation.css">`   

!!!
## Example
If your site not use MVC model example is into `index.php` into root directory.   

If MVC:   
Controllers (or presenters) method could be like this:
```
function index(){
    $geoClass = new Geolocation\Php\View();
    // here get data by $geoClass->location
    $data = Model::get($geoClass->location);
    $geoClass->post_url = '/'; // url for form action after city choice, only if javascript is disabled
    $geo = $geoClass->htmlOut(); // string, html code (city name and modal window with info and city choice)
    $this->view->generate(View::index, [$geo, $data]);
}
``` 
Methods before execute with $geoClass->post_url only if JS disabled.   
Don't set this method as target for js fetch request because JS rewrite only inner html of element with id "data_by_location" but no full page.

For shoosing city from list i use fetch request to class Front.  
Js variable "url_from_db" use for getting url to controller that will return json responce.   
For this don't forget to specify the routes in your framework (eg Route('url_from_coord', 'Controller:asyncFromCoord')) for getting city from coordinates and for shoosing from city list.   
Also controllers methods for async requests processing could be like this:  

``` 
function asyncFromCoord(): void {
    $fromCoord = new \Geolocation\Php\Front();
    $fromCoord->fromCoord();
}
``` 

```
function asyncFromDb(): void {
    $fromDb = new \Geolocation\Php\Front();
    $fromDb->getAll();
}
```

```
function getDataByLocation(): void {
        if ($_SERVER['REQUEST_METHOD'] == 'POST'
        && !empty($_POST['district'])
        && filter_input(INPUT_POST, 'district') !== false
        && !empty($_POST['region'])
        && filter_input(INPUT_POST, 'region') !== false
        && !empty($_POST['city'])
        && filter_input(INPUT_POST, 'city') !== false) {
            $location = [
                'city' => filter_input(INPUT_POST, 'city', FILTER_SANITIZE_SPECIAL_CHARS),
                'region' => filter_input(INPUT_POST, 'region', FILTER_SANITIZE_SPECIAL_CHARS),
                'district' => filter_input(INPUT_POST, 'district', FILTER_SANITIZE_SPECIAL_CHARS),
            ];
            
            $data = Model::get($location);
            $html = View::($data);
            header('Content-Type: application/json');
            echo json_encode($html);
            exit;
        }
}
```

Then in base template (layout):
to head put link to [oswc2_styles](https://github.com/i-jurij/oswc2_styles)  
"oswc2_styles" is a dependencies for geolocation,   
also if you install geolocation by npm then "oswc2_styles" is into "node_modules/oswc2_styles" directory. 
If you install geolocation only by composer then you need to get "oswc2_styles" by npm or cdn.

Use Rollup, Gulp, Grunt, Webpack or other for putting oswc2_styles to your assets directory and then
```
<link rel="stylesheet" type="text/css" href="assets/css/oswc2_styles.min.css">
```

or copy oswc2_styles.min.css to your www directory from "node_modules/oswc2_styles/oswc2_styles.min.css" 

or simply
```
<link rel="stylesheet" type="text/css" href="https://cdn.statically.io/gh/i-jurij/oswc2_styles/refs/heads/main/oswc2_styles.min.css">
```
Module use small css with autoComplete.js that inject into code util minify with rollup.   
If your optimizator (minimizator) get out css from js then need link css manually from assets dir eg:   
```
<link rel="stylesheet" type="text/css" href="assets/css/geolocation.css">
```


and put to template or View:   
```
	<div id="location_div"><?php echo $geo; ?></div>
    <div id="data_by_location"><?php echo $data; ?></div>

	<script>
        let url_from_coord = 'url_from_coord'; // or {Url::('Controller:asyncGeo')} eg
        let url_from_db = 'url_from_db'; // {Url::('Controller:fromDb')} eg
        let url_save_to_backend = 'url_for_save_city_after_user_selects'; // {Url::('Controller:saveLoc')} eg
    </script>
	<script src="assets/js/geolocation.min.js"></script>
    <!-- or <script src="https://cdn.statically.io/gh/i-jurij/geolocation2/refs/heads/main/build/geolocation2.min.js"></script> -->
```

## Work demo
Simple example is into `index.php` into root directory.   
Example with some MVC is into directory `example`.
Run the PHP dev server into root directory for simple example or into `example` for MVC:   
```
php -S 127.0.01:8000
```   
then open in browser `127.0.01:8000`   

If all right app out city name to element with id "location_div" and put location to localstorage   
`localStorage.setItem('locality', JSON.stringify(data_object));`  
where   
`data_object = { city: 'city name', adress: 'region name', id: 'city id' };`  
and where id - id of city from db table or empty string if city was received from yandex geocoder.     

## Class Ijurij\Geolocation\Geolocation


## Errors
PHP errors, exceptions, warnings are not intercepted or processed.   
JS print errors in console.log 