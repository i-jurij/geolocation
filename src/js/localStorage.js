class LocSt {
    getLocalStorage(name) {
        let item = localStorage.getItem(name);
        if (item != null) {
            return JSON.parse(item);
        }
        return null;
    }

    setLocality({ city, region = '' }) {
        let data_object = { city, region };
        localStorage.setItem('locality', JSON.stringify(data_object));
    }

    removeLocality() {
        localStorage.removeItem("locality");
    }

    setAllLocality(data_array) {
        localStorage.setItem('all_locality', JSON.stringify(data_array));
    }
}

export let LS = new LocSt();