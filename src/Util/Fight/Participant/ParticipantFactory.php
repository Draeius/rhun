<?php

namespace App\Util\Fight\Participant;

use App\Entity\Character;
use App\Entity\Monster;
use App\Repository\CharacterRepository;
use App\Repository\MonsterRepository;
use App\Util\Fight\FighterInterface;

/**
 * Description of ParticipantFactory
 *
 * @author Draeius
 */
class ParticipantFactory {

    /**
     *
     * @var CharacterRepository
     */
    private $charRepo;

    /**
     *
     * @var MonsterRepository
     */
    private $monsterRepo;

    public function __construct(CharacterRepository $charRepo, MonsterRepository $monsterRepo) {
        $this->charRepo = $charRepo;
        $this->monsterRepo = $monsterRepo;
    }

    public function decodeParticipant(array $data) {
        $fighter = null;
        switch ($data['class']) {
            case CharacterParticipant::class:
                $fighter = $this->charRepo->find($data['fighterId']);
                break;
            case MonsterParticipant::class:
                $fighter = $this->monsterRepo->find($data['fighterId']);
                break;
        }
        $participant = self::FACTORY($fighter);
        if ($participant instanceof MonsterParticipant) {
            $participant->setCurrentHP($data['currentHP']);
        }
        return $participant;
    }

    public static function FACTORY(FighterInterface $fighter) {
        if ($fighter instanceof Character) {
            return new CharacterParticipant($fighter);
        }
        if ($fighter instanceof Monster) {
            return new MonsterParticipant($fighter);
        }
    }

}
