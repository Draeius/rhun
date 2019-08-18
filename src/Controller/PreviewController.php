<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of PreviewController
 *
 * @author Draeius
 */
class PreviewController extends AbstractController {

    /**
     * @Route("format/preview")
     * @param Request $request
     */
    function getPreview(Request $request) {
        return $this->render('parts/preview.html.twig', [
                    'text' => $request->get("text")
        ]);
    }

}
