<?php

namespace App\Services;

use App\Mail\GenericMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\MailerSetting;
use Illuminate\Support\Facades\Config;
use App\Models\User;
use App\Jobs\SendApplicationMailJob;
use Illuminate\Support\Facades\Log;


class ApplicationMailer
{
    public function send(array $payload, $userId)
    {
        // Push email sending to queue instead of executing now
        SendApplicationMailJob::dispatch($payload, $userId);

        return true; // Immediate response so UI/modal can close
    }
}
