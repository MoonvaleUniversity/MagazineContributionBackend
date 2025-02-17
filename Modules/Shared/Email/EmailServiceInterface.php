<?php
namespace Modules\Shared\Email;


interface EmailServiceInterface{

    /**
     * Handle single file upload.
     *
     * @param string $email mail.
     * @param int $userId id of an user:
     * @return
     */
    public function sendVerificationEmail(int $userId,string  $email): void;

}

?>
