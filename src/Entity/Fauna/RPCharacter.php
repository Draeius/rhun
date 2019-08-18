<?php

namespace App\Entity\Fauna;

use App\Entity\Biography;
use App\Entity\Guild;
use App\Entity\Message;
use App\Entity\Race;
use App\Service\DateTimeService;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Description of RPCharacter
 *
 * @author Draeius
 */
class RPCharacter extends CharacterBase {

    /**
     * The character's race
     * @var Race
     * @ManyToOne(targetEntity="Race")
     * @JoinColumn(name="race_id", referencedColumnName="id")
     */
    protected $race;

    /**
     * This character's biography
     * @var ArrayCollection 
     * @OneToMany(targetEntity="Biography", mappedBy="owner", fetch="EXTRA_LAZY", cascade={"remove"})
     */
    protected $biographies;

    /**
     * The messages this Character has sent
     * @var ArrayCollection
     * @OneToMany(targetEntity="Message", mappedBy="sender", fetch="EXTRA_LAZY", cascade={"remove"})
     */
    protected $messageSent;

    /**
     * The messages this character has received
     * @var ArrayCollection
     * @OneToMany(targetEntity="Message", mappedBy="addressee", fetch="EXTRA_LAZY")
     */
    protected $receivedMessages;

    /**
     * 
     * @var Guild 
     * @ManyToOne(targetEntity="Guild", inversedBy="members")
     * @JoinColumn(name="guild_id", referencedColumnName="id")
     */
    protected $guild;

    /**
     * 
     * @var int 
     * @Column(type="integer") 
     */
    protected $postCounter = 0;

    /**
     * If this character is considered to be online
     * @var bool 
     * @Column(type="boolean", nullable=false, name="is_online")
     */
    protected $online = false;

    /**
     *
     * @var boolean
     * @Column(type="boolean", nullable=false)
     */
    protected $rpState = false;

    /**
     * The last time the player was active
     * @var DateTime 
     * @Column(type="datetime", nullable=false)
     */
    protected $lastActive;

    public function __construct() {
        parent::__construct();
        $this->lastActive = DateTimeService::getDateTime("now");
        $this->biographies = new ArrayCollection();
        $this->messageSent = new ArrayCollection();
        $this->receivedMessages = new ArrayCollection();
    }

    public function getActiveBiography(bool $masked = false): ?Biography {
        foreach ($this->getBiography() as $bio) {
            if ($masked && $bio->getMaskedBall()) {
                return $bio;
            } elseif (!$masked && $bio->getSelected()) {
                return $bio;
            }
        }
        return null;
    }

    public function changeRPState(): void {
        $this->rpState = !$this->rpState;
    }

    public function updateLastActive(): void {
        $this->lastActive = DateTimeService::getDateTime('NOW');
    }

    public function getRace(): ?Race {
        return $this->race;
    }

    public function getBiographies(): ArrayCollection {
        return $this->biographies;
    }

    public function getMessageSent(): ArrayCollection {
        return $this->messageSent;
    }

    public function getReceivedMessages(): ArrayCollection {
        return $this->receivedMessages;
    }

    public function getGuild(): ?Guild {
        return $this->guild;
    }

    public function getPostCounter(): int {
        return $this->postCounter;
    }

    public function getOnline(): bool {
        return $this->online;
    }

    public function getRpState(): bool {
        return $this->rpState;
    }

    public function getLastActive(): DateTime {
        return $this->lastActive;
    }

    public function setLastActive(DateTime $lastActive) {
        $this->lastActive = $lastActive;
    }

    public function setRace(Race $race): void {
        $this->race = $race;
    }

    public function setBiographies(array $biographies): void {
        $this->biographies = $biographies;
    }

    public function setMessageSent(array $messageSent): void {
        $this->messageSent = $messageSent;
    }

    public function setReceivedMessages(array $receivedMessages): void {
        $this->receivedMessages = $receivedMessages;
    }

    public function setGuild(Guild $guild): void {
        $this->guild = $guild;
    }

    public function setPostCounter($postCounter): void {
        $this->postCounter = $postCounter;
    }

    public function setOnline($online): void {
        $this->online = $online;
    }

    public function setRpState($rpState): void {
        $this->rpState = $rpState;
    }

}
