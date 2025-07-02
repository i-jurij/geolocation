<?php
$unknown_location = 'Местоположение';
$scrf = Ijurij\Geolocation\Lib\Csrf::display();
$city = !empty($data['locality']['city']) ? $data['locality']['city'] : $unknown_location;
$region = !empty($data['locality']['region']) ? $data['locality']['region'] : '';

$checked = '';
$button = ' <noscript>
                <form action="" method="post" id="to_city_choice" class="left">
                    ' . $scrf . '
                </form>
                <input type="submit" form="to_city_choice" name="all_loc" value="Выбрать" class="button" />
            </noscript>';
if ($city === $unknown_location) {
    $checked = 'checked';
    if (!empty($data['locations']) && is_string($data['locations'])) {
        $message = 'Выбор местоположения невозможен. </br>Проверьте, разрешены ли куки, очистите кеш, закройте страницу и откройте заново.';
        // $message = htmlspecialchars($data['locations']);
        $button = '';
    } else {
        $message = 'Ваше местоположение неизвестно. </br>Выберите его, нажав на кнопку "Выбрать"';
    }

    // $button = '<label for="show_city_select" class="button button_shoose" id="shoose_location">Выбрать</label>';
} else {
    if ($city != $region) {
        $locality = '<span id="p_city">' . $city . '</span>'
            . '</br>'
            . '<span id="p_region">' . $region . '</span>';
    } else {
        $locality = '<span id="p_city">' . $city . '</span>';
    }
    $message = '<p>Ваше местоположение:</p><p>' . $locality . '</p><p>Если нет - выберите его, нажав на кнопку "Выбрать"</p>';
}

// for city choice form
if (!function_exists('alllocHtml')) {
    function alllocHtml(array $all_loc): string
    {
        $html = '';

        foreach ($all_loc['district'] as $district) {
            $html .= '<div class="checked1 mt2">';
            $html .= '  <input type="radio" name="district" id="' . $district['id'] . '" value="' . $district['name'] . '">
                        <label class="checkable" for="' . $district['id'] . '">' . $district['name'] . '</label>';

            $html .= '<div class="regions toggle1">';
            foreach ($district['regions'] as $k => $region) {
                $html .= '<div class="checked2 ml3 mt2">';
                $html .= '  <input type="radio" name="region" id="' . $district['id'] . '_' . $region['id'] . '" value="' . $region['name'] . '" >
                            <label class=" checkable" for="' . $district['id'] . '_' . $region['id'] . '">' . $region['name'] . '</label>';

                $html .= '<div class="cities toggle2  ml3 mt2">';
                foreach ($district['regions'][$k]['cities'] as $city) {
                    $html .= '  <label class="button" >
                                        <input type="radio" name="city" value="' . $city['name'] . '">
                                        <span class="checkable">' . $city['name'] . '</span>
                                    </label>';
                }
                $html .= '</div>';
                $html .= '</div>';
            }
            $html .= '</div>';
            $html .= '</div>';
        }

        return '<div class="">' . $html . '</div>';
    }
}

$checkd = '';
$alllochtml = '';
if (!empty($data['locations']['district']) && is_array($data['locations']['district'])) {
    $checkd = 'checked';
    $checked = '';
    $alllochtml = allLocHtml($data['locations']);
}

?>

<label for="city_region_info_div" class="">
    <span class="mr1">&#128205;</span>
    <span id="location">
        <?php echo $city; ?>
    </span>
    <!-- &ensp;&#8250; -->
</label>

<style type="text/css">
    <?php
    include_once Ijurij\Geolocation\Config::$style;
    ?>
</style>

<div class="modal" id="location_message_modal">
    <input id="city_region_info_div" type="checkbox" <?php echo $checked; ?> />
    <label for="city_region_info_div" class="overlay "></label>
    <article class="">
        <header class="bgcolor">
            <p>&nbsp;</p>
            <label for="city_region_info_div" class="close">&times;</label>
        </header>
        <section class="content bgcontent" id="clients_location_message">
            <?php echo $message; ?>
        </section>
        <footer class="bgcontent clearfix" id="footer_city_message">
            <?php echo $button; ?>
            <label for="city_region_info_div" class="button dangerous">
                Закрыть
            </label>
        </footer>
    </article>
</div>

<div class="modal" id="city_choice_modal">
    <input id="show_city_select" type="checkbox" <?php echo $checkd; ?> />
    <label for="show_city_select" class="overlay "></label>
    <article class="">
        <header class="bgcolor">
            <p>Выбор города</p>
            <label for="show_city_select" class="close">&times;</label>
        </header>
        <section class="content bgcontent" id="section_city_choice">
            <noscript>
                <form method="post" action="<?php echo $data['url_location_to_server']; ?>" id="form_city_choice">
                    <?php echo $scrf; ?>
                    <?php echo $alllochtml; ?>
                </form>
            </noscript>
        </section>
        <footer class="bgcontent" id="footer_city_choice">
            <noscript>
                <button class="submit" form="form_city_choice">
                    Выбрать
                </button>
                <label for="show_city_select" class="button dangerous">
                    Закрыть
                </label>
            </noscript>
        </footer>
    </article>
</div>

<script>
    let unknown_location = '<?php echo $unknown_location; ?>';
    let city_from_back = '<?php echo $city; ?>';
    let region_from_back = '<?php echo $region; ?>';
    let csrf_name = '<?php echo Ijurij\Geolocation\Lib\Csrf::$token_name; ?>';
    let csrf = '<?php echo Ijurij\Geolocation\Lib\Csrf::getToken(); ?>';
</script>