<?php

namespace App\Controller;

use App\Util\Session\RhunSession;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @author Draeius
 */
class InventoryController {

    const SHOW_INVENTORY_ROUTE_NAME = 'inventory';

    /**
     * @Route("/inventory/get/{uuid}", name=InventoryController::SHOW_INVENTORY_ROUTE_NAME)
     */
    public function showInventory($uuid) {
        $character = $this->getCharacter($uuid);

        $builder = $this->get('app.navbar');
        $builder->addNav('Zurück', 'world', ['uuid' => $uuid, 'locationId' => $character->getLocation()->getId()]);

        $session = new RhunSession($uuid);

        $vars = array_merge($this->getBaseVariables($builder, 'Inventar'), [
            'page' => 'default/inventory',
//            'fight' => FightFactory::LOAD_FIGHT(new RhunSession($this->getUuid()), $this->getDoctrine()->getManager(), $character),
            'inventory' => $character->getInventory(),
            'uuid' => $uuid
        ]);

        return $this->render($this->getSkinFile(), $vars);
    }

}
