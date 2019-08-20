<?php

namespace App\Entity;

use App\Entity\Traits\EntityCreatedTrait;
use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntityIsNewestTrait;
use App\Entity\Traits\EntityOwnerTrait;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of Post
 *
 * @author Draeius
 * @Entity(repositoryClass="App\Repository\PostRepository")
 * @Table(name="posts")
 * @HasLifecycleCallbacks
 */
class Post extends LocationBasedEntity {

    use EntityIdTrait;
    use EntityIsNewestTrait;
    use EntityCreatedTrait;
    use EntityOwnerTrait;

    /**
     * The content of this post
     * @var string
     * @Column(type="text")
     */
    protected $content;

    public function getContent() {
        return $this->content;
    }

    public function setAuthor(Character $author) {
        $this->author = $author;
    }

}
