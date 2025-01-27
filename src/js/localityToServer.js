export function localityToServer(url_location_to_server_js, csrf, city, region) {
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