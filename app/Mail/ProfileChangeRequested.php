<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Profile;
use App\ProfileChangeRequest;

class ProfileChangeRequested extends Mailable
{
    use Queueable, SerializesModels;

    public $profile;

    public $request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Profile $profile, ProfileChangeRequest $request)
    {
        $this->profile = $profile;
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.profile-change-requested')
            ->subject($this->profile->fancy_name . ' deseja alterar o perfil');
    }
}
