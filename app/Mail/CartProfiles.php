<?php

namespace App\Mail;

use App\Cart;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CartProfiles extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The cart instance
     *
     * @var Cart
     */
    public $cart;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('emails.carts.profiles')->subject($this->cart->name);
        $array_imgs = unserialize($this->cart->photos_select);
         
        foreach ($this->cart->profiles as $profile) {
            $filename = str_slug($profile->user->name);

            $email->attach(public_path('/uploads/carts/' . $this->cart->id . '/' . $filename.".pdf"), [
                'as' => $profile->fancy_name . '.pdf',
            ]);

            $email->attach(public_path($array_imgs[$profile->user->id][0]["src"]), [
                'as' => $profile->fancy_name . '.jpg',
            ]);
            
        }

        return $email;
    }
}
