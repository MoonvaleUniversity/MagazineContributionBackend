<?php

namespace Modules\Shared\Email;

use Illuminate\Support\Facades\Mail;

class EmailService implements EmailServiceInterface
{
    /**
     * Send verification email to the user.
     *
     * @param int $userId The user's ID.
     * @param string $email The user's email.
     * @return void
     */

     protected $emailService;

    // Inject EmailServiceInterface into the controller
    public function __construct(EmailServiceInterface $emailService)
    {
        $this->emailService = $emailService;
    }

    public function sendVerificationEmail(int $userId, string $email): void
    {
        $message = 'Please click the link to verify your email address.';
        $subject = 'Email Verification';

        // Send the email using Mail::send
        Mail::send('mail', ['userId' => $userId, 'message' => $message], function($message) use ($email, $subject) {
            $message->to($email)
                    ->subject($subject);
        });
    }
}
