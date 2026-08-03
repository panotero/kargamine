<?php

namespace Tests\Feature;

use App\Models\ClientMaster;
use App\Models\SettingRole;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientMasterFormTest extends TestCase
{
    use RefreshDatabase;

    private function actingUser(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        return $user;
    }

    private function stage1Payload(array $overrides = []): array
    {
        return array_merge([
            'company_name' => 'Acme Corp',
            'client_mnemonic' => 'ACME',
            'addresses' => [
                [
                    'address_type' => 'Office',
                    'is_primary' => true,
                    'address_no' => '123',
                    'address_street' => 'Main St',
                    'address_town_city' => 'Manila',
                ],
            ],
        ], $overrides);
    }

    /** @test */
    public function client_mnemonic_is_required_and_unique()
    {
        $this->actingUser();

        $this->postJson('/api/clientMasters/stage1', $this->stage1Payload())
            ->assertOk()
            ->assertJson(['success' => true]);

        $response = $this->postJson('/api/clientMasters/stage1', $this->stage1Payload([
            'company_name' => 'Different Corp',
        ]));

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
        $this->assertArrayHasKey('client_mnemonic', $response->json('invalid_fields'));
    }

    /** @test */
    public function account_manager_id_is_snapshotted_from_the_csr_team_leader_at_creation()
    {
        $team = Team::create(['name' => 'Sales Team A']);

        $leader = User::factory()->create(['team_id' => $team->id, 'is_team_leader' => true]);
        $member = User::factory()->create(['team_id' => $team->id, 'is_team_leader' => false]);

        $this->actingAs($member);

        $response = $this->postJson('/api/clientMasters/stage1', $this->stage1Payload());
        $response->assertOk();

        $client = ClientMaster::where('client_mnemonic', 'ACME')->firstOrFail();
        $this->assertEquals($leader->id, $client->account_manager_id);
    }

    /** @test */
    public function account_manager_id_is_left_null_when_there_is_no_team_leader()
    {
        // No team at all - no leader to find.
        $this->actingUser();

        $this->postJson('/api/clientMasters/stage1', $this->stage1Payload())->assertOk();

        $client = ClientMaster::where('client_mnemonic', 'ACME')->firstOrFail();
        $this->assertNull($client->account_manager_id);
    }

    /** @test */
    public function finance_stage3_persists_new_fields_and_rejects_an_invalid_cro_user_id()
    {
        $this->actingUser();

        $stage1 = $this->postJson('/api/clientMasters/stage1', $this->stage1Payload())->json('data');
        $uuid = $stage1['uuid'];

        $cro = User::factory()->create();

        $response = $this->postJson("/api/clientMasters/{$uuid}/stage3", [
            'finance' => [
                'client_business_name' => 'Acme Corp Trading',
                'tin_number' => '123-456-789',
                'tax_status' => 'VAT',
                'credit_terms' => 'Net 30',
                'mode_of_payment' => 'Bank Transfer',
                'cro_user_id' => $cro->id,
                'max_declared_value' => 500000.50,
            ],
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('client_finance', [
            'client_business_name' => 'Acme Corp Trading',
            'tin_number' => '123-456-789',
            'cro_user_id' => $cro->id,
        ]);

        $invalidResponse = $this->postJson("/api/clientMasters/{$uuid}/stage3", [
            'finance' => [
                'cro_user_id' => 999999,
            ],
        ]);

        $invalidResponse->assertStatus(422);
    }

    /** @test */
    public function contact_stage2_persists_a_contact_with_nested_addresses()
    {
        $this->actingUser();

        $stage1 = $this->postJson('/api/clientMasters/stage1', $this->stage1Payload())->json('data');
        $uuid = $stage1['uuid'];

        $response = $this->postJson("/api/clientMasters/{$uuid}/stage2", [
            'contacts' => [
                [
                    'first_name' => 'Jane',
                    'last_name' => 'Doe',
                    'email' => 'jane@example.com',
                    'addresses' => [
                        [
                            'address_type' => 'Office',
                            'is_primary' => true,
                            'address_street' => 'Contact St',
                        ],
                    ],
                ],
            ],
        ]);

        $response->assertOk();

        $client = ClientMaster::where('uuid', $uuid)->with('contacts.addresses')->firstOrFail();
        $this->assertCount(1, $client->contacts);
        $contact = $client->contacts->first();
        $this->assertCount(1, $contact->addresses);
        $this->assertDatabaseHas('client_contact_addresses', [
            'contact_id' => $contact->id,
            'address_street' => 'Contact St',
        ]);
    }

    /** @test */
    public function ancillary_stage4_snapshots_parent_fields_and_allows_zero_rows()
    {
        $this->actingUser();

        $stage1 = $this->postJson('/api/clientMasters/stage1', $this->stage1Payload())->json('data');
        $uuid = $stage1['uuid'];

        $this->postJson("/api/clientMasters/{$uuid}/stage3", [
            'finance' => ['client_business_name' => 'Acme Business Name'],
        ])->assertOk();

        $response = $this->postJson("/api/clientMasters/{$uuid}/stage4", [
            'ancillary_services' => [
                [
                    'required_service' => 'Extra Stuffing',
                    'location' => 'Manila',
                    'unit' => 'per container',
                    'unit_rate_vat_ex' => 1500,
                    // Client attempts to override the snapshot fields -
                    // these must be ignored server-side.
                    'client_code' => 'HACKED',
                    'client_mnemonic' => 'HACKED',
                    'client_business_name' => 'HACKED',
                ],
            ],
        ]);

        $response->assertOk();

        $client = ClientMaster::where('uuid', $uuid)->firstOrFail();
        $this->assertDatabaseHas('client_ancillary_services', [
            'client_id' => $client->id,
            'required_service' => 'Extra Stuffing',
            'client_code' => $client->customer_code,
            'client_mnemonic' => 'ACME',
            'client_business_name' => 'Acme Business Name',
        ]);

        // Zero rows must be allowed (ancillary is optional).
        $emptyResponse = $this->postJson("/api/clientMasters/{$uuid}/stage4", [
            'ancillary_services' => [],
        ]);
        $emptyResponse->assertOk();
        $this->assertDatabaseCount('client_ancillary_services', 0);
    }

    /** @test */
    public function byRole_endpoint_returns_only_users_with_the_matching_role()
    {
        $this->actingUser();

        $creditOfficerRole = SettingRole::create(['role_name' => 'Credit Officer', 'is_system' => false]);
        $otherRole = SettingRole::create(['role_name' => 'user', 'is_system' => true]);

        $co = User::factory()->create(['role_id' => $creditOfficerRole->id]);
        $other = User::factory()->create(['role_id' => $otherRole->id]);

        $response = $this->getJson('/api/users/byRole?role=' . urlencode('Credit Officer'));

        $response->assertOk();
        $ids = collect($response->json('data'))->pluck('id')->all();

        $this->assertContains($co->id, $ids);
        $this->assertNotContains($other->id, $ids);
    }
}
