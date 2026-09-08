<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Issues a Sanctum API token for an external device (e.g. the planned
 * cargo-yard QR scanner) to call the dormant device-integration endpoints
 * directly, without a browser session. Not run as part of normal
 * operation yet - this is the readiness step for when real hardware
 * shows up. See DeviceContainerAssignmentController and MODULES.md.
 *
 * Every device token is issued against one shared, roleless
 * "Device Integrations" account (never used to log in, no role_id) so
 * revoking or auditing device access never touches a real staff user.
 */
class MakeDeviceToken extends Command
{
    protected $signature = 'device:make-token {name : A label for this device, e.g. "Yard B Scanner"}';

    protected $description = 'Issue a Sanctum API token for an external device (e.g. a QR scanner) to call the dormant container-assignment endpoint.';

    public function handle(): int
    {
        $label = $this->argument('name');

        $device = User::firstOrCreate(
            ['email' => 'device-integrations@kargamine.internal'],
            [
                'name' => 'Device Integrations',
                'password' => Hash::make(Str::random(40)),
                'status' => User::STATUS_ACTIVE,
                'role_id' => null,
            ],
        );

        $token = $device->createToken($label, ['container.assign']);

        $this->info("Token issued for \"{$label}\":");
        $this->line($token->plainTextToken);
        $this->warn('Store this now - it will not be shown again. Configure the device to send it as: Authorization: Bearer <token>');

        return self::SUCCESS;
    }
}
