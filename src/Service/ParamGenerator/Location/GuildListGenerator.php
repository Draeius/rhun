<?php

namespace App\Service\ParamGenerator\Location;

use App\Entity\LocationBase;

/**
 * Description of GuildListGenerator
 *
 * @author Draeius
 */
class GuildListGenerator extends LocationParamGeneratorBase {

    public function getParams(LocationBase $location): array {
        $rep = $this->getEntityManager()->getRepository('App:Guild');
        $invRep = $this->getEntityManager()->getRepository('App:GuildInvitation');

        return [
            'guilds' => $rep->findAll(),
            'invitations' => $invRep->findBy(['character' => $this->getCharacter()]),
            'foundingPrice' => $this->getConfig()->getGuildConfig()->getGuildFoundingPrice()
        ];
    }

}
