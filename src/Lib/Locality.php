<?php

declare(strict_types=1);

namespace Ijurij\Geolocation\Lib;

use Ijurij\Geolocation\Provider\Ipprovider;

final class Locality
{
    public function __construct(
        public string $ip_provider = 'geoplugin',// sypexgeo
        public string $lang = 'ru',
        private Ipprovider $provider = new Ipprovider(),
    ) {
        Session::start();
    }

    /**
     * if city and region isset.
     */
    public function isset(): bool
    {
        if (Session::has('city') && Session::has('region')) {
            return true;
        }

        return false;
    }

    /**
     * save city and region to session.
     */
    public function set(array $locality): void
    {
        Session::setArray([
            'city' => $locality['city'],
            'region' => (!empty($locality['region'])) ? $locality['region'] : '',
            // 'id' => (!empty($this->locality['id'])) ? $this->locality['id'] : '',
        ]);
    }

    /**
     * get city and region from POST if user manual city choice, from session or ipprovider.
     */
    public function get(): array
    {
        if ((Csrf::isValid() || Csrf::isRecent())
                && !empty($_POST['region']) && !empty($_POST['city'])) {
            $locality = [
                'city' => preg_replace("/[^\p{Cyrillic}\p{Latin}]/ui", '', filter_input(INPUT_POST, 'city', FILTER_SANITIZE_SPECIAL_CHARS)),
                'region' => preg_replace("/[^\p{Cyrillic}\p{Latin}]/ui", '', filter_input(INPUT_POST, 'region', FILTER_SANITIZE_SPECIAL_CHARS)),
            ];

            $this->set($locality);

            return $locality;
        }

        if (Session::has('city')) {
            return [
                'city' => Session::get('city'),
                'region' => (Session::has('region')) ? Session::get('region') : '',
            ];
        }

        $locality = $this->getFromIpprovider();
        $this->set($locality);

        return $locality;
    }

    /**
     * get city and region from ipprovider.
     */
    public function getFromIpprovider(): array
    {
        $this->provider->ip_provider = $this->ip_provider;
        $this->provider->lang = $this->lang;

        return $this->provider->getLocality();
    }
}
