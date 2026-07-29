<?php

namespace Tests\Feature;

use App\Models\Container;
use App\Models\ContainerAsset;
use App\Models\ContainerAssetLocationHistory;
use App\Models\ContainerClass;
use App\Models\ContainerSize;
use App\Models\ContainerVariant;
use App\Models\NavMenu;
use App\Models\Port;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContainerAssetTest extends TestCase
{
    use RefreshDatabase;

    private function makeVariant(): ContainerVariant
    {
        $container = Container::create(['code' => 'DRY', 'name' => 'Dry Van', 'is_active' => true]);
        $class = ContainerClass::create(['class' => 'High Cube']);
        $size = ContainerSize::create(['size' => '40ft']);

        return ContainerVariant::create([
            'container_id' => $container->id,
            'container_class_id' => $class->id,
            'container_size_id' => $size->id,
            'is_active' => true,
        ]);
    }

    private function makePort(string $code): Port
    {
        return Port::create(['code' => $code, 'name' => strtoupper($code).' PORT', 'is_active' => true]);
    }

    private function actingUser(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        return $user;
    }

    /** @test */
    public function it_creates_a_container_asset_with_its_variant_and_current_port()
    {
        $variant = $this->makeVariant();
        $port = $this->makePort('MNL');

        $asset = ContainerAsset::create([
            'container_variant_id' => $variant->id,
            'container_no' => 'MSCU1234567',
            'status' => ContainerAsset::STATUS_AVAILABLE,
            'current_port_id' => $port->port_id,
        ]);

        $this->assertEquals(ContainerAsset::STATUS_AVAILABLE, $asset->status);
        $this->assertTrue($asset->containerVariant->is($variant));
        $this->assertTrue($asset->currentPort->is($port));
    }

    /** @test */
    public function transitioning_status_records_a_location_history_row()
    {
        $variant = $this->makeVariant();
        $originPort = $this->makePort('MNL');
        $user = User::factory()->create();

        $asset = ContainerAsset::create([
            'container_variant_id' => $variant->id,
            'container_no' => 'MSCU7654321',
            'status' => ContainerAsset::STATUS_BOOKED,
            'current_port_id' => $originPort->port_id,
        ]);

        $history = $asset->applyChange(
            ['status' => ContainerAsset::STATUS_IN_TRANSIT, 'current_port_id' => $originPort->port_id],
            ContainerAssetLocationHistory::SOURCE_PIER_CHECKIN,
            $user->id
        );

        $asset->refresh();

        $this->assertEquals(ContainerAsset::STATUS_IN_TRANSIT, $asset->status);
        $this->assertNotNull($asset->last_movement_at);
        $this->assertDatabaseHas('container_asset_location_history', [
            'id' => $history->id,
            'container_asset_id' => $asset->id,
            'port_id' => $originPort->port_id,
            'status_at_time' => ContainerAsset::STATUS_LABELS[ContainerAsset::STATUS_IN_TRANSIT],
            'source' => ContainerAssetLocationHistory::SOURCE_PIER_CHECKIN,
            'recorded_by' => $user->id,
        ]);
        $this->assertCount(1, $asset->locationHistory);
    }

    /** @test */
    public function available_endpoint_only_returns_containers_at_the_origin_port()
    {
        $variant = $this->makeVariant();
        $origin = $this->makePort('MNL');
        $elsewhere = $this->makePort('CEB');
        $this->actingUser();

        $far = ContainerAsset::create([
            'container_variant_id' => $variant->id,
            'container_no' => 'FARU1111111',
            'status' => ContainerAsset::STATUS_AVAILABLE,
            'current_port_id' => $elsewhere->port_id,
            'last_movement_at' => now()->subDays(5),
        ]);

        $idle = ContainerAsset::create([
            'container_variant_id' => $variant->id,
            'container_no' => 'IDLE1111111',
            'status' => ContainerAsset::STATUS_AVAILABLE,
            'current_port_id' => $origin->port_id,
            'last_movement_at' => now()->subDays(3),
        ]);

        $recent = ContainerAsset::create([
            'container_variant_id' => $variant->id,
            'container_no' => 'RECE2222222',
            'status' => ContainerAsset::STATUS_AVAILABLE,
            'current_port_id' => $origin->port_id,
            'last_movement_at' => now()->subDay(),
        ]);

        $response = $this->getJson('/api/container-assets/available?'.http_build_query([
            'container_variant_id' => $variant->id,
            'origin_port_id' => $origin->port_id,
        ]));

        $response->assertOk();
        $ids = collect($response->json('data'))->pluck('id')->all();

        // Only origin-port assets come back, longest-idle first - the
        // other port's container ($far) must never appear.
        $this->assertEquals([$idle->id, $recent->id], $ids);
    }

    /** @test */
    public function reserve_fails_cleanly_when_an_asset_is_no_longer_available()
    {
        $variant = $this->makeVariant();
        $port = $this->makePort('MNL');
        $this->actingUser();

        $asset = ContainerAsset::create([
            'container_variant_id' => $variant->id,
            'container_no' => 'RACE1111111',
            'status' => ContainerAsset::STATUS_BOOKED,
            'current_port_id' => $port->port_id,
        ]);

        $response = $this->postJson('/api/container-assets/reserve', [
            'container_asset_ids' => [$asset->id],
        ]);

        $response->assertStatus(422);
        $this->assertEquals(ContainerAsset::STATUS_BOOKED, $asset->fresh()->status);
    }

    /** @test */
    public function release_rejects_a_non_booked_asset()
    {
        $variant = $this->makeVariant();
        $port = $this->makePort('MNL');
        $this->actingUser();

        $asset = ContainerAsset::create([
            'container_variant_id' => $variant->id,
            'container_no' => 'AVAL1111111',
            'status' => ContainerAsset::STATUS_AVAILABLE,
            'current_port_id' => $port->port_id,
        ]);

        $response = $this->postJson('/api/container-assets/release', [
            'container_asset_ids' => [$asset->id],
        ]);

        $response->assertStatus(422);
        $this->assertEquals(ContainerAsset::STATUS_AVAILABLE, $asset->fresh()->status);
    }

    /** @test */
    public function reserve_then_release_round_trips_status_correctly()
    {
        $variant = $this->makeVariant();
        $port = $this->makePort('MNL');
        $this->actingUser();

        $asset = ContainerAsset::create([
            'container_variant_id' => $variant->id,
            'container_no' => 'ROND1111111',
            'status' => ContainerAsset::STATUS_AVAILABLE,
            'current_port_id' => $port->port_id,
        ]);

        $this->postJson('/api/container-assets/reserve', [
            'container_asset_ids' => [$asset->id],
        ])->assertOk();

        $this->assertEquals(ContainerAsset::STATUS_BOOKED, $asset->fresh()->status);

        $this->postJson('/api/container-assets/release', [
            'container_asset_ids' => [$asset->id],
        ])->assertOk();

        $this->assertEquals(ContainerAsset::STATUS_AVAILABLE, $asset->fresh()->status);
        $this->assertCount(2, $asset->fresh()->locationHistory);
    }

    /** @test */
    public function management_endpoints_are_gated_by_the_container_inventory_nav_menu_entry()
    {
        $variant = $this->makeVariant();
        $port = $this->makePort('MNL');

        NavMenu::create([
            'title' => 'Container Inventory',
            'link' => '/page_container_inventory',
            'allowed_roles' => json_encode(['1']),
            'parent_menu' => '0',
            'menu_order' => '9',
        ]);

        // role_id 3 ("user") is not in the allowed_roles above.
        $this->actingAs(User::factory()->create(['role_id' => 3]));

        $response = $this->postJson('/api/container-assets', [
            'container_variant_id' => $variant->id,
            'container_no' => 'GATE1111111',
            'current_port_id' => $port->port_id,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('container_assets', ['container_no' => 'GATE1111111']);
    }

    /** @test */
    public function management_endpoint_succeeds_for_a_role_allowed_by_the_nav_menu_entry()
    {
        $variant = $this->makeVariant();
        $port = $this->makePort('MNL');

        NavMenu::create([
            'title' => 'Container Inventory',
            'link' => '/page_container_inventory',
            'allowed_roles' => json_encode(['1']),
            'parent_menu' => '0',
            'menu_order' => '9',
        ]);

        $this->actingAs(User::factory()->create(['role_id' => 1]));

        $response = $this->postJson('/api/container-assets', [
            'container_variant_id' => $variant->id,
            'container_no' => 'GATE2222222',
            'current_port_id' => $port->port_id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('container_assets', ['container_no' => 'GATE2222222']);
    }
}
