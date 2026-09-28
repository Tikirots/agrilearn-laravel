<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TraineeApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('AgriLearn - Account Approved!')
            ->html("
                <h2>Hello {$this->user->name},</h2>
                <p>Great news! Your registration at <strong>AgriLearn - Masaganang Bukid Agricultural Learning Center</strong> has been <strong>APPROVED</strong>.</p>
                <p>You can now log in to your account and enroll in our available training programs.</p>
                <p><a href='" . route('login') . "' style='display:inline-block; background-color:#28a745; color:#fff; padding:10px 20px; text-decoration:none; border-radius:5px;'>Log In Now</a></p>
                <p>Thank you,<br>AgriLearn Team</p>
            ");
    }
}
