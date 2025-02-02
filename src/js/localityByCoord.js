/**
 * Get location from browser geolocation and yandex geocoder.
 * Required user permission for geolocation
 */
export class LocalityByCoord {
    url = '';
    type = '';

    async get() {
        let coords = await this.getCoords();
        if (coords) {
            this.type = 'db';
            let loc = await this.fetchCoord(coords);
            if (this.checkResponce(await loc) === false) {
                this.type = 'yg';
                loc = await this.fetchCoord(coords);
                if (this.checkResponce(await loc) === false) {
                    loc = { city: '', region: '' }
                }
            }
            return await loc;
        }
    }

    async getCoords() {
        const pos = await new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(resolve, reject);
            function reject(error) {
                console.log(error.message);
            }
        });
        return {
            long: pos.coords.longitude,
            lat: pos.coords.latitude,
        };
    };

    async fetchCoord(coords) {
        let token = '&token=' + csrf;
        let longlat = '&long = ' + coords.long + ' & lat=' + coords.lat;
        let type = '&' + this.type + '=' + this.type;
        const response = await fetch(this.url + '?' + longlat + type + token, {
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
