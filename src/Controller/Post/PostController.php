<?php

namespace App\Controller\Post;

use App\Controller\BasicController;
use App\Entity\Character;
use App\Entity\Post;
use App\Entity\ServerSettings;
use App\Query\GetPostCountQuery;
use App\Repository\CharacterRepository;
use App\Repository\LocationRepository;
use App\Repository\PostRepository;
use App\Service\ConfigService;
use App\Service\PlayerReward\RewardDistributor;
use App\Service\PostService;
use App\Util\Config\RolePlayConfig;
use App\Util\Price;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @author Draeius
 * @Route("post")
 * @App\Annotation\Security(needAccount=true)
 */
class PostController extends BasicController {

    const GET_PAGE_ROUTE_NAME = 'post_page';
    const EDIT_POST_ROUTE_NAME = 'post_edit';
    
    /**
     * 
     * @Route("/page/{uuid}/{page}", name=PostController::ADD_POST_ROUTE_NAME)
     * @App\Annotation\Security(needCharacter=true)
     */
    public function getPage(int $page, EntityManagerInterface $eManager, CharacterRepository $charRepo, PostRepository $postRepo) {
        $session = new RhunSession();
        $settings = $this->getServerSettings();

        /* @var $character Character */
        $character = $charRepo->find($session->getCharacterID());
        $posts = $postRepo->findByLocation($character->getLocation(), $character->getAccount()->getPostsPerPage(), $page);

        $query = new GetPostCountQuery($eManager);
        $pages = ceil($query() / $character->getAccount()->getPostsPerPage());

        return new JsonResponse($this->preparePostArray($posts, $character, $settings, $eManager, $pages));
    }

    /**
     * @App\Annotation\Security(needCharacter=true)
     * @Route("/add/{uuid}")
     */
    public function addPost(Request $request, EntityManagerInterface $eManager, CharacterRepository $charRepo, LocationRepository $locRepo, PostService $pService, ConfigService $config) {
        if($request->get('id')){
            return $this->redirectToRoute(PostController::EDIT_POST_ROUTE_NAME);
        }
        $session = new RhunSession();
        /* @var $character Character */
        $character = $charRepo->find($session->getCharacterID());
        $location = $this->getPostLocation($request, $character, $locRepo);
        try {
            $post = $pService->createPost($request, $character, $location);
        } catch (EmptyPostException | DuplicatePostException $ex) {
            return new JsonResponse(array(
                'error' => $ex->getMessage()
            ));
        }

        //add Reward
        /* @var $config RolePlayConfig */
        $distributor = new RewardDistributor($config->getRpConfig());
        $distributor->handOutReward($post, $character);

        //persist db
        $eManager->persist($post);
        $eManager->persist($character);
        $eManager->flush();
        return new JsonResponse(array(
            'success' => true
        ));
    }

    /**
     * @App\Annotation\Security(needCharacter=true)
     * @Route("/get/last/{uuid}")
     */
    public function getLastPost(Request $request, PostRepository $postRep) {
        $post = $postRep->findLastOwnPost($this->getCharacter($uuid), filter_var($request->get("ooc"), FILTER_VALIDATE_BOOLEAN));

        if (!$post) {
            return new JsonResponse(array());
        }

        return new JsonResponse(array(
            "text" => $post->getContent(),
            "id" => $post->getId()
        ));
    }

    /**
     * @App\Annotation\Security(needCharacter=true)
     * @Route("/edit/{uuid}", name=PostController::EDIT_POST_ROUTE_NAME)
     * @param Request $request
     * @param type $uuid
     * @return JsonResponse
     */
    public function editPost(Request $request, $uuid) {
        $rep = $this->getDoctrine()->getRepository('App:Post');

        $post = $rep->findLastOwnPost($this->getCharacter($uuid), filter_var($request->get("ooc"), FILTER_VALIDATE_BOOLEAN));

        //check if there is any post
        if (!$post) {
            return new JsonResponse(array('error' => 'Der letzte Post ist nicht von dir. Du kannst ihn nicht bearbeiten.'));
        }
        // set old reward
        $oldReward = $this->getReward($post);

        //strip html tags
        $content = strip_tags($request->get('content'));
        $post->setContent($content);

        // set new reward after changing content
        $newReward = $this->getReward($post);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush($post);

        // check rewards after updating post content in db
        $character = $this->getCharacter($uuid);
        $this->checkReward($oldReward, $newReward, $character);
        return new JsonResponse();
    }

    /**
     * @App\Annotation\Security(needCharacter=true)
     * @Route("/delete/{uuid}")
     * @param Request $request
     * @param type $uuid
     * @return JsonResponse
     */
    public function deletePost(Request $request, $uuid) {
        $character = $this->getCharacter($uuid);
        /* @var $rep PostRepository */
        $rep = $this->getDoctrine()->getRepository('App:Post');
        $post = $rep->findLastPost($character, filter_var($request->get("ooc"), FILTER_VALIDATE_BOOLEAN));

        //check if there is any post
        if (!$post || $post->getAuthor()->getId() != $character->getId()) {
            return new JsonResponse(array('error' => 'Der letzte Post ist nicht von dir. Du kannst ihn nicht löschen.'));
        }

        // set old reward
        $oldReward = $this->getReward($post);

        //delete post
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();

        // check rewards after updating post content in db
        if ($post->getLocation()->getId() != 1) {
            $config = $this->getServerConfig()->getRpConfig();
            $this->checkReward($oldReward, new Price(0, 0, 0), $character);
            $length = count(explode(' ', $post->getContent()));
            if ($length > $config->getPostRewardMinWordCount()) {
                $character->setPostCounter(
                        $character->getPostCounter() - $config->getRpPointRewardMultiplier() * (ceil($length / $config->getPostRewardMaxWordCount()))
                );
            }
            $entityManager->flush($character);
        }
        return new JsonResponse();
    }

    private function preparePostArray($posts, ?Character $character, ServerSettings $settings, EntityManagerInterface $eManager, int $pages) {
        $session = new RhunSession();
        $content = ['posts' => [], 'pageCount' => $pages];

        foreach ($posts as $p) {
            $content['posts'] = [
                'text' => $this->render('parts/post.html.twig', [
                    'post' => $p,
                    'char' => $character,
                    'uuid' => $session->getTabIdentifier()->getIdentifier(),
                    'content' => PostService::printPost($p, $eManager, $settings->getUseMaskedBios())
                ])->getContent(),
                'id' => $p->getId()
            ];
        }
    }

    private function getPostLocation(Request $request, Character $character, LocationRepository $locRepo) {
        $rep = $this->getDoctrine()->getRepository('App:Location');
        if (filter_var($request->get('ooc'), FILTER_VALIDATE_BOOLEAN)) {
            return $rep->find(1);
        }
        return $rep->find($character->getLocation());
    }

}
