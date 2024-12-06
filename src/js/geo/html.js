function outLoc(string) {
	let con = string + '_from_back';
	if (window[con]) {
		return '<p id="p_' + string + '">' + window[con] + '</p>';
	}
}

let inner = '<div class="">\
	<label for="modal_1" class="pseudo button">\
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
				<h3>&nbsp;</h3>\
				<label for="modal_1" class="close">&times;</label>\
			</header>\
			<section class="content bgcontent" id="clients_location_message">'
	+ outLoc('city') + outLoc('region') +
	'</section >\
	<footer class="bgcontent">\
		<label for="show_city_select" class="button" id="shoose_location">\
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
				<h3>&nbsp;</h3>\
				<label for="show_city_select" class="close">&times;</label>\
			</header>\
			<section class="content bgcontent" id="">\
				<input type="text" name="city_search_input" id="city_search_input" placeholder="Поиск">\
					<select id="shoose_district" class="select mb1">\
						<option>Округ</option>\
					</select>\
					<select id="shoose_region" class="select mb1" disabled>\
						<option>Регион (область)</option>\
					</select>\
					<select id="shoose_city" class="select" disabled>\
						<option>Город</option>\
					</select>\
			</section>\
			<footer class="bgcontent">\
				<button id="save_city">\
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