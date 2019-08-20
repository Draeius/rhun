<?php

namespace App\Service\NavbarFactory\Location;

use App\Entity\Job;
use App\Entity\Location;
use App\Service\NavbarService;
use App\Util\Session\RhunSession;
use App\Util\TabIdentification\TabIdentifier;

/**
 * Description of JobLocationModifier
 *
 * @author Draeius
 */
class JobLocationModifier extends NavbarModifierBase {

    public function modifyNavbar(NavbarService $service, Location $location) {
        $jobList = $this->getEntityManager()->getRepository('App:Job')->findByLocation($location);

        $character = $this->getCharacter();
        $session = new RhunSession();

        $service->addNavhead('Arbeit');
        foreach ($jobList as $job) {
            $this->addJobNav($service, $job, $session->getTabIdentifier(),
                    $character->getJob() != null && $job->getId() == $character->getJob()->getId());
        }
    }

    private function addJobNav(NavbarService $service, Job $job, TabIdentifier $tabId, bool $isCurrentCharJob) {
        if ($isCurrentCharJob) {
            $service->addNav('`QArbeiten', 'job_work', ['uuid' => $tabId->getIdentifier()]);
            if ($job->getPromoteTo()) {
                $service->addNav('`QBefördert werden', 'job_promote', ['uuid' => $tabId->getIdentifier()]);
            }
        } else {
            $service->addNav('`dNach Arbeit fragen <br /> (' . $job->getColoredName() . ')', 'job_take',
                    ['uuid' => $tabId->getIdentifier(), 'jobId' => $job->getId()]);
        }
    }

}
