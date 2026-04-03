<?php

namespace App\Mail;

use App\Models\Stop;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewStopCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Stop $stop;

    public function __construct(Stop $stop)
    {
        $this->stop = $stop->loadMissing('user');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('[KDNVPP] Thong bao the STOP moi #' . $this->stop->id)
            ->view('emails.stops.new-stop-created', [
                'stop' => $this->stop,
            ]);
    }
}
