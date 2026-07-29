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

class BookingDispatchTest extends TestCase
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
            'booking.generate-dispatch-document', 'booking.assign-cv',
        ] as $key) {
            $permission = Permission::firstOrCreate(['key' => $key], ['label' => $key, 'module' => 'Booking']);
            $role->permissions()->syncWithoutDetaching([$permission->id]);
        }

        $this->actingAs(User::factory()->create(['role_id' => $role->id]));
    }

    private function makeDeliveryType(bool $originTrucking): DeliveryType
    {
        return DeliveryType::create([
            'code' => $originTrucking ? 'DD' : 'PP',
            'name' => $originTrucking ? 'Door to Door' : 'Pier to Pier',
            'includes_origin_trucking' => $originTrucking,
            'includes_destination_trucking' => $originTrucking,
        ]);
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
    public function a_door_origin_line_auto_routes_to_atw()
    {
        $this->makeDeliveryType(originTrucking: true);
        $booking = $this->bookLine();
        $line = $booking->lines->first();

        $response = $this->postJson("/api/booking-lines/{$line->id}/dispatch-document", []);

        $response->assertCreated();
        $this->assertEquals('ATW', $response->json('data.document_type'));
        $this->assertStringStartsWith('ATW-', $response->json('data.document_number'));
    }

    /** @test */
    public function a_pier_origin_line_auto_routes_to_can()
    {
        $this->makeDeliveryType(originTrucking: false);
        $booking = $this->bookLine(originTrucking: false);
        $line = $booking->lines->first();

        $response = $this->postJson("/api/booking-lines/{$line->id}/dispatch-document", []);

        $response->assertCreated();
        $this->assertEquals('CAN', $response->json('data.document_type'));
    }

    /** @test */
    public function a_client_flagged_always_route_atw_overrides_a_pier_origin_line_to_atw()
    {
        $this->makeDeliveryType(originTrucking: false);
        $this->client->update(['always_route_atw' => true]);
        $booking = $this->bookLine(originTrucking: false);
        $line = $booking->lines->first();

        $response = $this->postJson("/api/booking-lines/{$line->id}/dispatch-document", []);

        $response->assertCreated();
        $this->assertEquals('ATW', $response->json('data.document_type'));
    }

    /** @test */
    public function a_line_cannot_get_a_second_dispatch_document()
    {
        $this->makeDeliveryType(originTrucking: true);
        $booking = $this->bookLine();
        $line = $booking->lines->first();

        $this->postJson("/api/booking-lines/{$line->id}/dispatch-document", [])->assertCreated();

        $response = $this->postJson("/api/booking-lines/{$line->id}/dispatch-document", []);
        $response->assertStatus(422);
        $this->assertStringContainsString('already has a dispatch document', $response->json('message'));
    }

    /** @test */
    public function cv_assignment_updates_the_container_unit()
    {
        $this->makeDeliveryType(originTrucking: true);
        $booking = $this->bookLine();
        $unit = $booking->containerUnits()->first();

        $response = $this->putJson("/api/booking-container-units/{$unit->id}/cv-assignment", [
            'proforma_bl_number' => 'PBL-0001',
            'waybill_number' => 'WB-0001',
            'seal_no' => 'SEAL-0001',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('booking_container_units', [
            'id' => $unit->id,
            'proforma_bl_number' => 'PBL-0001',
            'waybill_number' => 'WB-0001',
            'seal_no' => 'SEAL-0001',
        ]);
    }

    /** @test */
    public function bucket_counts_move_from_for_atw_to_for_cv_assignment_as_the_document_is_generated()
    {
        $this->makeDeliveryType(originTrucking: true);
        $booking = $this->bookLine();
        $line = $booking->lines->first();

        $before = collect($this->getJson('/api/cargo-build-up')->json('data'))->keyBy('key');
        $this->assertEquals(1, $before['for_atw']['count']);
        $this->assertEquals(0, $before['for_cv_assignment']['count']);

        $this->postJson("/api/booking-lines/{$line->id}/dispatch-document", [])->assertCreated();

        $after = collect($this->getJson('/api/cargo-build-up')->json('data'))->keyBy('key');
        $this->assertEquals(0, $after['for_atw']['count']);
        $this->assertEquals(1, $after['for_cv_assignment']['count']);
    }
}
