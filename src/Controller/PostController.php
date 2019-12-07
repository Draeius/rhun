<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 *
 * @author Draeius
 */
class PostController extends BasicController {

    public function getPage(int $page, PostRepository $postRepo) {
        $posts = $postRepo->findBy([]);

        $content = [];
        foreach ($posts as $p) {
            $content[] = [
                
            ];
        }

        return new JsonResponse($content);
    }

}
