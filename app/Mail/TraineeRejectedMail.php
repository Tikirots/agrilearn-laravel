<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TraineeRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('AgriLearn - Registration Status Update')
            ->html("
                <h2>Hello {$this->user->name},</h2>
                <p>We regret to inform you that your registration application at <strong>AgriLearn - Masaganang Bukid Agricultural Learning Center</strong> was not approved at this time.</p>
                <p>If you believe this is an error or have questions, please reach out directly to the center administrators.</p>
                <p>Thank you,<br>AgriLearn Team</p>
            ");
    }
}
