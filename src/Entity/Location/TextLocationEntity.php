<?php

namespace App\Entity\Location;

use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Location\LocationEntity;

/**
 * Description of TextLocation
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_text")
 */
class TextLocationEntity extends LocationEntity {

    /**
     *
     * @var string[]
     * @Column(type="array", nullable=true)
     */
    private $texts;

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new TextLocation($manager, $uuid, $this, $config);
    }

    public function getTemplate() {
        return 'locations/textLocation';
    }

    public function getTexts() {
        return $this->texts;
    }

    public function setTexts($texts) {
        $this->texts = $texts;
    }

}
