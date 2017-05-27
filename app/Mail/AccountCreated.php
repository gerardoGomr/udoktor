<?php

namespace Udoktor\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Udoktor\Domain\Users\User;

/**
 * Class AccountCreated
 *
 * @package Udoktor\Mail
 * @category Mailable
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class AccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    private $user;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Builds the message to be sent to new user
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.accounts.created')
            ->with('user', $this->user);
    }
}
