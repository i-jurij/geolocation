document.head.appendChild(document.createElement("style")).textContent=".modal .overlay~*{position:relative;box-shadow:none;border-radius:0.2em;border:1px solid #aaa;overflow:hidden;text-align:left;background:#fff;margin-bottom:0.6em;padding:0;transition:all 0.3s ease;}.modal .overlay~.hidden,.modal .overlay~:checked+*,.modal .overlay:checked+*{font-size:0;padding:0;margin:0;border:0;}.modal .overlay~*>*{max-width:100%;display:block;}.modal .overlay~* header,.modal .overlay~* section,.modal .overlay~*>p{padding:0.6em 0.8em;}.modal .overlay~* section{padding:0.6em 0.8em 0;}.modal .overlay~* header{font-weight:bold;position:relative;border-bottom:1px solid #aaa;}.modal .overlay~* header h3{padding:0;margin:0 2em 0 0;line-height:1;display:inline-block;vertical-align:text-bottom;}.modal .overlay~* footer{padding:0.8em;}.modal .overlay~* p{margin:0.3em 0;}.modal .overlay~* p:first-child{margin-top:0;}.modal .overlay~* p:last-child{margin-bottom:0;}.modal .overlay~* .close{position:absolute;top:0.4em;right:0.3em;font-size:1.2em;padding:0 0.5em;cursor:pointer;width:auto;}.modal .overlay~* .close:hover{color:#ff4136;}.modal .overlay~* .button_close{float:right;margin-bottom:0.5rem;}.modal .overlay~* .button_shoose{float:left;margin-bottom:0.5rem}.modal{text-align:center;}.modal>input{display:none;}.modal>input~*{opacity:0;max-height:0;overflow:hidden;}.modal .overlay{top:0;left:0;bottom:0;right:0;position:fixed;margin:0;border-radius:0;background:rgba(17,17,17,0.2);transition:all 0.3s;z-index:100000;}.modal .overlay:before,.modal .overlay:after{display:none;}.modal .overlay~*{border:0;position:fixed;top:50%;left:50%;transform:translateX(-50%) translateY(-50%) scale(0.2,0.2);z-index:1000000;transition:all 0.3s;}.modal>input:checked~*{display:block;opacity:1;max-height:10000px;transition:all 0.3s;}.modal>input:checked~.overlay~*{max-height:90%;overflow:auto;-webkit-transform:translateX(-50%) translateY(-50%) scale(1,1);transform:translateX(-50%) translateY(-50%) scale(1,1);}@media (max-width:60em){.modal .overlay~*{min-width:90%;}}.button{border-radius:0.2em;border:1px solid #aaa;padding:0.5rem;}label{cursor:pointer;}.d_block{display:block;}";

function outLoc(string) {
	let con = string + '_from_back';
	if (window[con]) {
		return '<p id="p_' + string + '">' + window[con] + '</p>';
	}
}

let inner = '<div class="">\
	<label for="modal_1" class="">\
		<span class="mr1">&#128205;</span>\
		<span id="location">'
	+ city_from_back + '&ensp;&#8250;\
		</span>\
	</label>\
	<div class="modal">\
		<input id="modal_1" type="checkbox" />\
		<label for="modal_1" class="overlay "></label>\
		<article class="">\
			<header class="bgcolor">\
				<p>&nbsp;</p>\
				<label for="modal_1" class="close">&times;</label>\
			</header>\
			<section class="content bgcontent" id="clients_location_message">'
	+ outLoc('city') + outLoc('region') +
	'</section >\
			<footer class="bgcontent">\
				<label for="show_city_select" class="button button_shoose" id="shoose_location">\
					Выбрать\
				</label>\
				<label for="modal_1" class="button button_close">\
					Закрыть\
				</label>\
			</footer>\
		</article >\
	</div >\
	<div class="modal">\
		<input id="show_city_select" type="checkbox" />\
		<label for="show_city_select" class="overlay "></label>\
		<article class="">\
			<header class="bgcolor">\
				<p>&nbsp;</p>\
				<label for="show_city_select" class="close">&times;</label>\
			</header>\
			<section class="content bgcontent" id="">\
				<input class="d_block" type="text" name="city_search_input" id="city_search_input" placeholder="Поиск">\
					<select id="shoose_district" class="d_block select mb1">\
						<option>Округ</option>\
					</select>\
					<select id="shoose_region" class="d_block select mb1" disabled>\
						<option>Регион (область)</option>\
					</select>\
					<select id="shoose_city" class="d_block select" disabled>\
						<option>Город</option>\
					</select>\
			</section>\
			<footer class="bgcontent">\
				<button id="save_city" class="button">\
					Выбрать\
				</button>\
				<label for="show_city_select" class="button button_close">\
					Закрыть\
				</label>\
			</footer>\
		</article>\
	</div>\
</div >';

function html() {
	let el = document.querySelector('#location_div');
	if (el) {
		el.innerHTML = inner;
	}
}

