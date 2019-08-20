<?php

namespace App\Service\ParamGenerator\Location;

use App\Entity\LocationBase;

/**
 * Description of LibraryGenerator
 *
 * @author Draeius
 */
class LibraryGenerator extends LocationParamGeneratorBase {

    public function getParams(LocationBase $location): array {
        $themeId = $this->getSession()->getBookTheme();
        if ($themeId) {
            $books = $this->getEntityManager()->getRepository('App:Book')->findBy(['theme' => $themeId], ['listOrder' => 'ASC']);
        } else {
            $books = [];
        }

        return [
            'themes' => $this->getEntityManager()->getRepository('App:BookTheme')->findAll(),
            'books' => $books,
            'own_books' => $this->getEntityManager()->getRepository('App:Book')->findByAuthor($this->getCharacter())
        ];
    }

}
