<?php

namespace App\Controller;

use App\Entity\Character;
use App\Entity\Job;
use App\Repository\CharacterRepository;
use App\Repository\JobRepository;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of JobController
 *
 * @author Draeius
 * @Route("job");
 */
class JobController extends BasicController {

    /**
     * 
     * @Route("/take/{uuid}/{jobId}", name="job_take")
     */
    public function takeJob(Request $request, $uuid, $jobId, CharacterRepository $charRepo, JobRepository $jobRepo, EntityManagerInterface $eManager) {
        $session = new RhunSession();
        /* @var $character Character */
        $character = $charRepo->find($session->getCharacterID());

        /* @var $job Job */
        $job = $jobRepo->find($jobId);
        if (!$job || true !== $suitable = $job->isSuitable($character)) {
            $session->error($suitable);
            return $this->redirectToWorld($character);
        }

        $character->setJob($job);
        $eManager->persist($character);
        $eManager->flush();

        return $this->redirectToWorld($character);
    }

    /**
     * 
     * @Route("/promote/{uuid}", name="job_promote")
     */
    public function promoteJob(Request $request, $uuid, CharacterRepository $charRepo, EntityManagerInterface $eManager) {
        $session = new RhunSession();
        /* @var $character Character */
        $character = $charRepo->find($session->getCharacterID());

        $job = $character->getJob();
        if (!$job) {
            return $this->redirectToWorld($character);
        }

        $promote = $job->getPromoteTo();
        if (!$promote || true !== $suitable = $promote->isSuitable($character)) {
            $session->error($suitable ? $suitable : 'Dieser Job kann nicht befördert werden.');
            return $this->redirectToWorld($character);
        }

        $character->setJob($promote);
        $eManager->persist($character);
        $eManager->flush();
        return $this->redirectToWorld($character);
    }

    /**
     * 
     * @Route("/work/{uuid}", name="job_work")
     */
    public function work(Request $request, $uuid, CharacterRepository $charRepo, EntityManagerInterface $eManager) {
        $session = new RhunSession();
        /* @var $character Character */
        $character = $charRepo->find($session->getCharacterID());

        $job = $character->getJob();
        if (!$job) {
            return $this->redirectToWorld($character);
        }

        foreach ($character->getBuffs() as $buff) {
            if (!$buff->getTemplate()->getCanWork()) {
                $session->error('`?Dein Chef schaut dich entgeistert an. "`qIn dem Zustand bleibst du wohl lieber daheim.`?" Vielleicht ruhst du dich heute lieber einfach aus, hm?');
                return $this->redirectToWorld($character);
            }
        }

        if ($character->getActionPoints() < 1) {
            $session->error('Du bist zu erschöpft um zu arbeiten.');
            return $this->redirectToWorld($character);
        }

        $character->addActionPoints(-1);
        $character->getWallet()->addPrice($job->getSalary());

        $eManager->persist($character);
        $eManager->flush();

        return $this->redirectToWorld($character);
    }

}
