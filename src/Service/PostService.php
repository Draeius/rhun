<?php

namespace App\Service;

use App\Controller\Post\DuplicatePostException;
use App\Controller\Post\EmptyPostException;
use App\Entity\Character;
use App\Entity\Location;
use App\Entity\Post;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @author Draeius
 */
class PostService {

    public function createPost(Request $request, Character $character, Location $location) {
        $content = strip_tags(trim($request->get('content')));
        if ($content == '') {
            throw new EmptyPostException('Empty posts are not allowed.');
        }

        $post = new Post();
        $post->setContent($content);
        $post->setLocation($location);
        $post->setAuthor($character);

        if ($this->isDuplicatePost($character, $post)) {
            throw new DuplicatePostException('Duplicate posts are not allowed.');
        }
        return $post;
    }

}
