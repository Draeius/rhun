<?php

namespace App\Service;

use App\Entity\User;
use Swift_Mailer;
use Swift_Message;

/**
 * Enthält Methoden die diverse Standardmails verschicken.
 *
 * @author Draeius
 */
class EmailService {

    /**
     *
     * @var ConfigService
     */
    private $configService;

    /**
     *
     * @var Swift_Mailer
     */
    private $swiftMailer;

    public function sendMailValidationCode(User $user) {
        $mail = new Swift_Message('Rhun Support');
    }

    public function sendNewPasswordMail(User $user, $newPassword) {
        $mail = new Swift_Message('Rhun Support');
    }

}
