<?php

namespace Tests\Feature;

use App\Enums\DocumentType;
use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'document_type' => DocumentType::Passport->value,
            'document_name' => 'My Passport',
            'expiry_date' => '2030-01-01',
            'reminder_days' => 30,
        ], $overrides);
    }

    public function test_unauthenticated_cannot_access_documents(): void
    {
        $this->getJson('/api/v1/documents')->assertStatus(401);
    }

    public function test_user_can_create_document(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')
            ->postJson('/api/v1/documents', $this->validPayload());

        $response->assertStatus(201)
            ->assertJson(['status' => true])
            ->assertJsonPath('data.document_type', DocumentType::Passport->value)
            ->assertJsonPath('data.document_name', 'My Passport');
    }

    public function test_document_type_is_required(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api')
            ->postJson('/api/v1/documents', $this->validPayload(['document_type' => null]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['document_type']);
    }

    public function test_document_type_must_be_valid_enum(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api')
            ->postJson('/api/v1/documents', $this->validPayload(['document_type' => 'invalid_type']))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['document_type']);
    }

    public function test_user_can_list_own_documents(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($user, 'api')->postJson('/api/v1/documents', $this->validPayload());
        $this->actingAs($other, 'api')->postJson('/api/v1/documents', $this->validPayload(['document_name' => 'Other']));

        $response = $this->actingAs($user, 'api')->getJson('/api/v1/documents');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data.data'));
    }

    public function test_user_can_update_document(): void
    {
        $user = User::factory()->create();

        $created = $this->actingAs($user, 'api')
            ->postJson('/api/v1/documents', $this->validPayload());

        $id = $created->json('data.id');

        $this->actingAs($user, 'api')
            ->putJson("/api/v1/documents/{$id}", $this->validPayload(['document_name' => 'Updated Passport']))
            ->assertStatus(200)
            ->assertJsonPath('data.document_name', 'Updated Passport');
    }

    public function test_user_cannot_update_another_users_document(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $created = $this->actingAs($other, 'api')
            ->postJson('/api/v1/documents', $this->validPayload());

        $id = $created->json('data.id');

        $this->actingAs($user, 'api')
            ->putJson("/api/v1/documents/{$id}", $this->validPayload())
            ->assertStatus(404);
    }

    public function test_user_can_delete_document(): void
    {
        $user = User::factory()->create();

        $created = $this->actingAs($user, 'api')
            ->postJson('/api/v1/documents', $this->validPayload());

        $id = $created->json('data.id');

        $this->actingAs($user, 'api')
            ->deleteJson("/api/v1/documents/{$id}")
            ->assertStatus(200);

        $this->assertSoftDeleted('documents', ['id' => $id]);
    }

    public function test_expiring_documents_endpoint_returns_soon_to_expire(): void
    {
        $user = User::factory()->create();

        Document::factory()->create([
            'user_id' => $user->id,
            'document_type' => DocumentType::Passport->value,
            'document_name' => 'Expiring Soon',
            'expiry_date' => now()->addDays(10)->toDateString(),
            'reminder_days' => 30,
        ]);

        Document::factory()->create([
            'user_id' => $user->id,
            'document_type' => DocumentType::Passport->value,
            'document_name' => 'Far Future',
            'expiry_date' => now()->addYears(5)->toDateString(),
            'reminder_days' => 30,
        ]);

        $response = $this->actingAs($user, 'api')->getJson('/api/v1/documents/expiring');

        $response->assertStatus(200);
        $data = $response->json('data.data');
        $this->assertCount(1, $data);
        $this->assertEquals('Expiring Soon', $data[0]['document_name']);
    }
}
