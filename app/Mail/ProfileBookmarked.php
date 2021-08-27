<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Profile;
use App\User;

class ProfileBookmarked extends Mailable
{
    use Queueable, SerializesModels;

    public $profile;

    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Profile $profile, User $user)
    {
        $this->profile = $profile;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->profile->fancy_name . ' foi favoritado por ' . $this->user->name)
            ->view('emails.profile-bookmarked');
    }
}
