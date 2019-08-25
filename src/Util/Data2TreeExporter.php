<?php

namespace App\Util;

use App\Entity\Navigation;

/**
 * Description of Data2TreeExporter
 *
 * @author Draeius
 */
class Data2TreeExporter {

    public static function exportLocationData($locations) {
        $data = [];
        foreach ($locations as $location) {
            $data[] = ['id' => $location->getId(), 'label' => $location->getName()];
        }
        return json_encode($data);
    }

    public static function exportNavData($navigations) {
        $data = [];
        foreach ($navigations as $nav) {
            if (!self::dataContainsNav($nav, $data)) {
                $data[] = ['from' => $nav->getLocation()->getId(), 'to' => $nav->getTargetLocation()->getId(), 'arrows' => 'from,to'];
            }
        }
        return json_encode($data);
    }

    private static function dataContainsNav(Navigation $nav, array $data) {
        foreach ($data as $navData) {
            if ($navData['from'] == $nav->getLocation()->getId() && $navData['to'] == $nav->getTargetLocation()->getId()) {
                return true;
            }
            if ($navData['to'] == $nav->getLocation()->getId() && $navData['from'] == $nav->getTargetLocation()->getId()) {
                return true;
            }
        }
        return false;
    }

}
