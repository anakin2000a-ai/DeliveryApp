<?php

namespace App\Jobs;

use App\Mail\WelcomeUserMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class NewUserCreatedJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $user)
    {
        //
    }

    public function handle(): void
    {
        Mail::to($this->user->email)->send(
            new WelcomeUserMail($this->user)
        );
    }
}