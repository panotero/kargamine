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

class VesselVoyageLoadlistTest extends TestCase
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
            'booking.generate-dispatch-document', 'booking.assign-cv', 'booking.gate-scan',
            'booking.issue-eir', 'booking.assign-voyage', 'booking.generate-loadlist',
        ] as $key) {
            $permission = Permission::firstOrCreate(['key' => $key], ['label' => $key, 'module' => 'Booking']);
            $role->permissions()->syncWithoutDetaching([$permission->id]);
        }

        $this->actingAs(User::factory()->create(['role_id' => $role->id]));
    }

    private function confirmedBooking(): Booking
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

        $response = $this->postJson('/api/bookings', ['client_id' => $this->client->id, 'lines' => [$line]]);
        $response->assertCreated();

        $booking = Booking::findOrFail($response->json('data.booking_id'));
        $this->postJson("/api/bookings/{$booking->uuid}/confirm")->assertOk();

        return $booking->fresh();
    }

    /** @test */
    public function the_bill_of_lading_downloads_without_error_after_the_earlier_lane_removal()
    {
        // Regression check: pdf.bill-of-lading used to reference the
        // removed Booking::lane() relation (route moved to per-line) and
        // would fatal on render. This just needs to not throw and to
        // come back as a PDF.
        $booking = $this->confirmedBooking();

        $response = $this->get("/api/bookings/{$booking->uuid}/bol");

        $response->assertOk();
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }

    /** @test */
    public function a_voyage_loadlist_downloads_as_a_pdf()
    {
        $booking = $this->confirmedBooking();
        $bookingLine = $booking->lines->first();
        $unit = $booking->containerUnits()->first();

        $this->postJson("/api/booking-lines/{$bookingLine->id}/dispatch-document", [])->assertCreated();
        $this->postJson("/api/booking-container-units/{$unit->id}/eir-out", [])->assertCreated();
        $this->postJson('/api/gate-scan', ['code' => $unit->gate_pass_code])->assertOk();
        $this->postJson('/api/gate-scan', ['code' => $unit->gate_pass_code])->assertOk();
        $this->postJson("/api/booking-container-units/{$unit->id}/eir-in", [])->assertCreated();

        $voyage = $this->postJson('/api/vesselVoyages', [
            'vessel_name' => 'Callista 84',
            'voyage_mnemonic' => 'Lady Callista 84-A',
            'voyage_leg' => 'A',
            'origin_port_id' => $this->origin->port_id,
            'destination_port_id' => $this->destination->port_id,
        ]);
        $voyage->assertCreated();
        $voyageId = $voyage->json('data.id');

        $this->postJson("/api/booking-container-units/{$unit->id}/assign-voyage", [
            'vessel_voyage_id' => $voyageId,
        ])->assertOk();

        $response = $this->get("/api/vesselVoyages/{$voyageId}/loadlist");

        $response->assertOk();
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }
}