function outLocation({ city, adress }) {
    const city_elem = document.getElementById("location");
    const clients_place_message = document.getElementById("clients_location_message");
    document.getElementById("shoose_location");
    const checkbox_modal_window = document.getElementById('modal_1');

    if (city_elem && city && typeof city == 'string') {
        city_elem.innerHTML = city + "&ensp;&#8250;";
        let adr = '';
        if (typeof adress == 'string' && adress && adress.includes(city + ' ')) {
            adr = '<div class="my2">' + adress + '</div>';
        } else if (typeof adress == 'string' && adress && !adress.includes(city + ' ')) {
            adr = '<div class="mt2">' + city + '</div><div class="mb2">' + adress + '</div>';
        } else {
            adr = '<div class="my2">' + city + '.</div>';
        }

        clients_place_message.innerHTML = 'Ваше местоположение: ' + adr + ' Если нет - выберите его, нажав на кнопку "Выбрать"';
        // checkbox_modal_window.checked = true;
    }
    if (city_elem && !city) {
        if (clients_place_message) {
            clients_place_message.innerHTML = 'Ваше местоположение неизвестно. </br>Выберите его, нажав на кнопку "Выбрать"';
            checkbox_modal_window.checked = true;
        }
    }
}

function getLocality() {
    return JSON.parse(localStorage.getItem('locality'));
}

function setLocality({ city, adress = '', id = '' }) {
    let data_object = { city, adress, id };
    localStorage.setItem('locality', JSON.stringify(data_object));
}

async function locationFromYandexGeocoder(yapikey, { long, lat }, format = 'json', kind = 'locality', results = 1) {
    const url = "https://geocode-maps.yandex.ru/1.x/?apikey=" + yapikey + "&geocode=" + long + "," + lat + "&format=" + format + "&results=" + results + "&kind=" + kind;
    try {
        const response = await fetch(url, {
            headers: {
                'Accept': 'application/json'
            }
        });
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }

        const json = await response.json();

        let name = json.response.GeoObjectCollection.featureMember[0].GeoObject.name;
        let description = json.response.GeoObjectCollection.featureMember[0].GeoObject.description;

        if (name && description) {
            outLocation({ city: name, adress: description });
            setLocality({ city: name, adress: description });
        } else {
            console.error('No location data in responce from geocode-maps.yandex.ru');
        }
        //return { city: name, adress: description };
    } catch (error) {
        console.error(error.message);
    }
}

// for city getting from Yandex Geocoder from browser navigator geolocation
//import { yapikey } from "../config/yandex_api.js"

