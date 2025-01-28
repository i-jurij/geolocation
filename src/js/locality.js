import { htmlReplace, htmlInfo } from "./html.js";
import { LocalStorage } from './localStorage.js'
import { localityToServer } from './localityToServer.js'
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
                localityToServer(url_location_to_server_js, csrf, this.lfls.city, this.lfls.region)
                htmlInfo({ city: this.lfls.city, region: this.lfls.region });
            }
        } else {
            if (city_from_back == unknown_location) {
                // get city from coord throw browser.navigator
                this.LC = new LocalityByCoord();
                this.LC.url = url_from_coord;
                this.l = await this.LC.get();
                htmlInfo(this.l);
                if (this.LC.checkResponce(this.l)) {
                    this.LS.setLocalityToLocalStorage({ city: city_from_back, region: region_from_back });
                    localityToServer(url_location_to_server_js, csrf, this.l.city, this.l.region)
                }
            } else {
                this.LS.setLocalityToLocalStorage({ city: city_from_back, region: region_from_back });
            }
        }
    }
}
