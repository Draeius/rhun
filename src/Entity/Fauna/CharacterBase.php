<?php

namespace App\Entity\Fauna;

use App\Entity\ColoredName;
use App\Entity\LocationBasedEntity;
use App\Entity\Partial\Interfaces\CharacterNameInterface;
use App\Entity\Title;
use App\Entity\Traits\EntityCreatedTrait;
use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntityIsNewestTrait;
use App\Entity\User;
use App\Service\DateTimeService;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of CharacterBase
 *
 * @author Draeius
 * @MappedSuperclass
 * @HasLifecycleCallbacks
 * @Table(indexes={@Index(name="newest_idx", columns={"newest"}), @Index(name="uuid_idx", columns={"uuid"})})
 */
abstract class CharacterBase extends LocationBasedEntity implements CharacterNameInterface {

    use EntityIdTrait;
    use EntityIsNewestTrait;
    use EntityCreatedTrait;

    /**
     * The character's owning account
     * @var User 
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="account_id", referencedColumnName="id")
     */
    protected $account;

    /**
     * The name of this character. This may not contain any colorcodes
     * @var string 
     * @Column(type="string", length=32)
     */
    protected $name;

    /**
     * The characters titles
     * @var string 
     * @OneToMany(targetEntity="ColoredName", mappedBy="owner", fetch="EXTRA_LAZY")
     */
    protected $coloredNames;

    /**
     * The characters titles
     * @var string 
     * @OneToMany(targetEntity="Title", mappedBy="owner", fetch="EXTRA_LAZY", cascade={"remove"})
     */
    protected $titles;

    /**
     * The character's gender
     * @var bool
     * @Column(type="boolean", nullable=false)
     */
    protected $gender;

    /**
     *
     * @var DateTime
     * @Column(type="datetime", nullable=true)
     */
    protected $ban = null;

    /**
     *
     * @var string
     * @Column(type="text", nullable=true)
     */
    protected $banReason = null;

    public function __construct() {
        $this->titles = new ArrayCollection();
        $this->coloredNames = new ArrayCollection();
    }

    /**
     * Gibt an, ob der Charakter aktuell noch gebannt ist.
     * 
     * @return boolean
     */
    public function isBanned(): bool {
        if ($this->ban == null) {
            return false;
        }
        return $this->ban > DateTimeService::getDateTime('now');
    }

    /**
     * Fügt einen neuen farbigen Namen hinzu, sofern es diesen nicht schon gibt.
     * @param ColoredName $name
     * @return bool true, wenn der Name hinzugefügt wurde
     */
    public function addColoredName(ColoredName $name): bool {
        if ($this->hasColoredName($name)) {
            return false;
        }
        $this->coloredNames->add($name);
        $name->setOwner($this);
        return true;
    }

    /**
     * Gibt an, ob der Charakter den gegebenen farbigen Namen bereits hat.
     * @param ColoredName $name
     * @return bool
     */
    public function hasColoredName(ColoredName $name): bool {
        foreach ($this->coloredNames as $oldName) {
            if ($oldName->getName() == $name->getName()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Fügt einen neuen farbigen Titel hinzu, sofern es diesen nicht schon gibt.
     * @param Title $title
     * @return bool true, wenn der Titel hinzugefügt wurde.
     */
    public function addTitle(Title $title): bool {
        if ($this->hasTitle($title)) {
            return false;
        }
        $this->titles->add($title);
        $title->setOwner($this);
        return true;
    }

    /**
     * Gibt an, ob der Charakter den gegebenen Titel bereits hat.
     * @param Title $title
     * @return bool
     */
    public function hasTitle(Title $title): bool {
        foreach ($this->titles as $oldTitle) {
            if ($oldTitle->getTitle() == $title->getTitle()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Gibt den ausgewählten farbigen Namen zurück.
     * @return ColoredName|null Null, wenn kein farbiger Name gewählt wurde.
     */
    public function getSelectedName(): ?ColoredName {
        foreach ($this->coloredNames as $name) {
            if ($name->getIsActivated()) {
                return $name;
            }
        }
        $name = new ColoredName();
        $name->setName($this->name);
        return $name;
    }

    /**
     * Gibt den ausgewählten Titel zurück
     * @return Title|null Null, wenn kein Titel ausgewählt wurde.
     */
    public function getSelectedTitle(): ?Title {
        foreach ($this->titles as $title) {
            if ($title->getIsActivated()) {
                return $title;
            }
        }
        $title = new Title();
        $title->setIsInFront(true);
        $title->setTitle($this->gender ? Title::STANDARD_MALE : Title::STANDARD_FEMALE);
        return $title;
    }

    public function getFullName(): string {
        if ($this->getSelectedTitle()->getIsInFront()) {
            return $this->getSelectedTitle()->getTitle() . ' ' . $this->getSelectedName()->getName();
        }
        return $this->getSelectedName()->getName() . ' ' . $this->getSelectedTitle()->getTitle();
    }

    public function getDisplayName(bool $useMasked): string {
        @trigger_error('Not fully implemented yet', E_USER_WARNING);
        return $this->getFullName();
    }

    /**
     * Aktiviert den gegebenen farbigen Namen.
     * @param ColoredName $coloredName
     * @return void
     */
    public function activateColoredName(ColoredName $coloredName): void {
        foreach ($this->coloredNames as $other) {
            $other->setIsActivated($coloredName->getId() == $other->getId());
        }
    }

    /**
     * Aktiviert den gegebenen Titel
     * @param Title $title
     * @return void
     */
    public function activateTitle(Title $title): void {
        foreach ($this->titles as $other) {
            $other->setIsActivated($title->getId() == $other->getId());
        }
    }

    protected function addToValue(int $amount, int $current, int $max, bool $allowNegative = false) {
        $current += $amount;
        if ($current >= $max) {
            $current = $max;
        }
        if ($current <= 0 && !$allowNegative) {
            $current = 0;
        }
        return $current;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function getColoredNames(): ArrayCollection {
        return $this->coloredNames;
    }

    public function getTitles(): ArrayCollection {
        return $this->titles;
    }

    public function getGender(): ?bool {
        return $this->gender;
    }

    public function getBan(): DateTime {
        return $this->ban;
    }

    public function getBanReason() {
        return $this->banReason;
    }

    function getAccount(): User {
        return $this->account;
    }

    public function setAccount(User $account) {
        $this->account = $account;
    }

    public function setName($name): void {
        $this->name = $name;
    }

    public function setGender($gender) {
        $this->gender = $gender;
    }

    public function setBan(DateTime $ban) {
        $this->ban = $ban;
    }

    public function setBanReason(?string $banReason) {
        $this->banReason = $banReason;
    }

}
