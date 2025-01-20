<?php

declare(strict_types=1);

namespace Ijurij\Geolocation\Mvc;

use Ijurij\Geolocation\Config;

final class Model
{
    public $db;

    public function __construct()
    {
        $path_to_db = Config::SRC_DIR.DIRECTORY_SEPARATOR.'sqlite'.DIRECTORY_SEPARATOR.Config::$db;

        $dsn = "sqlite:$path_to_db";

        $opt = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ];

        if ($this->db == null) {
            $this->db = new \PDO($dsn, null, null, $opt);
        }
    }

    /**
     * Get all location for manual user choice if js disabled.
     * Send json in response.
     * Response is array of nested arrays: [district [regions => [cities]]].
     */
    public function getAll(): array
    {
        $query = '  SELECT  d.id AS district_id, d.name AS district, 
                            r.id AS region_id, r.name AS region,
                            c.id AS city_id, c.name AS city
                    FROM `geo_district` AS d
                    INNER JOIN `geo_regions` AS r ON d.id = r.district_id
                        INNER JOIN `geo_city` AS c ON r.id = c.region_id
        ';

        $rows = $this->db->query($query);

        foreach ($rows as $row) {
            $res['district'][$row['district_id']]['id'] = $row['district_id'];
            $res['district'][$row['district_id']]['name'] = $row['district'];

            $res['district'][$row['district_id']]['regions'][$row['region_id']]['id'] = $row['region_id'];
            $res['district'][$row['district_id']]['regions'][$row['region_id']]['name'] = $row['region'];

            $res['district'][$row['district_id']]['regions'][$row['region_id']]['cities'][$row['city_id']]['id'] = $row['city_id'];
            $res['district'][$row['district_id']]['regions'][$row['region_id']]['cities'][$row['city_id']]['name'] = $row['city'];
        }

        return $res ?? [];
    }

    /**
     * Get location by coordinates.
     * Response is array of nested arrays: [district [regions => [cities]]].
     */
    public function fromCoord($long, $lat)
    {
        $regex_lat = '/'.Config::REGEX_LAT.'/';
        $regex_long = '/'.Config::REGEX_LONG.'/';

        if (\preg_match($regex_lat, $lat) && \preg_match($regex_long, $long)) {
            $area = (1 / 111) * 100; // ~100km (1° ~ 111 км, 1 км = 1 / 111 = 0,009009009009009°.)

            $lat_dist_minus = (float) $lat - $area;
            $lat_dist_plus = (float) $lat + $area;
            $long_dist_minus = (float) $long - $area;
            $long_dist_plus = (float) $long + $area;

            $query = 'SELECT `city`, `region`, `id`
                                    FROM (
                                            SELECT `id`, `city`, `region`, `distance`
                                                FROM (
                                                        SELECT `gc`.`id`, `gc`.`name` AS city, `r`.`name` AS region,
                                                            ACOS(SIN(PI()*gc.latitude/180.0)*SIN(PI()*:lat1/180.0)
                                                                +COS(PI()*gc.latitude/180.0)*COS(PI()*:lat2/180.0)
                                                                *COS(PI()*:long/180.0-PI()*gc.longitude/180.0))*6371 AS distance
                                                        FROM `geo_city` AS gc
                                                        INNER JOIN `geo_regions` AS r ON `r`.`id` = `gc`.`region_id`
                                                        WHERE gc.latitude BETWEEN :lat_dist_minus AND :lat_dist_plus
                                                        AND gc.longitude BETWEEN :long_dist_minus AND :long_dist_plus
                                                ) AS subquery
                                            ORDER BY distance
                                            LIMIT 5
                                    ) AS limited
                                    ORDER BY distance
                                    LIMIT 1;';

            $pre = $this->db->prepare($query);
            $pre->bindParam('lat1', $lat);
            $pre->bindParam('lat2', $lat);
            $pre->bindParam('long', $long);
            $pre->bindParam('lat_dist_minus', $lat_dist_minus);
            $pre->bindParam('lat_dist_plus', $lat_dist_plus);
            $pre->bindParam('long_dist_minus', $long_dist_minus);
            $pre->bindParam('long_dist_plus', $long_dist_plus);

            if ($pre != false && $pre->execute()) {
                $locality = $pre->fetch();
            }
        }

        return (is_array($locality)) ? $locality : [];
    }

    public function getDistrict($city): array
    {
        $query = '  SELECT  d.name AS district, 
                            r.name AS region,
                            c.name AS city
                    FROM `geo_city` AS c
                    INNER JOIN `geo_regions` AS r ON d.id = r.district_id
                    INNER JOIN `geo_district` AS d ON r.id = c.region_id
                    WHERE `c`.`name` LIKE :ci
                    LIMIT 1
        ';
        $pre = $this->db->prepare($query);
        $c = $city.'%';
        $pre->bindParam(':ci', $c);
        if ($pre != false && $pre->execute()) {
            $geo = $pre->fetch();
        }

        return is_array($geo) ? $geo : [];
    }
}