// get location from browser geolocation and yandex geocoder
// required user permission for geolocation
async function getLoc() {
    async function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(getCoords, showError, positionOption);
        } else {
            outLocation({ city: '', adress: '' });
            console.warn("WARNING! Geolocation is not supported by this browser.");
        }
    }

    let positionOption = { timeout: 5000, /* maximumAge: 24 * 60 * 60, /* enableHighAccuracy: true */ };

    function getCoords(position) {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;

        let coord = { long: longitude, lat: latitude };

        fetch(url_from_coord + '?coord=' + long + '_' + coord.lat, {
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(response => checkResponce(response) ? outSave(response) : locationFromYandexGeocoder(yapikey, coord))
            .catch(error => console.error(error));
    }

    function showError(error) {
        outLocation({ city: '', adress: '' });

        switch (error.code) {
            case error.PERMISSION_DENIED:
                console.error("ERROR! User denied the request for Geolocation.");
                break;
            case error.POSITION_UNAVAILABLE:
                console.error("ERROR! Location information is unavailable.");
                break;
            case error.TIMEOUT:
                console.error("ERROR! The request to get user location timed out.");
                break;
            case error.UNKNOWN_ERROR:
                console.error("ERROR! An unknown error occurred.");
                break;
        }
    }

    function outSave({ city, adress, id }) {
        outLocation({ city, adress });
        setLocality({ city, adress, id });
    }

    function checkResponce(obj) {
        if (typeof obj === 'object' && 'city' in obj && obj.city != '' && obj.city != 'undefined' && typeof obj.city == 'string') {
            return true;
        } else {
            return false;
        }
    }
    getLocation();
}

function geoLocation() {
    document.addEventListener('DOMContentLoaded', () => {
        // search in localstorage keeped data with user location
        //let locality = JSON.parse(localStorage.getItem('locality'));
        let locality = getLocality();

        const substring = "Местоположение";

        if (locality) {
            outLocation({ city: locality.city, adress: locality.adress });
        } else {
            if (city_from_back) {
                if (city_from_back.includes(substring)) {
                    getLoc();
                    //outLocation({ city: '', adress: '' });
                } else {
                    let region = region_from_back ?? '';
                    outLocation({ city: city_from_back, adress: region });
                    setLocality({ city: city_from_back, adress: region });
                }
            } else {
                console.error('ERROR! Element with id "location" is empty.');
            }
        }
    });
}

function districtOut(districts) {
    let inner = '<option value="" id="empty_district">Округ</option>';

    for (const key of Object.keys(districts)) {
        // console.log(district[key]['id'] + ' ' + district[key]['name'])
        inner = inner + '<option value="' + districts[key]['id'] + '">' + districts[key]['name'] + '</option>';
    }
    let shoose_district = document.querySelector('#shoose_district');
    if (shoose_district) {
        shoose_district.innerHTML = inner;
    }
    let shoose_region = document.querySelector('#shoose_region');
    if (shoose_region) {
        shoose_region.innerHTML = '<option value="">Регион</option>';
    }
    let shoose_city = document.querySelector('#shoose_city');
    if (shoose_city) {
        shoose_city.innerHTML = '<option value="">Город</option>';
    }
}

function regionOut(regions) {
    let inner = '<option value="" id="empty_region">Регион</option>';
    for (const key of Object.keys(regions)) {
        inner = inner + '<option value="' + regions[key]['id'] + '">' + regions[key]['name'] + '</option>';
    }
    let shoose_region = document.querySelector('#shoose_region');
    if (shoose_region) {
        shoose_region.disabled = false;
        shoose_region.innerHTML = inner;
    }
    let shoose_city = document.querySelector('#shoose_city');
    if (shoose_city) {
        shoose_city.innerHTML = '<option value="">Город</option>';
    }
}

function cityOut(cities) {
    let inner = '<option value="" id="empty_city">Город</option>';
    for (const key of Object.keys(cities)) {
        inner = inner + '<option value="' + cities[key]['id'] + '">' + cities[key]['name'] + '</option>';
    }
    let shoose_city = document.querySelector('#shoose_city');
    if (shoose_city) {
        shoose_city.disabled = false;
        shoose_city.innerHTML = inner;
    }

}

function hideLocationModal() {
    const mod_1 = document.getElementById('modal_1');
    mod_1.checked = false;
}

function regionOutAndCityOutAndSave(districts) {
    let shoose_district = document.querySelector('#shoose_district');
    if (shoose_district) {
        shoose_district.addEventListener('change', function () {
            let options_empty_district = document.querySelector('#empty_district');
            if (options_empty_district) {
                options_empty_district.remove();
            }

            let district_id = this.value;
            this.options[this.selectedIndex].text;

            if (district_id) {
                let regions0 = districts[district_id];
                if (regions0) {
                    let regions = regions0['regions'];
                    regionOut(regions);
                    cityOutAndSave(regions);
                }
            }
        });
    }
}

function cityOutAndSave(regions) {
    let shoose_region = document.querySelector('#shoose_region');
    if (shoose_region) {
        shoose_region.addEventListener('change', function () {
            let options_empty_region = document.querySelector('#empty_region');
            if (options_empty_region) {
                options_empty_region.remove();
            }
            let region_id = this.value;
            let region_text = this.options[this.selectedIndex].text;
            if (region_id) {
                let cities0 = regions[region_id];
                if (cities0) {
                    let cities = cities0['cities'];
                    if (cities) {
                        cityOut(cities);
                    }
                }

                let shoose_city = document.querySelector('#shoose_city');
                if (shoose_city) {
                    shoose_city.addEventListener('change', function () {
                        let options_empty_city = document.querySelector('#empty_city');
                        if (options_empty_city) {
                            options_empty_city.remove();
                        }
                        let city_id = this.value;
                        let city_text = this.options[this.selectedIndex].text;

                        saveCity(city_text, region_text, city_id);
                    });
                }
            }
        });
    }
}

function saveCity(city_text, region_text, city_id) {
    let save_city = document.querySelector('#save_city');
    if (save_city) {
        save_city.addEventListener('click', function () {
            //let opt_adress = region_text + ' ' + district_text;
            let opt_adress = region_text;
            setLocality({ city: city_text, adress: opt_adress, id: city_id });
            outLocation({ city: city_text, adress: opt_adress });
            const show_city_select = document.getElementById('show_city_select');
            if (show_city_select) {
                show_city_select.checked = false;
            }
        });
    }
}

function fromDB() {
    let shoose_location = document.querySelector('#shoose_location');
    if (shoose_location) {
        shoose_location.addEventListener('click', function () {
            hideLocationModal();

            fetch(url_from_db, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X_FROMDB': 'shooseFromDb',
                }
            })
                .then((response) => response.ok === true ? response.json() : false)
                .then(locations => {
                    let districts = locations['district'];
                    districtOut(districts);
                    regionOutAndCityOutAndSave(districts);
                });
        }, false);

    }
}

//import "../css/style.css"; /* extract the styles to a external css bundle */
html();
geoLocation();
fromDB();

// reread data from db with regions or city data of executors or customers from city of localstorage

/* <!-- js for esc on modal (in Home part of site that based on PicnicCSS) --> */
document.onkeydown = function (event) {
    if (event.key == "Escape") {
        var mods = document.querySelectorAll('.modal > [type=checkbox]');
        [].forEach.call(mods, function (mod) { mod.checked = false; });
    }
};
