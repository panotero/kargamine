<?php

namespace Tests\Feature;

use App\Models\ClientContract;
use App\Models\ClientContractRate;
use App\Models\ClientMaster;
use App\Models\Container;
use App\Models\ContainerAsset;
use App\Models\ContainerClass;
use App\Models\ContainerSize;
use App\Models\ContainerVariant;
use App\Models\DeliveryType;
use App\Models\Lane;
use App\Models\LaneTariffRate;
use App\Models\LaneTariffRatePrice;
use App\Models\Permission;
use App\Models\Port;
use App\Models\ServiceableArea;
use App\Models\SettingRole;
use App\Models\User;
use App\Models\VatRate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private Port $origin;
    private Port $destination;
    private Lane $lane;
    private LaneTariffRate $tariffRate;
    private DeliveryType $deliveryType;
    private ServiceableArea $originArea;
    private ServiceableArea $destinationArea;
    private ClientMaster $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->origin = Port::create(['code' => 'MNL', 'name' => 'Manila', 'is_active' => true]);
        $this->destination = Port::create(['code' => 'CEB', 'name' => 'Cebu', 'is_active' => true]);

        $this->lane = Lane::create([
            'origin_port_id' => $this->origin->port_id,
            'destination_port_id' => $this->destination->port_id,
            'is_active' => true,
        ]);

        $this->tariffRate = LaneTariffRate::create([
            'lane_id' => $this->lane->lane_id,
            'effective_date' => now()->subDay()->toDateString(),
            'is_active' => true,
        ]);

        VatRate::create(['rate_percent' => 12, 'effective_date' => now()->subDay()->toDateString(), 'is_active' => true]);

        $this->deliveryType = DeliveryType::create([
            'code' => 'DD',
            'name' => 'Door to Door',
            'includes_origin_trucking' => false,
            'includes_destination_trucking' => false,
        ]);

        $this->originArea = ServiceableArea::create(['port_id' => $this->origin->port_id, 'area_name' => 'Origin Area']);
        $this->destinationArea = ServiceableArea::create(['port_id' => $this->destination->port_id, 'area_name' => 'Dest Area']);

        $this->client = ClientMaster::create(['customer_code' => 'CM-2026-0001', 'company_name' => 'Test Client', 'current_stage' => 1]);
    }

    private function makeVariant(string $containerCode = 'DRY'): ContainerVariant
    {
        $container = Container::create(['code' => $containerCode, 'name' => 'Dry Van', 'is_active' => true]);
        $class = ContainerClass::create(['class' => 'A']);
        $size = ContainerSize::create(['size' => '20ft']);

        $variant = ContainerVariant::create([
            'container_id' => $container->id,
            'container_class_id' => $class->id,
            'container_size_id' => $size->id,
            'is_active' => true,
        ]);

        LaneTariffRatePrice::create([
            'lane_tariff_rate_id' => $this->tariffRate->rate_id,
            'container_variant_id' => $variant->id,
            'frt' => 10000,
        ]);

        return $variant;
    }

    private function makeAsset(ContainerVariant $variant, string $containerNo): ContainerAsset
    {
        return ContainerAsset::create([
            'container_variant_id' => $variant->id,
            'container_no' => $containerNo,
            'status' => ContainerAsset::STATUS_AVAILABLE,
            'current_port_id' => $this->origin->port_id,
        ]);
    }

    private function actingSuperadmin(): User
    {
        $role = SettingRole::where('role_name', 'superadmin')->first()
            ?? SettingRole::create(['role_name' => 'superadmin', 'is_system' => true]);

        foreach (['booking.create', 'booking.confirm', 'booking.cancel', 'booking.advance-status'] as $key) {
            $permission = Permission::firstOrCreate(['key' => $key], ['label' => $key, 'module' => 'Booking']);
            $role->permissions()->syncWithoutDetaching([$permission->id]);
        }

        $user = User::factory()->create(['role_id' => $role->id]);

        return tap($user, fn ($u) => $this->actingAs($u));
    }

    private function basePayload(ContainerVariant $variant, int $quantity = 1, bool $autoAssign = true, array $assetIds = []): array
    {
        return [
            'client_id' => $this->client->id,
            'origin_port_id' => $this->origin->port_id,
            'destination_port_id' => $this->destination->port_id,
            'origin_area_id' => $this->originArea->area_id,
            'destination_area_id' => $this->destinationArea->area_id,
            'delivery_type_id' => $this->deliveryType->delivery_type_id,
            'lines' => [[
                'container_variant_id' => $variant->id,
                'quantity' => $quantity,
                'auto_assign' => $autoAssign,
                'container_asset_ids' => $assetIds,
            ]],
        ];
    }

    /** @test */
    public function it_creates_a_multi_line_draft_booking_and_reserves_containers()
    {
        $this->actingSuperadmin();
        $variant = $this->makeVariant();
        $this->makeAsset($variant, 'AAAU1111111');
        $this->makeAsset($variant, 'AAAU2222222');

        $response = $this->postJson('/api/bookings', $this->basePayload($variant, quantity: 2));

        $response->assertStatus(201);
        $data = $response->json('data');

        $this->assertNotEmpty($data['code']);
        $this->assertEquals(1, $data['status']); // Draft
        $this->assertCount(2, $data['lines'][0]['container_units']);
        $this->assertEquals(2, ContainerAsset::where('status', ContainerAsset::STATUS_BOOKED)->count());
    }

    /** @test */
    public function contract_rate_applies_when_a_matching_client_contract_rate_exists()
    {
        $this->actingSuperadmin();
        $variant = $this->makeVariant();
        $this->makeAsset($variant, 'BBBU1111111');

        $contract = ClientContract::create([
            'code' => 'CC-2026-0001',
            'client_id' => $this->client->id,
            'valid_from' => now()->subMonth(),
            'valid_to' => now()->addMonth(),
            'status' => ClientContract::STATUS_ACTIVE,
        ]);

        ClientContractRate::create([
            'contract_id' => $contract->id,
            'origin_port_id' => $this->origin->port_id,
            'destination_port_id' => $this->destination->port_id,
            'container_id' => $variant->container_id,
            'container_class_id' => $variant->container_class_id,
            'container_size_id' => $variant->container_size_id,
            'container_variant_id' => $variant->id,
            'base_rate' => 10000,
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'final_rate' => 9000,
        ]);

        $response = $this->postJson('/api/bookings', $this->basePayload($variant));

        $response->assertStatus(201);
        $line = $response->json('data.lines.0');

        $this->assertEquals('percentage', $line['discount_type_snapshot']);
        $this->assertEquals(9000, (float) $line['frt_after_discount_snapshot']);
    }

    /** @test */
    public function confirm_requires_every_container_unit_to_be_assigned()
    {
        $this->actingSuperadmin();
        $variant = $this->makeVariant();
        $this->makeAsset($variant, 'CCCU1111111');
        $this->makeAsset($variant, 'CCCU2222222');

        $store = $this->postJson('/api/bookings', $this->basePayload($variant, quantity: 1));
        $uuid = $store->json('data.uuid');

        // Manually null out the one unit's container_asset_id to simulate an unassigned line.
        \App\Models\BookingContainerUnit::query()->update(['container_asset_id' => null]);
        ContainerAsset::query()->update(['status' => ContainerAsset::STATUS_AVAILABLE]);

        $confirm = $this->postJson("/api/bookings/{$uuid}/confirm");

        $confirm->assertStatus(422);
        $confirm->assertJsonFragment(['success' => false]);
    }

    /** @test */
    public function confirm_locks_pricing_and_creates_a_draft_invoice_and_bill_of_lading()
    {
        $this->actingSuperadmin();
        $variant = $this->makeVariant();
        $this->makeAsset($variant, 'DDDU1111111');

        $store = $this->postJson('/api/bookings', $this->basePayload($variant));
        $uuid = $store->json('data.uuid');

        $confirm = $this->postJson("/api/bookings/{$uuid}/confirm");

        $confirm->assertStatus(200);
        $data = $confirm->json('data');

        $this->assertEquals(2, $data['status']); // Confirmed
        $this->assertNotNull($data['invoice']);
        $this->assertEquals($data['grand_total_snapshot'], $data['invoice']['amount']);
        $this->assertNotNull($data['bill_of_lading']);
    }

    /** @test */
    public function cancelling_a_draft_booking_releases_its_reserved_containers()
    {
        $this->actingSuperadmin();
        $variant = $this->makeVariant();
        $asset = $this->makeAsset($variant, 'EEEU1111111');

        $store = $this->postJson('/api/bookings', $this->basePayload($variant));
        $uuid = $store->json('data.uuid');

        $this->assertEquals(ContainerAsset::STATUS_BOOKED, $asset->fresh()->status);

        $cancel = $this->postJson("/api/bookings/{$uuid}/cancel", ['reason' => 'Client cancelled']);

        $cancel->assertStatus(200);
        $this->assertEquals(6, $cancel->json('data.status')); // Cancelled
        $this->assertEquals(ContainerAsset::STATUS_AVAILABLE, $asset->fresh()->status);
    }

    /** @test */
    public function manual_assignment_rejects_a_quantity_mismatch_and_rolls_back()
    {
        $this->actingSuperadmin();
        $variant = $this->makeVariant();
        $asset = $this->makeAsset($variant, 'FFFU1111111');

        $response = $this->postJson('/api/bookings', $this->basePayload(
            $variant,
            quantity: 2,
            autoAssign: false,
            assetIds: [$asset->id]
        ));

        $response->assertStatus(422);
        $this->assertEquals(ContainerAsset::STATUS_AVAILABLE, $asset->fresh()->status);
        $this->assertEquals(0, \App\Models\Booking::count());
    }

    /** @test */
    public function a_user_without_the_booking_create_permission_is_blocked()
    {
        $role = SettingRole::create(['role_name' => 'no-perms-role', 'is_system' => false]);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $variant = $this->makeVariant();
        $this->makeAsset($variant, 'GGGU1111111');

        $response = $this->postJson('/api/bookings', $this->basePayload($variant));

        $response->assertStatus(403);
    }
}
