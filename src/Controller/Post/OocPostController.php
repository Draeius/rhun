<?php

namespace App\Controller\Post;

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
