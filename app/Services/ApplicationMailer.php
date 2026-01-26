<?php

namespace App\Services;

use App\Mail\GenericMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\MailerSetting;
use Illuminate\Support\Facades\Config;
use App\Models\User;
use App\Jobs\SendApplicationMailJob;


class ApplicationMailer
{
    public function send(array $payload, $recipient_id)
    {
        // Push email sending to queue instead of executing now
        SendApplicationMailJob::dispatch($payload, $recipient_id);

        return true; // Immediate response so UI/modal can close
    }
}
