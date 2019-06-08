<?php

namespace App\Entity\Location;

use AppBundle\Entity\Post;
use AppBundle\Util\Config\Config;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use NavigationBundle\Location\PostableLocation;

/**
 * Description of PostableLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_posting")
 */
class PostableLocationEntity extends LocationEntity {

    /**
     * The Posts of this location.
     * @var Post[]
     * @OneToMany(targetEntity="AppBundle\Entity\Post", mappedBy="location", fetch="EXTRA_LAZY")
     */
    protected $posts;

    public function __construct() {
        parent::__construct();
        $this->posts = new ArrayCollection();
    }

    public function getTemplate() {
        return 'locations/postableLocation';
    }

    public function getPosts() {
        return $this->posts;
    }

    public function setPosts(array $posts) {
        $this->posts = $posts;
    }

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new PostableLocation($manager, $uuid, $this, $config);
    }

}
