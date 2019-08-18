<?php

namespace App\Controller\Entity;

use App\Controller\BasicController;
use App\Entity\Race;
use App\Form\RaceType;
use App\Repository\RaceRepository;
use App\Service\ParamGenerator\EditorParamGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/race")
 * @App\Annotation\Security(needAccount=true, editWorld=true)
 */
class RaceController extends BasicController {

    /**
     *
     * @var EditorParamGenerator
     */
    private $paramGenerator;

    function __construct(EditorParamGenerator $paramGenerator) {
        $this->paramGenerator = $paramGenerator;
    }

    /**
     * @Route("/", name="race_index", methods={"GET"})
     */
    public function index(RaceRepository $raceRepository): Response {
        return $this->render($this->getSkinFile(), array_merge($this->paramGenerator->getEditorParams('editors/race/index'), [
                    'races' => $raceRepository->findAll()
        ]));
    }

    /**
     * @Route("/new", name="race_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response {
        $race = new Race();
        $form = $this->createForm(RaceType::class, $race);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($race);
            $entityManager->flush();

            return $this->redirectToRoute('race_index');
        }

        return $this->render($this->getSkinFile(), array_merge($this->paramGenerator->getEditorParams('editors/race/new'), [
                    'race' => $race,
                    'form' => $form->createView()
        ]));
    }

    /**
     * @Route("/{id}", name="race_show", methods={"GET"})
     */
    public function show(Race $race): Response {
        return $this->render($this->getSkinFile(), array_merge($this->paramGenerator->getEditorParams('editors/race/show'), [
                    'race' => $race
        ]));
    }

    /**
     * @Route("/{id}/edit", name="race_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Race $race): Response {
        $form = $this->createForm(RaceType::class, $race);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('race_index', [
                        'id' => $race->getId()
            ]);
        }

        return $this->render($this->getSkinFile(), array_merge($this->paramGenerator->getEditorParams('editors/race/edit'), [
                    'race' => $race,
                    'form' => $form->createView()
        ]));
    }

    /**
     * @Route("/{id}", name="race_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Race $race): Response {
        if ($this->isCsrfTokenValid('delete' . $race->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($race);
            $entityManager->flush();
        }

        return $this->redirectToRoute('race_index');
    }

}
