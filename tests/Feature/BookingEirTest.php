<?php

namespace Tests\Feature;

use App\Models\Booking;
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

class BookingEirTest extends TestCase
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

        VatRate::create(['rate_percent' => 12, 'effective_date' => now()->subDay()->toDateString(), 'is_active' => true]);

        DeliveryType::create([
            'code' => 'DD',
            'name' => 'Door to Door',
            'includes_origin_trucking' => true,
            'includes_destination_trucking' => true,
        ]);

        DeliveryType::create([
            'code' => 'PP',
            'name' => 'Pier to Pier',
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

        foreach ([
            'booking.create', 'booking.confirm', 'booking.cancel', 'booking.advance-status',
            'booking.generate-dispatch-document', 'booking.assign-cv', 'booking.gate-scan', 'booking.issue-eir',
        ] as $key) {
            $permission = Permission::firstOrCreate(['key' => $key], ['label' => $key, 'module' => 'Booking']);
            $role->permissions()->syncWithoutDetaching([$permission->id]);
        }

        $this->actingAs(User::factory()->create(['role_id' => $role->id]));
    }

    private function bookLine(bool $originTrucking = true): Booking
    {
        $mode = $originTrucking ? 'door' : 'pier';

        $line = [
            'origin_port_id' => $this->origin->port_id,
            'destination_port_id' => $this->destination->port_id,
            'origin_area_id' => $this->originArea->area_id,
            'destination_area_id' => $this->destinationArea->area_id,
            'origin_mode' => $mode,
            'destination_mode' => $mode,
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

        return Booking::with('lines')->findOrFail($response->json('data.booking_id'));
    }

    /** @test */
    public function eir_out_cannot_be_issued_before_the_dispatch_document()
    {
        $booking = $this->bookLine();
        $unit = $booking->containerUnits()->first();

        $response = $this->postJson("/api/booking-container-units/{$unit->id}/eir-out", []);

        $response->assertStatus(422);
        $this->assertStringContainsString('dispatch document', $response->json('message'));
    }

    /** @test */
    public function eir_out_requires_a_driver_id_photo_for_a_pier_origin_line()
    {
        $booking = $this->bookLine(originTrucking: false); // pier origin -> CAN -> ID photo required
        $line = $booking->lines->first();
        $unit = $booking->containerUnits()->first();

        $this->postJson("/api/booking-lines/{$line->id}/dispatch-document", [])->assertCreated();

        $withoutPhoto = $this->postJson("/api/booking-container-units/{$unit->id}/eir-out", []);
        $withoutPhoto->assertStatus(422);
        $this->assertArrayHasKey('driver_id_photo_path', $withoutPhoto->json('errors') ?? $withoutPhoto->json());

        $withPhoto = $this->postJson("/api/booking-container-units/{$unit->id}/eir-out", [
            'driver_id_photo_path' => '/storage/uploads/booking/eir/fake.webp',
        ]);
        $withPhoto->assertCreated();
        $this->assertEquals('OUT', $withPhoto->json('data.direction'));
    }

    /** @test */
    public function eir_out_does_not_require_a_driver_id_photo_for_a_door_origin_line()
    {
        $booking = $this->bookLine(originTrucking: true); // door origin -> ATW -> no ID photo required
        $line = $booking->lines->first();
        $unit = $booking->containerUnits()->first();

        $this->postJson("/api/booking-lines/{$line->id}/dispatch-document", [])->assertCreated();

        $response = $this->postJson("/api/booking-container-units/{$unit->id}/eir-out", []);
        $response->assertCreated();
    }

    /** @test */
    public function gate_out_now_requires_eir_out_not_just_the_dispatch_document()
    {
        $booking = $this->bookLine(originTrucking: true);
        $line = $booking->lines->first();
        $unit = $booking->containerUnits()->first();

        $this->postJson("/api/booking-lines/{$line->id}/dispatch-document", [])->assertCreated();

        // Dispatch document exists but EIR Out doesn't yet - must still be rejected.
        $tooEarly = $this->postJson('/api/gate-scan', ['code' => $unit->gate_pass_code]);
        $tooEarly->assertStatus(422);

        $this->postJson("/api/booking-container-units/{$unit->id}/eir-out", [])->assertCreated();

        $now = $this->postJson('/api/gate-scan', ['code' => $unit->gate_pass_code]);
        $now->assertOk();
        $this->assertEquals('OUT', $now->json('action'));
    }

    /** @test */
    public function eir_in_cannot_be_issued_before_the_container_is_scanned_back_in()
    {
        $booking = $this->bookLine();
        $line = $booking->lines->first();
        $unit = $booking->containerUnits()->first();

        $this->postJson("/api/booking-lines/{$line->id}/dispatch-document", [])->assertCreated();
        $this->postJson("/api/booking-container-units/{$unit->id}/eir-out", [])->assertCreated();
        $this->postJson('/api/gate-scan', ['code' => $unit->gate_pass_code])->assertOk(); // gates OUT only

        $response = $this->postJson("/api/booking-container-units/{$unit->id}/eir-in", []);
        $response->assertStatus(422);
        $this->assertStringContainsString('not been scanned back in', $response->json('message'));
    }

    /** @test */
    public function bucket_counts_progress_through_documentation_gate_out_transit_partial_in_yard_and_in_yard()
    {
        $booking = $this->bookLine();
        $line = $booking->lines->first();
        $unit = $booking->containerUnits()->first();

        $counts = fn () => collect($this->getJson('/api/cargo-build-up')->json('data'))->keyBy('key');

        // Dispatch document not issued yet - nothing in any Phase 5 bucket.
        $this->assertEquals(0, $counts()['for_documentation']['count']);

        $this->postJson("/api/booking-lines/{$line->id}/dispatch-document", [])->assertCreated();
        $this->assertEquals(1, $counts()['for_documentation']['count']);
        $this->assertEquals(0, $counts()['for_gate_out']['count']);

        $this->postJson("/api/booking-container-units/{$unit->id}/eir-out", [])->assertCreated();
        $this->assertEquals(0, $counts()['for_documentation']['count']);
        $this->assertEquals(1, $counts()['for_gate_out']['count']);

        $this->postJson('/api/gate-scan', ['code' => $unit->gate_pass_code])->assertOk(); // OUT
        $this->assertEquals(0, $counts()['for_gate_out']['count']);
        $this->assertEquals(1, $counts()['pickup_in_transit']['count']);

        $this->postJson('/api/gate-scan', ['code' => $unit->gate_pass_code])->assertOk(); // IN
        $this->assertEquals(0, $counts()['pickup_in_transit']['count']);
        $this->assertEquals(1, $counts()['partial_in_yard']['count']);
        $this->assertEquals(0, $counts()['in_yard']['count']);

        $this->postJson("/api/booking-container-units/{$unit->id}/eir-in", [])->assertCreated();
        $this->assertEquals(0, $counts()['partial_in_yard']['count']);
        $this->assertEquals(1, $counts()['in_yard']['count']);
    }

    /** @test */
    public function a_foul_trip_never_counts_as_in_yard_even_with_eir_in_issued()
    {
        $booking = $this->bookLine();
        $line = $booking->lines->first();
        $unit = $booking->containerUnits()->first();

        $this->postJson("/api/booking-lines/{$line->id}/dispatch-document", [
            'trip_type' => 'Single Foul',
        ])->assertCreated();

        $this->postJson("/api/booking-container-units/{$unit->id}/eir-out", [])->assertCreated();
        $this->postJson('/api/gate-scan', ['code' => $unit->gate_pass_code])->assertOk();
        $this->postJson('/api/gate-scan', ['code' => $unit->gate_pass_code])->assertOk();
        $this->postJson("/api/booking-container-units/{$unit->id}/eir-in", [])->assertCreated();

        $counts = collect($this->getJson('/api/cargo-build-up')->json('data'))->keyBy('key');
        $this->assertEquals(0, $counts['in_yard']['count']);
    }
}
