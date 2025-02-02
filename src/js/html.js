function showHideModal(id, action) {
	let modal = document.getElementById(id);
	if (modal) {
		modal.checked = (action == 'show') ? true : false;
	}
}
function newData(elem_id, new_inner) {
	//let elem = document.querySelector('#' + elem_id);
	let elem = document.getElementById(elem_id);
	if (elem) {
		elem.innerHTML = new_inner;
	}
}

export function htmlReplace() {
	showHideModal("modal_1", 'hide');

	let inner_message_footer = '<label for="show_city_select" class="button" id="shoose_location">\
						Выбрать\
					</label>\
					<label for="modal_1" class="button dangerous">\
						Закрыть\
					</label>';

	let inner_choice_section = '<p>По названию:</p>\
				<input class="" name="city_search_input" id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" maxlength="2048" tabindex="1">\
				<p>Или из списка:</p>\
				<select id="shoose_district" class=" select mb1">\
					<option>Округ</option>\
				</select>\
				<select id="shoose_region" class=" select mb1" disabled>\
					<option>Регион (область)</option>\
				</select>\
				<select id="shoose_city" class=" select" disabled>\
					<option>Город</option>\
				</select>';


	let inner_choice_footer = '<button id="save_city" class="button">\
									Выбрать\
								</button>\
								<label for="show_city_select" class="button dangerous">\
									Закрыть\
								</label>';

	newData('footer_city_message', inner_message_footer);
	newData('section_city_choice', inner_choice_section);
	newData('footer_city_choice', inner_choice_footer);

	/* <!-- js for esc on modal --> */
	document.onkeydown = function (event) {
		if (event.key == "Escape") {
			var mods = document.querySelectorAll('.modal > [type=checkbox]');
			[].forEach.call(mods, function (mod) { mod.checked = false; });
		}
	}
};

function regionWithoutCity(city, region) {
	let adr = '';
	if (typeof region == 'string' && region && region.includes(city + ' ')) {
		adr = '<div class="my2">' + region + '</div>';
	} else if (typeof region == 'string' && region && !region.includes(city + ' ') && region != city) {
		adr = '<div class="mt2">' + city + '</div><div class="mb2">' + region + '</div>';
	} else {
		adr = '<div class="my2">' + city + '.</div>'
	}
	return adr;
}

export function htmlInfo({ city, region }) {
	let info = '';
	if (city && typeof city == 'string') {
		info = 'Ваше местоположение: ' + regionWithoutCity(city, region) + ' Если нет - выберите его, нажав на кнопку "Выбрать"';
		newData('location', city);
		newData('clients_location_message', info);
		// showHideModal("modal_1", 'show');
	} else {
		info = 'Ваше местоположение неизвестно. </br>Выберите его, нажав на кнопку "Выбрать"';
		newData('clients_location_message', info);
		showHideModal("modal_1", 'hide');
	}
}

////////////////////////////////////////////////////////
// from db part
////////////////////////////////////////////////////////
export function htmlFromDB(all_locations) {
	let district = all_locations.district;
	districtOut(district);
	regionOutAndCityOut(district);
	showHideModal("show_city_select", 'show');
	showHideModal("modal_1", 'hide');
}

function districtOut(districts) {
	let inner = '<option value="" id="empty_district">Округ</option>';
	for (const key of Object.keys(districts)) {
		// console.log(district[key]['id'] + ' ' + district[key]['name'])
		inner = inner + '<option value="' + districts[key]['id'] + '">' + districts[key]['name'] + '</option>'
	}
	newData('shoose_district', inner);
	newData('shoose_region', '<option value="">Регион</option>');
	newData('shoose_city', '<option value="">Город</option>');
}

function regionOutAndCityOut(districts) {
	let shoose_district = document.querySelector('#shoose_district');
	if (shoose_district) {
		shoose_district.addEventListener('change', function () {
			let options_empty_district = document.querySelector('#empty_district');
			if (options_empty_district) {
				options_empty_district.remove();
			}

			let district_id = this.value;
			const district_text = this.options[this.selectedIndex].text;

			if (district_id) {
				let regions0 = districts[district_id];
				if (regions0) {
					let regions = regions0['regions'];
					regionOut(regions);
					cityOut(regions);
				}
			}
		})
	}
}

function regionOut(regions) {
	let inner = '<option value="" id="empty_region">Регион</option>';
	for (const key of Object.keys(regions)) {
		inner = inner + '<option value="' + regions[key]['id'] + '">' + regions[key]['name'] + '</option>'
	}
	let shoose_region = document.querySelector('#shoose_region');
	if (shoose_region) {
		shoose_region.disabled = false;
		shoose_region.innerHTML = inner;
	}
	let shoose_city = document.querySelector('#shoose_city');
	if (shoose_city) {
		shoose_city.innerHTML = '<option value="">Город</option>';
	}
}

function cityOut(regions) {
	let shoose_region = document.querySelector('#shoose_region');
	let shoose_city = document.querySelector('#shoose_city');
	if (shoose_region) {
		shoose_region.addEventListener('change', function () {
			let options_empty_region = document.querySelector('#empty_region');
			if (options_empty_region) {
				options_empty_region.remove();
			}
			let region_id = this.value;
			const region_text = this.options[this.selectedIndex].text;
			if (region_id) {
				let cities0 = regions[region_id];
				if (cities0) {
					let cities = cities0['cities'];
					if (cities) {
						let inner = '<option value="" id="empty_city">Город</option>';
						for (const key of Object.keys(cities)) {
							inner = inner + '<option value="' + cities[key]['id'] + '">' + cities[key]['name'] + '</option>'
						}
						if (shoose_city) {
							shoose_city.disabled = false;
							shoose_city.innerHTML = inner;
						}
					}
				}

				if (shoose_city) {
					shoose_city.addEventListener('change', function () {
						let options_empty_city = document.querySelector('#empty_city');
						if (options_empty_city) {
							options_empty_city.remove();
						}
						//let city_id = this.value;
						const city_text = this.options[this.selectedIndex].text;
					});
				}
			}
		})
	}
}
