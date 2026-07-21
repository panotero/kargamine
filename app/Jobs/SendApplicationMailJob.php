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
use Illuminate\Support\Facades\Log;

class SendApplicationMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payload;
    protected $userId;
    protected $user;

    public function __construct(array $payload, $userId)
    {
        $this->payload = $payload;
        $this->userId = $userId;
    }
    public function handle()
    {
        $config = MailerSetting::latest()->first();
        $user = User::find($this->userId);

        if (!$config || !$user) {
            Log::warning('SendApplicationMailJob skipped: missing config or user', [
                'userId' => $this->userId,
            ]);
            return;
        }

        Config::set('mail.default', 'smtp');

        Config::set('mail.mailers.smtp', [
            'transport'   => 'smtp',
            'host'        => $config->mail_host,
            'port'        => (int) $config->mail_port,
            'encryption'  => $config->mail_encryption ?: null,
            'username'    => $config->mail_username ?: null,
            'password'    => $config->mail_password ?: null,
            'verify_peer' => false, // <-- this is what actually disables cert verification
        ]);

        Config::set('mail.from.address', $config->mail_from_address);
        Config::set('mail.from.name', $config->mail_from_name);

        Log::info('Mailer config in use', [
            'host'       => $config->mail_host,
            'port'       => $config->mail_port,
            'encryption' => $config->mail_encryption,
            'from'       => $config->mail_from_address,
        ]);

        Mail::purge('smtp');

        try {
            Mail::to($user->email)->send(
                new GenericMail(
                    $this->payload['subject'],
                    $this->payload['message'] ?? '',
                    [
                        'title'            => $this->payload['title'] ?? null,
                        'message'          => $this->payload['message'] ?? null,
                        'docControlNumber' => $this->payload['docControlNumber'] ?? null,
                        'Header'           => $this->payload['Header'] ?? null,
                        'app_name'         => $this->payload['app_name'] ?? null,
                        'logo'             => $this->payload['logo'] ?? null,
                        'button'           => $this->payload['button'] ?? null,
                        'footer'           => $this->payload['footer'] ?? null,
                    ]
                )
            );

            Log::info('SendApplicationMailJob sent successfully', [
                'userId' => $this->userId,
                'to'     => $user->email,
            ]);
        } catch (\Throwable $e) {
            Log::error('SendApplicationMailJob failed: ' . $e->getMessage(), [
                'userId' => $this->userId,
                'to'     => $user->email,
                'host'   => $config->mail_host,
                'port'   => $config->mail_port,
            ]);
        }
    }
}
