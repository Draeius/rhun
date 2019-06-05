<?php

namespace App\Form\Facade;

use App\Form\DTO\CreateUserDTO;
use App\Repository\UserRoleRepository;
use App\Service\ConfigService;
use App\Service\ValidationService;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Description of createUserFacade
 *
 * @author Draeius
 */
class CreateUserFacade {

    /**
     *
     * @var UserPasswordEncoderInterface
     */
    private $pwEncoder;

    /**
     *
     * @var ValidationService
     */
    private $emailValidationService;

    /**
     *
     * @var UserRoleRepository
     */
    private $userRoleRepository;

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    /**
     *
     * @var ConfigService
     */
    private $configService;

    function __construct(UserPasswordEncoderInterface $pwEncoder, ValidationService $emailValidationService, UserRoleRepository $userRoleRepository,
            EntityManagerInterface $eManager, ConfigService $configService) {
        $this->pwEncoder = $pwEncoder;
        $this->emailValidationService = $emailValidationService;
        $this->userRoleRepository = $userRoleRepository;
        $this->eManager = $eManager;
        $this->configService = $configService;
    }

    public function createUser(CreateUserDTO $dto): User {
        $user = new User();
        $user->setUsername($dto->username);
        $user->setEmail($dto->email);
        $user->setBirthday($dto->birthday);

        $user->setValidationCode($this->emailValidationService->getValidationCode());
        $user->setValidated(!$this->configService->getStartSettings()->getNeedEmailValidation());

        $level = $this->userRoleRepository->find($this->configService->getStartSettings()->getStartUserLevelId());
        $user->setUserRole($level);

        $encoded = $this->pwEncoder->encodePassword($user, $dto->getPassword());
        $user->setPassword($encoded);

        $this->eManager->persist($user);
        $this->eManager->flush();
        return $user;
    }

}
