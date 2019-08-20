<?php

namespace App\Controller\Entity;

use App\Controller\BasicController;
use App\Entity\Book;
use App\Form\BookType;
use App\Service\ParamGenerator\EditorParamGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book")
 */
class BookController extends BasicController {

    /**
     *
     * @var EditorParamGenerator
     */
    private $paramGenerator;

    function __construct(EditorParamGenerator $paramGenerator) {
        $this->paramGenerator = $paramGenerator;
    }

    /**
     * @Route("/", name="book_index", methods={"GET"})
     */
    public function index(): Response {
        $books = $this->getDoctrine()
                ->getRepository(Book::class)
                ->findAll();

        return $this->render($this->getSkinFile(), array_merge($this->paramGenerator->getEditorParams('editors/book/index'), [
                    'books' => $books,
        ]));
    }

    /**
     * @Route("/new", name="book_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book_index');
        }

        return $this->render($this->getSkinFile(), array_merge($this->paramGenerator->getEditorParams('editors/book/new'), [
                    'book' => $book,
                    'form' => $form->createView(),
        ]));
    }

    /**
     * @Route("/{id}", name="book_show", methods={"GET"})
     */
    public function show(Book $book): Response {
        return $this->render($this->getSkinFile(), array_merge($this->paramGenerator->getEditorParams('editors/book/show'), [
                    'book' => $book,
        ]));
    }

    /**
     * @Route("/{id}/edit", name="book_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Book $book): Response {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('book_index', [
                        'id' => $book->getId(),
            ]);
        }

        return $this->render($this->getSkinFile(), array_merge($this->paramGenerator->getEditorParams('editors/book/edit'), [
                    'book' => $book,
                    'form' => $form->createView(),
        ]));
    }

    /**
     * @Route("/{id}", name="book_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Book $book): Response {
        if ($this->isCsrfTokenValid('delete' . $book->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('book_index');
    }

}
