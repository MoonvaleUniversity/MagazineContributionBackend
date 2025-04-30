<?php

namespace Modules\Shared\Email;

use Illuminate\Support\Facades\Mail;

class EmailService implements EmailServiceInterface
{

    public function send(string $view, string $email, string $subject, array $data = []): void
    {

        Mail::send($view, $data, function ($message) use ($email, $subject) {
            $message->to($email)
                ->subject($subject);
        });
    }

    public function raw(string $view, string $email, string $subject): void
    {
        Mail::raw($view, function ($message) use ($email, $subject) {
            $message->to($email)
                ->subject($subject);
        });
    }
}
