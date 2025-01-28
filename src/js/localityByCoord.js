import { getCoords } from './getCoords.js'
/**
 * Get location from browser geolocation and yandex geocoder.
 * Required user permission for geolocation
 */
export class LocalityByCoord {
    url = '';
    coords = '';
    type = '';

    async get() {
        this.coords = await getCoords();
        this.type = 'db';
        let loc = await this.fetchCoord();
        if (this.checkResponce(loc) === false) {
            this.type = 'yg';
            loc = await this.fetchCoord();
            if (this.checkResponce(loc) === false) {
                loc = { city: '', region: '' }
            }
        }
        return await loc;
    }

    async fetchCoord() {
        const response = await fetch(this.url + '?long=' + this.coords.long + '&lat=' + this.coords.lat + '&' + this.type + '=' + this.type, {
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json'
            }
        });

        return await response.json();
    }

    checkResponce(obj) {
        if (typeof obj === 'object' && 'city' in obj && obj.city != '' && obj.city != 'undefined' && typeof obj.city == 'string') {
            return true;
        } else {
            return false;
        }
    }
}
