<?php

namespace App\Controller\Post;

use App\Entity\Character;
use App\Query\GetPostCountQuery;
use App\Repository\CharacterRepository;
use App\Repository\LocationRepository;
use App\Repository\PostRepository;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @author Draeius
 * @Route("post")
 * @App\Annotation\Security(needAccount=true)
 */
class OocPostController extends PostController {

    /**
     * 
     * @Route("/ooc/page/{uuid}/{page}", name="ooc_page")
     */
    public function getOOCPage(int $page, LocationRepository $locRepo, CharacterRepository $charRepo, EntityManagerInterface $eManager, PostRepository $postRepo) {
        $session = new RhunSession();
        $settings = $this->getServerSettings();

        $character = null;
        if ($session->getTabIdentifier()->hasIdentifier()) {
            /* @var $character Character */
            $character = $charRepo->find($session->getCharacterID());
        }
        $posts = $postRepo->findByLocation($locRepo->find(1), $character->getAccount()->getPostsPerPage(), $page);

        $query = new GetPostCountQuery($eManager);
        $pages = ceil($query() / $character->getAccount()->getPostsPerPage());

        return new JsonResponse($this->preparePostArray($posts, $character, $settings, $eManager, $pages));
    }

}
