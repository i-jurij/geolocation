export class LocalStorage {
    getFromLocalStorage(name) {
        let item = localStorage.getItem(name);
        if (item != null) {
            return JSON.parse(item);
        }
        return null;
    }

    setLocalityToLocalStorage({ city, region = '' }) {
        let data_object = { city, region };
        localStorage.setItem('locality', JSON.stringify(data_object));
    }

    removeLocalityFromLocalStorage() {
        localStorage.removeItem("locality");
    }

    setAllLocalityFromDBToLocalStorage(data_array) {
        localStorage.setItem('all_locality', JSON.stringify(data_array));
    }
}