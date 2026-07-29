<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingContainerUnit;
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

class BookingVoyageTest extends TestCase
{
    use RefreshDatabase;

    private Port $origin;

    private Port $destination;

    private Port $relayPort;

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
        $this->relayPort = Port::create(['code' => 'ILO', 'name' => 'Iloilo', 'is_active' => true]);

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

        VatRate::create(['rate_percent' => 12, 'effective_date' => now()->subDay()->toDateString(), 'is_active' => true]);

        DeliveryType::create([
            'code' => 'DD',
            'name' => 'Door to Door',
            'includes_origin_trucking' => true,
            'includes_destination_trucking' => true,
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

        foreach ([
            'booking.create', 'booking.confirm', 'booking.cancel', 'booking.advance-status',
            'booking.generate-dispatch-document', 'booking.assign-cv', 'booking.gate-scan',
            'booking.issue-eir', 'booking.assign-voyage',
        ] as $key) {
            $permission = Permission::firstOrCreate(['key' => $key], ['label' => $key, 'module' => 'Booking']);
            $role->permissions()->syncWithoutDetaching([$permission->id]);
        }

        $this->actingAs(User::factory()->create(['role_id' => $role->id]));
    }

    /** Books a line and drives its one container unit all the way to In Yard. */
    private function makeInYardUnit(): BookingContainerUnit
    {
        $line = [
            'origin_port_id' => $this->origin->port_id,
            'destination_port_id' => $this->destination->port_id,
            'origin_area_id' => $this->originArea->area_id,
            'destination_area_id' => $this->destinationArea->area_id,
            'origin_mode' => 'door',
            'destination_mode' => 'door',
            'container_variant_id' => $this->variant->id,
            'quantity' => 1,
            'auto_assign' => true,
            'container_asset_ids' => [],
            'consignee_name' => 'Juan Dela Cruz',
            'consignee_address' => '123 Rizal St',
            'cargo_type' => 'General Merchandise',
            'declared_value' => 50000,
            'delivery_date' => now()->addDays(5)->toDateString(),
        ];

        $response = $this->postJson('/api/bookings', [
            'client_id' => $this->client->id,
            'lines' => [$line],
        ]);
        $response->assertCreated();

        $booking = Booking::with('lines')->findOrFail($response->json('data.booking_id'));
        $bookingLine = $booking->lines->first();
        $unit = $booking->containerUnits()->first();

        $this->postJson("/api/booking-lines/{$bookingLine->id}/dispatch-document", [])->assertCreated();
        $this->postJson("/api/booking-container-units/{$unit->id}/eir-out", [])->assertCreated();
        $this->postJson('/api/gate-scan', ['code' => $unit->gate_pass_code])->assertOk(); // OUT
        $this->postJson('/api/gate-scan', ['code' => $unit->gate_pass_code])->assertOk(); // IN
        $this->postJson("/api/booking-container-units/{$unit->id}/eir-in", [])->assertCreated();

        return $unit->fresh();
    }

    private function makeVoyage(): int
    {
        $response = $this->postJson('/api/vesselVoyages', [
            'vessel_name' => 'Callista 84',
            'voyage_mnemonic' => 'Lady Callista 84-A',
            'voyage_leg' => 'A',
            'origin_port_id' => $this->origin->port_id,
            'destination_port_id' => $this->destination->port_id,
        ]);
        $response->assertCreated();

        return $response->json('data.id');
    }

    /** @test */
    public function a_voyage_cannot_be_assigned_before_the_unit_reaches_in_yard()
    {
        // Book a line but stop right after dispatch document - not yet In Yard.
        $line = [
            'origin_port_id' => $this->origin->port_id,
            'destination_port_id' => $this->destination->port_id,
            'origin_area_id' => $this->originArea->area_id,
            'destination_area_id' => $this->destinationArea->area_id,
            'origin_mode' => 'door',
            'destination_mode' => 'door',
            'container_variant_id' => $this->variant->id,
            'quantity' => 1,
            'auto_assign' => true,
            'container_asset_ids' => [],
        ];
        $response = $this->postJson('/api/bookings', ['client_id' => $this->client->id, 'lines' => [$line]]);
        $response->assertCreated();
        $unit = Booking::findOrFail($response->json('data.booking_id'))->containerUnits()->first();

        $voyageId = $this->makeVoyage();

        $assign = $this->postJson("/api/booking-container-units/{$unit->id}/assign-voyage", [
            'vessel_voyage_id' => $voyageId,
        ]);

        $assign->assertStatus(422);
        $this->assertStringContainsString('not In Yard yet', $assign->json('message'));
    }

    /** @test */
    public function a_voyage_can_be_assigned_once_in_yard_with_optional_teu_and_relay_port()
    {
        $unit = $this->makeInYardUnit();
        $voyageId = $this->makeVoyage();

        $response = $this->postJson("/api/booking-container-units/{$unit->id}/assign-voyage", [
            'vessel_voyage_id' => $voyageId,
            'equivalent_teu' => 1.5,
            'relay_port_id' => $this->relayPort->port_id,
        ]);

        $response->assertOk();
        $this->assertEquals($voyageId, $response->json('data.vessel_voyage_id'));
        $this->assertNull($response->json('data.shut_out_at'));
    }

    /** @test */
    public function shut_out_requires_in_yard_and_clears_on_reassignment()
    {
        $unit = $this->makeInYardUnit();
        $voyageId = $this->makeVoyage();

        $this->postJson("/api/booking-container-units/{$unit->id}/assign-voyage", [
            'vessel_voyage_id' => $voyageId,
        ])->assertOk();

        $shutOut = $this->postJson("/api/booking-container-units/{$unit->id}/shut-out");
        $shutOut->assertOk();
        $this->assertNotNull($shutOut->json('data.shut_out_at'));

        // Reassigning (e.g. to the next voyage) clears the shut-out tag.
        $reassign = $this->postJson("/api/booking-container-units/{$unit->id}/assign-voyage", [
            'vessel_voyage_id' => $voyageId,
        ]);
        $reassign->assertOk();
        $this->assertNull($reassign->json('data.shut_out_at'));
    }

    /** @test */
    public function bucket_counts_progress_from_in_yard_to_for_vessel_loading_to_shut_out()
    {
        $unit = $this->makeInYardUnit();
        $voyageId = $this->makeVoyage();

        $counts = fn () => collect($this->getJson('/api/cargo-build-up')->json('data'))->keyBy('key');

        $before = $counts();
        $this->assertEquals(1, $before['in_yard']['count']);
        $this->assertEquals(0, $before['for_vessel_loading']['count']);
        $this->assertEquals(0, $before['shut_out']['count']);

        $this->postJson("/api/booking-container-units/{$unit->id}/assign-voyage", [
            'vessel_voyage_id' => $voyageId,
        ])->assertOk();

        $afterAssign = $counts();
        $this->assertEquals(1, $afterAssign['for_vessel_loading']['count']);
        $this->assertEquals(0, $afterAssign['shut_out']['count']);

        $this->postJson("/api/booking-container-units/{$unit->id}/shut-out")->assertOk();

        $afterShutOut = $counts();
        $this->assertEquals(0, $afterShutOut['for_vessel_loading']['count']);
        $this->assertEquals(1, $afterShutOut['shut_out']['count']);
    }

    /** @test */
    public function vessel_voyage_master_data_can_be_created_and_listed()
    {
        $this->makeVoyage();

        $response = $this->getJson('/api/vesselVoyages');
        $response->assertOk();
        $this->assertCount(1, $response->json('data.data'));
        $this->assertEquals('Lady Callista 84-A', $response->json('data.data.0.voyage_mnemonic'));
    }
}
