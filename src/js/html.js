function showHideModal(id, action) {
	const modal = document.getElementById(id);
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
	showHideModal("modal_1", 'hide');
	newData('footer_city_message', inner_message_footer);
	newData('section_city_choice', inner_choice_section);
	newData('footer_city_choice', inner_choice_footer);

	document.getElementById('shoose_location').onpointerdown = function () {
		showHideModal("show_city_select", 'show');
		showHideModal("modal_1", 'hide');
	};

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
