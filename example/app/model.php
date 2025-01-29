<?php

function model(string $city): string
{
    $text = (!empty($city)) ? $city : 'Location not yet determined';

    return '<p class="center">'.$text.'</p>';
}
