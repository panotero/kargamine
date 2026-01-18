<?php

namespace App\Services;

use App\Mail\GenericMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\MailerSetting;
use Illuminate\Support\Facades\Config;
use App\Models\User;

class ApplicationMailer
{
    public function send(array $payload, $recipient_id)
    {
        $config = MailerSetting::latest()->first();
        $user = User::with(['userConfig', 'office'])
            ->findOrFail($recipient_id);

        if (!$config) {
            throw new \RuntimeException('Mailer configuration not found.');
        }

        Config::set('mail.default', 'smtp');

        Config::set('mail.mailers.smtp', [
            'transport'  => 'smtp',
            'host'       => $config->mail_host,
            'port'       => $config->mail_port,
            'encryption' => $config->mail_encryption,
            'username'   => $config->mail_username,
            'password'   => $config->mail_password,
        ]);

        Config::set('mail.from.address', $config->mail_from_address);
        Config::set('mail.from.name', $config->mail_from_name);

        Mail::to($user->email)->send(
            new GenericMail(
                $payload['subject'],
                $payload['message'] ?? '',
                [
                    'title'   => $payload['title'] ?? null,
                    'message' => $payload['message'] ?? null,
                    'docControlNumber' => $payload['docControlNumber'] ?? null,
                    'button'  => $payload['button'] ?? null, // ← THIS IS THE KEY
                ]
            )
        );
    }
}
