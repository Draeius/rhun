<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

use App\Repository\UserRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Description of UserService
 *
 * @author Draeius
 */
class UserService {

    /**
     *
     * @var UserPasswordEncoderInterface
     */
    private $pwEncoder;

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    /**
     *
     * @var UserRepository
     */
    private $userRepo;

    public function resetUserPassword(string $name, string $newPassword) {
        /* @var $user User */
        $user = $this->userRepo->findByName($name);
        if (!$user) {
            return;
        }
        $encoded = $this->pwEncoder->encodePassword($user, $newPassword);
        $user->setPassword($encoded);

        $this->eManager->persist($user);
        $this->eManager->flush();
    }

}
