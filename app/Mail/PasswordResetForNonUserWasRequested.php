<?php

namespace Udoktor\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class PasswordResetForNonUserWasRequested
 *
 * @package Udoktor\Mail
 * @category Mailable
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class PasswordResetForNonUserWasRequested extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    private $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.accounts.password_reset_non_user')
            ->with('user', $this->email);
    }
}
