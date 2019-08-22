<?php

namespace App\Controller;

use App\Entity\Character;
use App\Entity\CraftRecipe;
use App\Repository\CharacterRepository;
use App\Util\Session\RhunSession;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of CraftingController
 *
 * @author Draeius
 */
class CraftingController extends BasicController {

    const CRAFT_ITEM_ROUTE_NAME = 'craft_item';

    /**
     * @Route("/item/craft/{uuid}", name=CraftingController::CRAFT_ITEM_ROUTE_NAME)
     */
    public function craftItem(Request $request, $uuid, CharacterRepository $charRepo) {
        $session = new RhunSession();
        $character = $charRepo->find($session->getCharacterID());
        $recipe = $this->getCraftingRecipe($request);

        if ($character->getAttributes()->getIntelligence() < $recipe->getMinIntelligence()) {
            $recipe->setSuccessChance(-10000);
        }

        if (!$this->checkIngredients($character, $recipe)) {
            return $this->redirectToWorld($character);
        }

        if ($recipe->craft($this->getDoctrine()->getManager(), $character)) {
            $session->log('Du hast erfolgreich ein ' . $recipe->getResult()->getName() . ' hergestellt.');
        } else {
            $session->error('Das war ein Fehlschlag. Alle deine eingesetzten Items wurden zerstört.');
        }
        if ($recipe->getResult() !== NULL && $character->addKnownRecipe($recipe)) {
            $session->log('Du hast ein neues Rezept gelernt.');
        }
        $this->getDoctrine()->getManager()->flush($character);
        return $this->redirectToWorld($character);
    }

    private function getCraftingRecipe(Request $request) {
        $ingredients = $this->getCraftingIngredients($request);
        if (count($ingredients) != 0) {
            $rep = $this->getDoctrine()->getRepository('App:CraftRecipe');
            $recipe = $rep->findByIngredients($ingredients);
            if ($recipe) {
                return $recipe;
            }
        }
        $recipe = new CraftRecipe();
        $recipe->setIngredients($ingredients);
        $recipe->setSuccessChance(-10000);
        return $recipe;
    }

    private function getCraftingIngredients(Request $request) {
        $ingredients = [];
        $rep = $this->getDoctrine()->getRepository('App:Item');
        foreach ($request->get('ingredients') as $id) {
            $ingredients[] = $rep->find($id);
        }
        return $ingredients;
    }

    private function checkIngredients(Character $character, CraftRecipe $recipe) {
        foreach ($recipe->getIngredients() as $item) {
            if (!$character->hasItem($item)) {
                return false;
            }
        }
        return true;
    }

}
