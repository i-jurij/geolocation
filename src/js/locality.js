import { htmlReplace, htmlInfo, htmlFromDB } from "./html.js";
import { LocalStorage } from './localStorage.js'
import { LocalityByCoord } from './localityByCoord.js'
import autoComplete from './autoComplete.js-10.2.9/src/autoComplete.js'

export class Locality {
    constructor() {
        this.LS = new LocalStorage();
        let lfls = this.LS.getFromLocalStorage('locality');
        this.l = lfls ?? { city: '', region: '' };
    }

    async init() {
        htmlReplace();
        this.fromDB();

        if (this.l.city) {
            if (city_from_back && !city_from_back.toLowerCase().includes(this.l.city.toLowerCase())) {
                this.localityToServer(url_location_to_server_js, csrf, this.l.city, this.l.region)
                htmlInfo({ city: this.l.city, region: this.l.region });
            }
        } else {
            if (city_from_back == unknown_location) {
                await this.fromCoord();
            } else {
                this.LS.setLocalityToLocalStorage({ city: city_from_back, region: region_from_back });
            }
        }
    }

    async fromCoord() {
        this.LC = new LocalityByCoord();
        this.LC.url = url_js_fetch;
        this.l = await this.LC.get();
        if (this.LC.checkResponce(this.l)) {
            this.localitySaveAndShow(url_location_to_server_js, csrf, this.l.city, this.l.region)
        }
    }

    fromDB() {
        let shoose_location = document.querySelector('#shoose_location');
        if (shoose_location) {
            shoose_location.onpointerdown = async (event) => {
                //get all locations (from LS or server)
                let al = await this.getAllLocations();
                // html output
                htmlFromDB(await al);

                this.autoComplete(await al);

                let save_city = document.querySelector('#save_city');
                let show_city_select = document.getElementById('show_city_select');
                let shoose_region = document.querySelector('#shoose_region');
                let shoose_city = document.querySelector('#shoose_city');

                if (save_city) {
                    save_city.addEventListener('pointerdown', (event) => {
                        let region_from_select = shoose_region.options[shoose_region.selectedIndex].text;
                        let city_from_select = shoose_city.options[shoose_city.selectedIndex].text;
                        if (city_from_select && region_from_select) {
                            this.localitySaveAndShow(url_location_to_server_js, csrf, city_from_select, region_from_select)
                            if (show_city_select) {
                                show_city_select.checked = false;
                            }
                        }
                    });
                };
            }
        }
    }

    async getAllLocations() {
        let all_locality = this.LS.getFromLocalStorage('all_locality');
        if (all_locality === null) {
            all_locality = await this.getAllLocationsFromDb();
            this.LS.setAllLocalityFromDBToLocalStorage(await all_locality);
        }
        return await all_locality;
    }

    async getAllLocationsFromDb() {
        const formData = new FormData();
        formData.set("token", csrf);
        formData.set("all_loc", 'fromdb');

        const response = await fetch(url_js_fetch, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X_FROMDB': 'shooseFromDb',
            },
            body: formData,
        })
        return await response.json();
    }

    localityToServer(url_location_to_server_js, csrf, city, region) {
        const formData = new FormData();

        formData.set("token", csrf);
        formData.set("city", city);
        formData.set("region", region);
        formData.set("js", 'js');

        fetch(url_location_to_server_js, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X_TOBACKEND': 'tobackend',
            },
            body: formData,
        })
            .then(response => response.json())
            .then(json => {
                const data_elem = document.getElementById('data_by_location');
                if (data_elem) {
                    data_elem.innerHTML = json;
                }
            });
    }

    autoComplete(loc) {
        function sanitize(string) {
            let regex = /^[\p{L}\p{N}\s?\-?]+[\s]?[\(]+[\p{L}\p{N}\s?\-?]+[\)]+$/;
            if (regex.test(string)) {
                return string;
            } else {
                let map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#x27;',
                    "/": '&#x2F;',
                };
                let reg = /[&<>"'/]/ig;
                return string.replace(reg, (match) => (map[match]));
            }
        }

        function dataForLiveSearch(loc) {
            let districts = loc.district;
            let data_for_livesearch = [];
            for (let e of Object.keys(districts)) {
                for (let el of Object.keys(districts[e]['regions'])) {
                    for (let ele of Object.keys((districts[e]['regions'][el]['cities']))) {
                        let region_name = districts[e]['regions'][el].name;
                        let city_name = districts[e]['regions'][el]['cities'][ele].name;
                        let city_id = districts[e]['regions'][el]['cities'][ele].id;
                        data_for_livesearch.push({
                            id: city_id,
                            city: city_name,
                            region: region_name
                        });
                    }
                }
            }
            return data_for_livesearch;
        }

        let config_live_search = {
            selector: "#autoComplete",
            placeHolder: "Поиск...",
            data: {
                src: dataForLiveSearch(loc),
                keys: ["city"],
                cache: true,
            },
            threshold: 3,
            debounce: 300, // Milliseconds value
            searchEngine: "strict",
            resultsList: {
                element: (list, data) => {
                    if (!data.results.length) {
                        // Create "No Results" message element
                        const message = document.createElement("div");
                        // Add class to the created element
                        message.setAttribute("class", "no_result");
                        message.style.padding = "1rem";
                        // Add message text content
                        message.innerHTML = '<span>Не найдено ' + sanitize(data.query) + '</span>';
                        // Append message element to the results list
                        list.prepend(message);
                    }
                },
                noResults: true,
            },
            resultItem: {
                highlight: true,
            },
            //submit: true,
        };

        const autoCompleteJS = new autoComplete(config_live_search);
        document.querySelector("#autoComplete").addEventListener("selection", (event) => {
            // "event.detail" carries the autoComplete.js "feedback" object
            //console.log(event.detail.selection.value);
            let vall = event.detail.selection.value;
            document.querySelector('#autoComplete').value = '';

            this.localitySaveAndShow(url_location_to_server_js, csrf, vall.city, vall.region)
            if (show_city_select) {
                show_city_select.checked = false;
            }

        });
    }

    localitySaveAndShow(url_to_server, csrf_value, city_name, region_name) {
        this.LS.setLocalityToLocalStorage({ city: city_name, region: region_name });
        this.localityToServer(url_to_server, csrf_value, city_name, region_name);
        htmlInfo({ city: city_name, region: region_name });
    }
}
