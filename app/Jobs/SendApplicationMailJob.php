<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\MailerSetting;
use App\Mail\GenericMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendApplicationMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payload;
    protected $recipient_id;

    public function __construct(array $payload, $recipient_id)
    {
        $this->payload = $payload;
        $this->recipient_id = $recipient_id;
    }

    public function handle()
    {
        $config = MailerSetting::latest()->first();
        $user = User::with(['userConfig', 'office'])
            ->find($this->recipient_id);

        if (!$config || !$user) {
            return;
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
                $this->payload['subject'],
                $this->payload['message'] ?? '',
                [
                    'title'   => $this->payload['title'] ?? null,
                    'message' => $this->payload['message'] ?? null,
                    'docControlNumber' => $this->payload['docControlNumber'] ?? null,
                    'button'  => $this->payload['button'] ?? null,
                ]
            )
        );
    }
}
