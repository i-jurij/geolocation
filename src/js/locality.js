import { htmlReplace, htmlInfo, htmlFromDB } from "./html.js";
import { LocalStorage } from './localStorage.js'
import { LocalityByCoord } from './localityByCoord.js'

export class Locality {
    l = { city: '', region: '' };

    constructor() {
        this.LS = new LocalStorage();
        this.lfls = this.LS.getFromLocalStorage('locality');
    }

    async init() {
        htmlReplace();

        if (this.lfls) {
            if (city_from_back && !city_from_back.toLowerCase().includes(this.lfls.city.toLowerCase())) {
                this.localityToServer(url_location_to_server_js, csrf, this.lfls.city, this.lfls.region)
                htmlInfo({ city: this.lfls.city, region: this.lfls.region });
            }
        } else {
            if (city_from_back == unknown_location) {
                // get city from coord throw browser.navigator
                this.LC = new LocalityByCoord();
                this.LC.url = url_js_fetch;
                this.l = await this.LC.get();
                htmlInfo(this.l);
                if (this.LC.checkResponce(this.l)) {
                    this.LS.setLocalityToLocalStorage({ city: this.l.city, region: this.l.region });
                    this.localityToServer(url_location_to_server_js, csrf, this.l.city, this.l.region)
                }
            } else {
                this.LS.setLocalityToLocalStorage({ city: city_from_back, region: region_from_back });
            }
        }

        this.fromDB();
    }

    fromDB() {
        const shoose_location = document.querySelector('#shoose_location');
        if (shoose_location) {
            shoose_location.onpointerdown = async (event) => {
                //get all locations (from LS or server)
                let al = await this.getAllLocations();
                // html output
                htmlFromDB(await al);
            };
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
}
