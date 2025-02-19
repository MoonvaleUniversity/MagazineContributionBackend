<?php

namespace Modules\Shared\Email;

interface EmailServiceInterface
{

    /**
     * Send mail
     *
     * @param string $view View file path of the email.
     * @param int $email Email to send.
     * @param string $subject Subject of the email.
     * @param array $data Data to pass into email view file.
     * @return void
     */
    public function send(string $view, string $email, string $subject, array $data);
}
