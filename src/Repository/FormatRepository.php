<?php

namespace App\Repository;

use App\Entity\Format;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Description of Formatrepository
 *
 * @author Draeius
 */
class FormatRepository extends ServiceEntityRepository {

    private static $formats = [];

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry) {
        parent::__construct($managerRegistry, Format::class);
    }

    public function findByCode(string $code): Format {
        if (array_key_exists($code, self::$formats)) {
            return self::$formats[$code];
        }
        $criteria = Criteria::create()
                ->where(Criteria::expr()
                ->eq('code', $code)
        );
        $formats = $this->matching($criteria);
        foreach ($formats as $format) {
            if ($format->getCode() == $code) {
                self::$formats[$code] = $format;
                return $format;
            }
        }
        self::$formats[code] = Format();
        return new Format();
    }

    public function loadFormats(array $tags) {
        foreach ($tags as $key => $value) {
            if (array_key_exists($value, self::$formats)) {
                unset($tags[$key]);
            }
        }
        if (empty($tags)) {
            return;
        }

        $formats = $this->findBy(['code' => $tags]);

        foreach ($formats as $format) {
            self::$formats[$format->getCode()] = $format;
        }
    }

}
