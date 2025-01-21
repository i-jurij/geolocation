<?php

function model(string $city): string
{
    $data = [
        'nondefault' => '<p class="center">'.$city.'<br>Content after city choice</p>',
        'default' => '<p class="center">Content</p>',
    ];

    if ($city != 'default') {
        return $data['nondefault'];
    }
    if ($city == 'default') {
        return $data['default'];
    }
}
