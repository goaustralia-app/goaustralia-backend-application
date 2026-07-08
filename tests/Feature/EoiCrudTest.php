<?php

namespace Tests\Feature;

use App\Models\EoiAnswer;
use App\Models\EoiQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EoiCrudTest extends TestCase
{
    use RefreshDatabase;

    private function makeResponses(): array
    {
        $question = EoiQuestion::factory()->create(['is_active' => true]);
        $answer = EoiAnswer::factory()->create(['eoi_question_id' => $question->id, 'points' => 10]);

        return [['question_id' => $question->id, 'answer_id' => $answer->id]];
    }

    public function test_unauthenticated_cannot_access_eois(): void
    {
        $this->getJson('/api/v1/eois')->assertStatus(401);
    }

    public function test_user_can_create_eoi(): void
    {
        $user = User::factory()->create();
        $responses = $this->makeResponses();

        $response = $this->actingAs($user, 'api')->postJson('/api/v1/eois', [
            'eoi_number' => 'EOI-001',
            'submission_date' => '2026-01-01',
            'expiry_date' => '2028-01-01',
            'state_nomination' => 'NSW',
            'notes' => 'Test notes',
            'responses' => $responses,
        ]);

        $response->assertStatus(201)
            ->assertJson(['status' => true])
            ->assertJsonPath('data.eoi_number', 'EOI-001')
            ->assertJsonPath('data.expiry_date', '2028-01-01')
            ->assertJsonPath('data.state_nomination', 'NSW')
            ->assertJsonPath('data.notes', 'Test notes');
    }

    public function test_expiry_date_must_be_after_submission_date(): void
    {
        $user = User::factory()->create();
        $responses = $this->makeResponses();

        $response = $this->actingAs($user, 'api')->postJson('/api/v1/eois', [
            'eoi_number' => 'EOI-001',
            'submission_date' => '2026-06-01',
            'expiry_date' => '2026-01-01',
            'responses' => $responses,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['expiry_date']);
    }

    public function test_user_can_list_own_eois_paginated(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $responses = $this->makeResponses();

        $this->actingAs($user, 'api')->postJson('/api/v1/eois', [
            'eoi_number' => 'EOI-001',
            'submission_date' => '2026-01-01',
            'responses' => $responses,
        ]);
        $this->actingAs($other, 'api')->postJson('/api/v1/eois', [
            'eoi_number' => 'EOI-OTHER',
            'submission_date' => '2026-01-01',
            'responses' => $responses,
        ]);

        $response = $this->actingAs($user, 'api')->getJson('/api/v1/eois');

        $response->assertStatus(200);
        $data = $response->json('data.data');
        $this->assertCount(1, $data);
        $this->assertEquals('EOI-001', $data[0]['eoi_number']);
    }

    public function test_user_can_show_own_eoi(): void
    {
        $user = User::factory()->create();
        $responses = $this->makeResponses();

        $created = $this->actingAs($user, 'api')->postJson('/api/v1/eois', [
            'eoi_number' => 'EOI-001',
            'submission_date' => '2026-01-01',
            'responses' => $responses,
        ]);

        $id = $created->json('data.id');

        $this->actingAs($user, 'api')->getJson("/api/v1/eois/{$id}")
            ->assertStatus(200)
            ->assertJsonPath('data.id', $id);
    }

    public function test_user_cannot_view_another_users_eoi(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $responses = $this->makeResponses();

        $created = $this->actingAs($other, 'api')->postJson('/api/v1/eois', [
            'eoi_number' => 'EOI-OTHER',
            'submission_date' => '2026-01-01',
            'responses' => $responses,
        ]);

        $id = $created->json('data.id');

        $this->actingAs($user, 'api')->getJson("/api/v1/eois/{$id}")
            ->assertStatus(404);
    }

    public function test_user_can_update_eoi(): void
    {
        $user = User::factory()->create();
        $responses = $this->makeResponses();

        $created = $this->actingAs($user, 'api')->postJson('/api/v1/eois', [
            'eoi_number' => 'EOI-001',
            'submission_date' => '2026-01-01',
            'responses' => $responses,
        ]);

        $id = $created->json('data.id');

        $this->actingAs($user, 'api')->putJson("/api/v1/eois/{$id}", [
            'eoi_number' => 'EOI-UPDATED',
            'submission_date' => '2026-01-01',
            'notes' => 'Updated notes',
            'responses' => $responses,
        ])->assertStatus(200)
            ->assertJsonPath('data.eoi_number', 'EOI-UPDATED')
            ->assertJsonPath('data.notes', 'Updated notes');
    }

    public function test_user_can_delete_eoi(): void
    {
        $user = User::factory()->create();
        $responses = $this->makeResponses();

        $created = $this->actingAs($user, 'api')->postJson('/api/v1/eois', [
            'eoi_number' => 'EOI-001',
            'submission_date' => '2026-01-01',
            'responses' => $responses,
        ]);

        $id = $created->json('data.id');

        $this->actingAs($user, 'api')->deleteJson("/api/v1/eois/{$id}")
            ->assertStatus(200)
            ->assertJson(['status' => true]);

        $this->actingAs($user, 'api')->getJson("/api/v1/eois/{$id}")
            ->assertStatus(404);
    }

    public function test_delete_returns_404_for_other_users_eoi(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $responses = $this->makeResponses();

        $created = $this->actingAs($other, 'api')->postJson('/api/v1/eois', [
            'eoi_number' => 'EOI-OTHER',
            'submission_date' => '2026-01-01',
            'responses' => $responses,
        ]);

        $id = $created->json('data.id');

        $this->actingAs($user, 'api')->deleteJson("/api/v1/eois/{$id}")
            ->assertStatus(404);
    }
}
