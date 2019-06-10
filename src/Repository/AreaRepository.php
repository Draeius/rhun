<?php

namespace App\Repository;

use App\Entity\Area;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Repository für alle areas
 *
 * @author Draeius
 */
class AreaRepository extends ServiceEntityRepository {

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry) {
        parent::__construct($managerRegistry, Area::class);
    }

    public function getAreasAllowingRaces() {
        return $this->findBy(['raceAllowed' => true]);
    }

    public static function findColoredCityNames(): array {
        return array(
            'pyra' => '`JPyra',
            'lerentia' => '`tLerentia',
            'nelaris' => '`?Nelaris',
            'manosse' => '`9Manosse',
            'underworld' => '`.Unterwelt',
            'olymp' => '`gUnterwelt'
        );
    }

    public static function getColoredCityName(string $city): string {
        $data = self::findColoredCityNames();
        if (array_key_exists($city, $data)) {
            return $data[$city];
        }
        return $city;
    }

    public function getDescriptionOfMajorAreas(): array {
        $dql = 'SELECT a FROM App\Entity\Area a WHERE a.id IN (1,2,3,4,5,6) ORDER BY a.id ASC';
        $query = $this->getEntityManager()->createQuery($dql);

        $cities = $query->getResult();
        return $this->buildArray($cities);
    }

    private function buildArray(array $data): array {
        return ['seiya' => [
                'name' => $data[0]->getName(),
                'descr' => $data[0]->getDescription()
            ],
            'nelaris' => [
                'name' => $data[1]->getName(),
                'descr' => $data[1]->getDescription()
            ],
            'manosse' => [
                'name' => $data[2]->getName(),
                'descr' => $data[2]->getDescription()
            ],
            'pyra' => [
                'name' => $data[3]->getName(),
                'descr' => $data[3]->getDescription()
            ],
            'lerentia' => [
                'name' => $data[4]->getName(),
                'descr' => $data[4]->getDescription()
            ],
            'underworld' => [
                'name' => $data[5]->getName(),
                'descr' => $data[5]->getDescription()
        ]];
    }

}
