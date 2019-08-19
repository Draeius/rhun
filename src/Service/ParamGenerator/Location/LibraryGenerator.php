<?php

namespace App\Service\ParamGenerator\Location;

use App\Entity\LocationBase;
use App\Util\Session\RhunSession;

/**
 * Description of LibraryGenerator
 *
 * @author Draeius
 */
class LibraryGenerator extends LocationParamGeneratorBase {

    public function getParams(LocationBase $location): array {
        $session = new RhunSession();
        $themeId = $session->getBookTheme();
        if ($themeId) {
            $books = $this->getEntityManager()->getRepository('App:Book')->findBy(['theme' => $themeId], ['listOrder' => 'ASC']);
        } else {
            $books = [];
        }

        return [
            'themes' => $this->getManager()->getRepository('App:BookTheme')->findAll(),
            'books' => $books,
            'own_books' => $this->getManager()->getRepository('App:Book')->findByAuthor($this->getCharacter())
        ];
    }

}
