# Модуль геолокации 
# A part of oswc framework for geolocation
## Описание
## Description
Приложение пытается определить местоположение пользователя.     
При первом запросе от клиента приложение (php часть) определяет местонахождение браузера по его ip  
и выдает HTML в ответе. Если местонахождение не определено, JS пытается найти его по координатам.   
Если местоположение не определено, пользователь может указать его вручную (только РФ).   
После получения местоположения данные `[ 'city' => 'city name', 'region' => 'region name', ...}`   
отправляются по адресу заданному в `(new Ijurij\Geolocation\Geolocation())->url_location_to_server = 'your_url';`.   

PHP часть приложения полученные данные хранит на сервере в сессионных переменных   
$_SESSION['city'] и $_SESSION['region'].

JS проверяет наличие сохраненных данных о местоположении в LocalStorage   
(`{ city: 'city name', region: 'region name'}`), и, если они там есть,   
выводит их на страницу и передает эти данные на сервер для возможной обработки по адресу, указанному на странице (или шаблоне) (`let url_location_to_server = '<?php echo $geo->url_location_to_server; ?>';`).  
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

Кроме выбранного пользователем города, JS часть сохраняет в LocalStorage копию базы с данными о городах РФ (55Кб), сортированную по округам и регионам, чтобы избежать запросов на сервер при возможном выборе другого местоположения пользователем.

***PHP часть не зависит от javascript. Javascript добавляет возможность получения местоположения пользователя по координатам, если по IP определить не вышло, а также изменить содержимое элементов страницы без ее полной перезагрузки.***

The application tries to get the name of the city and region, passes it in the response.
Javascript checks LocalStorage and, if there is saved data about the city, displays it on the page. In this case, the data received from the server is ignored.
If there is no data about the city in LocalStorage, and the server has provided it, Javascript uses it to display it on the screen and save it in LocalStorage.
If both LocalStorage and the server have not provided data, Javascript tries to get it from the coordinates, first in the database, then using the yandex-geocoder API.
If the data is not received, the user is asked to select their location independently.
The received data is stored in LocalStorage and receive to server backend. 

## Install
It contain two parts:   
php and js (+ small css into js),   
it can be install by composer (composer.json) or npm (package.json) or both.  

Create in root directory of your project next files:   
**composer.json**:   
```
{
    ...
    "repositories": [
        {
            "url": "https://github.com/i-jurij/geolocation.git",
            "type": "vcs"
        }
    ],
    "require": {
        "i-jurij/geolocation": "dev-main"
    }
}
```

or/and   

**package.json**:   
```
{
    ...
    "dependencies": {
        "geolocation": "github:i-jurij/geolocation"
  }
}
```

Then run `composer install` or `npm install` from command line into your project directory.   
But you must resolve import from "vendor" directory in first way (composer) and   
resolve automatic autoloading of php class from "node_modules" directory in second way.  

For automatic resolving autoloading of php class from "vendor" directory and   
javascript import from "node_modules" directory you can `composer install` and `npm install` both execute.   

### Dependencies
The library has CSS styles [oswc2_styles](https://github.com/i-jurij/oswc2_styles) as dependencies.  
***If you install this library by npm this dependencies will be installed too.***   
So you can create link
```
<link rel="stylesheet" type="text/css" href="node_modules/oswc2_styles/oswc2_styles.min.css">
```
or if you use optimizator/minimizator (webpack, rollup, gulp etc) for your project
```
<link rel="stylesheet" type="text/css" href="assets/css/oswc2_styles.min.css">
``` 

If you not use npm, css can be get from CDN  
`<link rel="stylesheet" type="text/css" href="https://cdn.statically.io/gh/i-jurij/oswc2_styles/refs/heads/main/oswc2_styles.min.css">`   

Or download it from github https://raw.githubusercontent.com/i-jurij/oswc2_styles/refs/heads/main/oswc2_styles.min.css.

Because "oswc2_styles" based in part on [Picnic CSS](https://github.com/franciscop/picnic) you can use it
`<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/picnic/7.1.0/picnic.min.css">` 

## Example
Working example is in  directory "example".  
Run php server into "example/public" directory and test it.   
```
cd ./example/public
php -S 127.0.0.8:8000
```

## Work 
Class Geolocation must be called on every page where you need locality data.  
Controllers (or presenters) method could be like this:
```
function _construct(){
    $this->geo = new Ijurij\Geolocation\Geolocation();
    /// not necessary ///
    $this->geo->ip_provider = 'location_to_server';
    $this->geo->lang = 'ru'; // language for ip_provider
    $this->geo->yandex_api_key = '';
    $this->geo->yandex_format = 'json';
    $this->geo->yandex_kind = 'locality';
    $this->geo->yandex_results = 1;
    $this->geo->url_location_to_server = 'location_to_server';
    /// end not necessary ///
    $this->geohtml = $this->geo->getHtml();
    $this->locality = $this->geo->getLocality();
}

function main(){
    $data = Model::get($this->locality);
    return View::(['geo' => $this->geohtml, 'data' => $data], $template);
}
```

After receiving the location we send it to the server, so we must set routes for your application,  
php variable "$this->geo->url_location_to_server" and javascript variable "url_location_to_server_js"  
then we can use method in controller like this 
```
function getDataByLocation(): void {
    $data = Model::get($this->locality);
     // prepare html for output on page for js or php
    if (!empty($_POST['js'])) {
        $html = JSView::(['locality' => $this->locality, 'data' => $data], $js_template);
        header('Content-Type: application/json');
        echo json_encode($html);
        exit;
    } else {
        return View::(['geo' => $this->geohtml, 'dataFromModel' => $data], $template);
    }
}
``` 
Also we must set javascript variable "url_js_fetch" for other js request
```
// return nothing, json send from Geolocation
function url_js_fetch(array $args)
{
}
```

Put to template or view:   
```
		<div id="location_div"><?php echo $data['geo']; ?></div>
        <div id="data_by_location"><?php echo $data['dataFromModel']; ?></div>

		<script>
			let url_js_fetch = 'url_js_fetch';
			let url_location_to_server_js = 'location_to_server_js'; 
		</script>
	<script src="assets/js/geolocation.min.js"></script>
```   

## Errors
PHP errors, exceptions, warnings are not intercepted or processed.   
JS are processed and print some errors in console.log 