import { htmlReplace, htmlInfo, htmlFromDB } from "./html.js";
import { LocalStorage } from './localStorage.js'
import { LocalityByCoord } from './localityByCoord.js'

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
            this.LS.setLocalityToLocalStorage({ city: this.l.city, region: this.l.region });
            this.localityToServer(url_location_to_server_js, csrf, this.l.city, this.l.region)
            htmlInfo(this.l);
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
