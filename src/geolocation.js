import { html } from "./js/html.js";
import { LS } from './js/localStorage.js'
import { showLocality } from './js/showLocality.js'
import { localityToServer } from './js/localityToServer.js'

html();

document.addEventListener('DOMContentLoaded', () => {
    // search in localstorage keeped data with user location
    let locality_from_ls = LS.getLocalStorage('locality');

    if (locality_from_ls) {
        if (city_from_back && !city_from_back.toLowerCase().includes(locality_from_ls.city.toLowerCase())) {
            localityToServer(url_location_to_server_js, csrf, locality_from_ls.city, locality_from_ls.region)
            showLocality({ city: locality_from_ls.city, region: locality_from_ls.region });
            console.log('localityToServer')
        }
        console.log('none action')
    } else {
        if (city_from_back == unknown_location) {
            // get city from coord by browser.navigator.
            //getLoc();
            console.log('getLoc by coord')
        } else {
            LS.setLocality({ city: city_from_back, region: region_from_back });
        }
    }
});
