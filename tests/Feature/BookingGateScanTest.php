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

class BookingGateScanTest extends TestCase
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

    private function bookLine(): Booking
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

        return Booking::with('lines')->findOrFail($response->json('data.booking_id'));
    }

    /** @test */
    public function a_unit_cannot_gate_out_before_its_line_has_a_dispatch_document()
    {
        $booking = $this->bookLine();
        $unit = $booking->containerUnits()->first();

        $response = $this->postJson('/api/gate-scan', ['code' => $unit->gate_pass_code]);

        $response->assertStatus(422);
        $this->assertStringContainsString('not ready to gate out', $response->json('message'));
    }

    /** @test */
    public function scanning_a_code_twice_gates_it_out_then_in_and_a_third_scan_is_rejected()
    {
        $booking = $this->bookLine();
        $line = $booking->lines->first();
        $unit = $booking->containerUnits()->first();

        $this->postJson("/api/booking-lines/{$line->id}/dispatch-document", [])->assertCreated();
        $this->postJson("/api/booking-container-units/{$unit->id}/eir-out", [])->assertCreated();

        $out = $this->postJson('/api/gate-scan', ['code' => $unit->gate_pass_code]);
        $out->assertOk();
        $this->assertEquals('OUT', $out->json('action'));
        $this->assertNotNull($out->json('data.actual_gate_out_at'));
        $this->assertStringStartsWith('GP-OUT-', $out->json('data.gate_pass_out_number'));

        $in = $this->postJson('/api/gate-scan', ['code' => $unit->gate_pass_code]);
        $in->assertOk();
        $this->assertEquals('IN', $in->json('action'));
        $this->assertNotNull($in->json('data.actual_gate_in_at'));

        $again = $this->postJson('/api/gate-scan', ['code' => $unit->gate_pass_code]);
        $again->assertStatus(422);
        $this->assertStringContainsString('already completed its gate round trip', $again->json('message'));
    }

    /** @test */
    public function an_unknown_code_returns_404()
    {
        $response = $this->postJson('/api/gate-scan', ['code' => 'NOT-A-REAL-CODE']);
        $response->assertStatus(404);
    }

    /** @test */
    public function pending_endpoint_only_lists_units_awaiting_a_gate_action()
    {
        $booking = $this->bookLine();
        $line = $booking->lines->first();
        $unit = $booking->containerUnits()->first();

        // Not yet ready (no dispatch document) - shouldn't appear.
        $pendingBefore = $this->getJson('/api/gate-scan/pending')->json('data');
        $this->assertCount(0, $pendingBefore);

        $this->postJson("/api/booking-lines/{$line->id}/dispatch-document", [])->assertCreated();

        // Dispatch document alone isn't enough anymore (Phase 5 tightened
        // this) - EIR Out still needs to be issued.
        $pendingAfterDoc = $this->getJson('/api/gate-scan/pending')->json('data');
        $this->assertCount(0, $pendingAfterDoc);

        $this->postJson("/api/booking-container-units/{$unit->id}/eir-out", [])->assertCreated();

        $pendingAfterEir = $this->getJson('/api/gate-scan/pending')->json('data');
        $this->assertCount(1, $pendingAfterEir);
        $this->assertEquals($unit->id, $pendingAfterEir[0]['id']);

        $this->postJson('/api/gate-scan', ['code' => $unit->gate_pass_code])->assertOk();

        // Now awaiting Gate In instead - still exactly one pending unit.
        $pendingAfterOut = $this->getJson('/api/gate-scan/pending')->json('data');
        $this->assertCount(1, $pendingAfterOut);

        $this->postJson('/api/gate-scan', ['code' => $unit->gate_pass_code])->assertOk();

        // Fully round-tripped - no longer pending anything.
        $pendingAfterIn = $this->getJson('/api/gate-scan/pending')->json('data');
        $this->assertCount(0, $pendingAfterIn);
    }

    /** @test */
    public function bucket_counts_move_from_for_gate_out_to_pickup_in_transit_and_then_clear()
    {
        $booking = $this->bookLine();
        $line = $booking->lines->first();
        $unit = $booking->containerUnits()->first();

        $this->postJson("/api/booking-lines/{$line->id}/dispatch-document", [])->assertCreated();
        $this->postJson("/api/booking-container-units/{$unit->id}/eir-out", [])->assertCreated();

        $counts = fn () => collect($this->getJson('/api/cargo-build-up')->json('data'))->keyBy('key');

        $before = $counts();
        $this->assertEquals(1, $before['for_gate_out']['count']);
        $this->assertEquals(0, $before['pickup_in_transit']['count']);

        $this->postJson('/api/gate-scan', ['code' => $unit->gate_pass_code])->assertOk();

        $afterOut = $counts();
        $this->assertEquals(0, $afterOut['for_gate_out']['count']);
        $this->assertEquals(1, $afterOut['pickup_in_transit']['count']);

        $this->postJson('/api/gate-scan', ['code' => $unit->gate_pass_code])->assertOk();

        $afterIn = $counts();
        $this->assertEquals(0, $afterIn['for_gate_out']['count']);
        $this->assertEquals(0, $afterIn['pickup_in_transit']['count']);
    }
}
