<?php

namespace App\Jobs;

use App\Mail\GenericMail;
use App\Models\MailerSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Same SMTP-from-MailerSetting bootstrap as SendApplicationMailJob, but for
 * recipients who aren't a `User` row (e.g. a client's authorized signatory) -
 * that job always mails the looked-up User's own address, which doesn't fit
 * external recipients.
 */
class SendExternalMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $toEmail;
    protected array $payload;

    public function __construct(string $toEmail, array $payload)
    {
        $this->toEmail = $toEmail;
        $this->payload = $payload;
    }

    public function handle()
    {
        $config = MailerSetting::latest()->first();

        if (!$config) {
            Log::warning('SendExternalMailJob skipped: missing mailer config', ['to' => $this->toEmail]);
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
            'verify_peer' => false,
        ]);

        Config::set('mail.from.address', $config->mail_from_address);
        Config::set('mail.from.name', $config->mail_from_name);

        Mail::purge('smtp');

        try {
            Mail::to($this->toEmail)->send(
                new GenericMail(
                    $this->payload['subject'],
                    $this->payload['message'] ?? '',
                    [
                        'title'    => $this->payload['title'] ?? null,
                        'message'  => $this->payload['message'] ?? null,
                        'Header'   => $this->payload['Header'] ?? null,
                        'app_name' => $this->payload['app_name'] ?? null,
                        'logo'     => $this->payload['logo'] ?? null,
                        'button'   => $this->payload['button'] ?? null,
                        'footer'   => $this->payload['footer'] ?? null,
                    ]
                )
            );

            Log::info('SendExternalMailJob sent successfully', ['to' => $this->toEmail]);
        } catch (\Throwable $e) {
            Log::error('SendExternalMailJob failed: ' . $e->getMessage(), [
                'to'   => $this->toEmail,
                'host' => $config->mail_host,
                'port' => $config->mail_port,
            ]);
        }
    }
}
