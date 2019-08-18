<?php

namespace App\Entity\Partial;

use App\Entity\ColoredName;
use App\Entity\Partial\Interfaces\CharacterNameInterface;
use App\Entity\Title;

/**
 * 
 *
 * @author Draeius
 */
class CharacterNamePartial implements CharacterNameInterface {

    /**
     *
     * @var ColoredName
     */
    protected $name;

    /**
     *
     * @var Title
     */
    protected $title;

    /**
     *
     * @var GuildCharListPartial
     */
    protected $guild;

    public function getFullName(): string {
        if ($this->title->getIsInFront()) {
            return $this->title->getTitle() . ' ' . $this->name->getName();
        }
        return $this->name->getName() . ' ' . $this->title->getTitle();
    }

    public function getSelectedName(): ?ColoredName {
        return $this->name;
    }

    public function getSelectedTitle(): ?Title {
        return $this->title;
    }

    function getGuild(): ?GuildCharListPartial {
        return $this->guild;
    }

    public static function FROM_DATA(array $data): CharacterNamePartial {
        $partial = new self();

        $partial->fromData($data);

        return $partial;
    }

    public function getDisplayName(bool $useMasked): string {
        @trigger_error('Not fully implemented yet', E_USER_WARNING);
        return $this->getFullName();
    }

    protected function fromData(array $data) {
        $this->name = new ColoredName();
        $this->name->setName($data['coloredName'] ? $data['coloredName'] : $data['name']);
        $this->name->setIsActivated(true);

        $this->title = new Title();
        if ($data['title'] === null) {
            $this->title->setIsInFront(true);
            $this->title->setTitle($data['gender'] ? Title::STANDARD_MALE : Title::STANDARD_FEMALE);
        } else {
            $this->title->setIsInFront($data['isInFront']);
            $this->title->setTitle($data['title']);
        }
        if (array_key_exists('guildName', $data)) {
            $this->guild = GuildCharListPartial::fromData(['name' => $data['guildName'], 'tag' => $data['guildTag']]);
        }
        $this->title->setIsActivated(true);
    }

}
