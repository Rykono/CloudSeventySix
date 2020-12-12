<?php

namespace App\Mail\PetFinder;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewCatsEmail extends Mailable
{
    use SerializesModels;

    public $animals;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $animals)
    {
        $this->animals = $animals;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from('drew@blrepair.com', 'Cloud Seventy Six')
                    ->subject('CLOUD SEVENTY SIX - New Cats Available')
                    ->markdown('emails.petfinder.newcats', [
                      'animals' => $this->animals
                    ]);

    }
}
