function outLoc() {
	if (city_from_back != region_from_back) {
		return '<p id="p_city">' + city_from_back + '</p>' +
			'<p id="p_region">' + region_from_back + '</p>';
	} else {
		return '<p id="p_city">' + city_from_back + '</p>';
	}
}

let inner = '<div class="">\
	<label for="modal_1" class="">\
		<span class="mr1">&#128205;</span>\
		<span id="location">'
	+ city_from_back + '&ensp;&#8250;\
		</span>\
	</label>\
	<div class="modal">\
		<input id="modal_1" type="checkbox" />\
		<label for="modal_1" class="overlay "></label>\
		<article class="">\
			<header class="bgcolor">\
				<p>&nbsp;</p>\
				<label for="modal_1" class="close">&times;</label>\
			</header>\
			<section class="content bgcontent" id="clients_location_message">'
	+ outLoc() +
	'</section >\
			<footer class="bgcontent">\
				<label for="show_city_select" class="button button_shoose" id="shoose_location">\
					Выбрать\
				</label>\
				<label for="modal_1" class="button dangerous">\
					Закрыть\
				</label>\
			</footer>\
		</article >\
	</div >\
	<div class="modal">\
		<input id="show_city_select" type="checkbox" />\
		<label for="show_city_select" class="overlay "></label>\
		<article class="">\
			<header class="bgcolor">\
				<p>Выбор города</p>\
				<label for="show_city_select" class="close">&times;</label>\
			</header>\
			<section class="content bgcontent" id="">\
				<p>По названию:</p>\
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
				</select>\
			</section>\
			<footer class="bgcontent">\
				<button id="save_city" class="button">\
					Выбрать\
				</button>\
				<label for="show_city_select" class="button dangerous">\
					Закрыть\
				</label>\
			</footer>\
		</article>\
	</div>\
</div >';

export function html() {
	let el = document.querySelector('#location_div');
	if (el) {
		el.innerHTML = inner;
	}
};