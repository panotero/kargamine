<?php

namespace Tests\Feature;

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

class CargoBuildUpTest extends TestCase
{
    use RefreshDatabase;

    private Port $origin;

    private Port $destination;

    private LaneTariffRate $tariffRate;

    private ServiceableArea $originArea;

    private ServiceableArea $destinationArea;

    private ClientMaster $client;

    private ContainerVariant $variant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->origin = Port::create(['code' => 'MNL', 'name' => 'Manila', 'is_active' => true]);
        $this->destination = Port::create(['code' => 'CEB', 'name' => 'Cebu', 'is_active' => true]);

        $lane = Lane::create([
            'origin_port_id' => $this->origin->port_id,
            'destination_port_id' => $this->destination->port_id,
            'is_active' => true,
        ]);

        $this->tariffRate = LaneTariffRate::create([
            'lane_id' => $lane->lane_id,
            'effective_date' => now()->subDay()->toDateString(),
            'is_active' => true,
        ]);

        VatRate::create(['rate_percent' => 12, 'effective_date' => now()->subDay()->toDateString(), 'is_active' => true, 'tax_type' => 'General']);

        DeliveryType::create([
            'code' => 'DD',
            'name' => 'Door to Door',
            'includes_origin_trucking' => false,
            'includes_destination_trucking' => false,
        ]);

        $this->originArea = ServiceableArea::create(['port_id' => $this->origin->port_id, 'area_name' => 'Origin Area']);
        $this->destinationArea = ServiceableArea::create(['port_id' => $this->destination->port_id, 'area_name' => 'Dest Area']);

        $this->client = ClientMaster::create(['customer_code' => 'CM-2026-0001', 'company_name' => 'Test Client', 'current_stage' => 1]);

        $container = Container::create(['code' => 'DRY', 'name' => 'Dry Van', 'is_active' => true]);
        $class = ContainerClass::create(['class' => 'A']);
        $size = ContainerSize::create(['size' => '20ft']);

        $this->variant = ContainerVariant::create([
            'container_id' => $container->id,
            'container_class_id' => $class->id,
            'container_size_id' => $size->id,
            'is_active' => true,
        ]);

        LaneTariffRatePrice::create([
            'lane_tariff_rate_id' => $this->tariffRate->rate_id,
            'container_variant_id' => $this->variant->id,
            'frt' => 10000,
        ]);

        ContainerAsset::create([
            'container_variant_id' => $this->variant->id,
            'container_no' => 'TEST1234567',
            'status' => ContainerAsset::STATUS_AVAILABLE,
            'current_port_id' => $this->origin->port_id,
        ]);

        $role = SettingRole::create(['role_name' => 'superadmin', 'is_system' => true]);

        foreach (['booking.create', 'booking.confirm', 'booking.cancel', 'booking.advance-status'] as $key) {
            $permission = Permission::firstOrCreate(['key' => $key], ['label' => $key, 'module' => 'Booking']);
            $role->permissions()->syncWithoutDetaching([$permission->id]);
        }

        $this->actingAs(User::factory()->create(['role_id' => $role->id]));
    }

    private function lineTemplate(): array
    {
        return [
            'origin_port_id' => $this->origin->port_id,
            'destination_port_id' => $this->destination->port_id,
            'origin_area_id' => $this->originArea->area_id,
            'destination_area_id' => $this->destinationArea->area_id,
            'origin_mode' => 'pier',
            'destination_mode' => 'pier',
            'container_variant_id' => $this->variant->id,
            'quantity' => 1,
            'auto_assign' => true,
            'container_asset_ids' => [],
        ];
    }

    /** @test */
    public function a_booking_with_no_transaction_details_lands_in_the_tentative_bucket()
    {
        $response = $this->postJson('/api/bookings', [
            'client_id' => $this->client->id,
            'lines' => [$this->lineTemplate()],
        ]);

        $response->assertCreated();

        $counts = $this->getJson('/api/cargo-build-up')->json('data');
        $countsByKey = collect($counts)->keyBy('key');

        $this->assertEquals(1, $countsByKey['cargo_build_up']['count']);
        $this->assertEquals(1, $countsByKey['tentative']['count']);
        $this->assertEquals(0, $countsByKey['live']['count']);

        $tentativeList = $this->getJson('/api/cargo-build-up/bookings?bucket=tentative')->json('data.data');
        $this->assertCount(1, $tentativeList);

        $liveList = $this->getJson('/api/cargo-build-up/bookings?bucket=live')->json('data.data');
        $this->assertCount(0, $liveList);
    }

    /** @test */
    public function a_booking_with_every_line_fully_detailed_lands_in_the_live_bucket()
    {
        $line = array_merge($this->lineTemplate(), [
            'consignee_name' => 'Juan Dela Cruz',
            'consignee_address' => '123 Rizal St, Cebu City',
            'consignee_contact_person' => 'Juan Dela Cruz',
            'consignee_contact_number' => '09171234567',
            'cargo_type' => 'General Merchandise',
            'declared_value' => 50000,
            'delivery_date' => now()->addDays(5)->toDateString(),
        ]);

        $response = $this->postJson('/api/bookings', [
            'client_id' => $this->client->id,
            'lines' => [$line],
        ]);

        $response->assertCreated();

        $counts = collect($this->getJson('/api/cargo-build-up')->json('data'))->keyBy('key');

        $this->assertEquals(1, $counts['cargo_build_up']['count']);
        $this->assertEquals(0, $counts['tentative']['count']);
        $this->assertEquals(1, $counts['live']['count']);

        $liveList = $this->getJson('/api/cargo-build-up/bookings?bucket=live')->json('data.data');
        $this->assertCount(1, $liveList);
    }

    /**
     * All 13 SOP buckets became tracked across Phases 3-6, so there's no
     * longer a real bucket left to exercise the "not yet tracked" guard
     * with - this now checks it still rejects a bogus/unknown key rather
     * than silently treating it as the default (cargo_build_up).
     */
    /** @test */
    public function an_unknown_bucket_key_is_rejected_instead_of_silently_falling_back()
    {
        $response = $this->getJson('/api/cargo-build-up/bookings?bucket=not_a_real_bucket');

        $response->assertStatus(422);
        $this->assertStringContainsString('not yet tracked', $response->json('message'));
    }

    /** @test */
    public function all_thirteen_buckets_are_now_tracked()
    {
        $index = collect($this->getJson('/api/cargo-build-up')->json('data'))->keyBy('key');

        $this->assertCount(13, $index);
        $this->assertTrue($index->every(fn ($bucket) => $bucket['tracked'] === true));
    }
}
