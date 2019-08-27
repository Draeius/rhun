<?php

namespace App\Controller\Entity;

use App\Controller\BasicController;
use App\Entity\BookTheme;
use App\Form\BookThemeType;
use App\Service\ParamGenerator\EditorParamGenerator;
use App\Service\SkinService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book/theme")
 * @App\Annotation\Security(needAccount=true, editWorld=true)
 */
class BookThemeController extends BasicController {

    /**
     *
     * @var EditorParamGenerator
     */
    private $paramGenerator;

    function __construct(SkinService $skinService, EditorParamGenerator $paramGenerator) {
        parent::__construct($skinService);
        $this->paramGenerator = $paramGenerator;
    }

    /**
     * @Route("/", name="book_theme_index", methods={"GET"})
     */
    public function index(): Response {
        $bookThemes = $this->getDoctrine()
                ->getRepository(BookTheme::class)
                ->findAll();

        return $this->render($this->getSkinFile(), array_merge($this->paramGenerator->getEditorParams('editors/book_theme/index'), [
                    'book_themes' => $bookThemes,
        ]));
    }

    /**
     * @Route("/new", name="book_theme_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response {
        $bookTheme = new BookTheme();
        $form = $this->createForm(BookThemeType::class, $bookTheme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bookTheme);
            $entityManager->flush();

            return $this->redirectToRoute('book_theme_index');
        }

        return $this->render($this->getSkinFile(), array_merge($this->paramGenerator->getEditorParams('editors/book_theme/new'), [
                    'book_theme' => $bookTheme,
                    'form' => $form->createView(),
        ]));
    }

    /**
     * @Route("/{id}", name="book_theme_show", methods={"GET"})
     */
    public function show(BookTheme $bookTheme): Response {
        return $this->render($this->getSkinFile(), array_merge($this->paramGenerator->getEditorParams('editors/book_theme/show'), [
                    'book_theme' => $bookTheme,
        ]));
    }

    /**
     * @Route("/{id}/edit", name="book_theme_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BookTheme $bookTheme): Response {
        $form = $this->createForm(BookThemeType::class, $bookTheme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('book_theme_index', [
                        'id' => $bookTheme->getId(),
            ]);
        }

        return $this->render($this->getSkinFile(), array_merge($this->paramGenerator->getEditorParams('editors/book_theme/edit'), [
                    'book_theme' => $bookTheme,
                    'form' => $form->createView(),
        ]));
    }

    /**
     * @Route("/{id}", name="book_theme_delete", methods={"DELETE"})
     */
    public function delete(Request $request, BookTheme $bookTheme): Response {
        if ($this->isCsrfTokenValid('delete' . $bookTheme->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bookTheme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('book_theme_index');
    }

}
