<?php

namespace Tests\Feature;

use App\Models\EoiAnswer;
use App\Models\EoiQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EoiHistoryTest extends TestCase
{
    use RefreshDatabase;

    private function createEoi(User $user): array
    {
        $question = EoiQuestion::factory()->create(['is_active' => true]);
        $answer = EoiAnswer::factory()->create(['eoi_question_id' => $question->id, 'points' => 10]);
        $responses = [['question_id' => $question->id, 'answer_id' => $answer->id]];

        $created = $this->actingAs($user, 'api')->postJson('/api/v1/eois', [
            'eoi_number' => 'EOI-001',
            'submission_date' => '2026-01-01',
            'responses' => $responses,
        ]);

        return [$created->json('data.id'), $responses];
    }

    public function test_history_is_created_on_update(): void
    {
        $user = User::factory()->create();
        [$id, $responses] = $this->createEoi($user);

        $this->actingAs($user, 'api')->putJson("/api/v1/eois/{$id}", [
            'eoi_number' => 'EOI-UPDATED',
            'submission_date' => '2026-01-01',
            'responses' => $responses,
        ]);

        $this->assertDatabaseHas('eoi_histories', ['eoi_id' => $id, 'change_type' => 'updated']);
    }

    public function test_history_endpoint_returns_changes(): void
    {
        $user = User::factory()->create();
        [$id, $responses] = $this->createEoi($user);

        $this->actingAs($user, 'api')->putJson("/api/v1/eois/{$id}", [
            'eoi_number' => 'EOI-V2',
            'submission_date' => '2026-01-01',
            'responses' => $responses,
        ]);

        $response = $this->actingAs($user, 'api')->getJson("/api/v1/eois/{$id}/history");

        $response->assertStatus(200);
        $history = $response->json('data');
        $this->assertNotEmpty($history);
        $this->assertEquals('updated', $history[0]['change_type']);
    }

    public function test_history_is_empty_before_any_update(): void
    {
        $user = User::factory()->create();
        [$id] = $this->createEoi($user);

        $response = $this->actingAs($user, 'api')->getJson("/api/v1/eois/{$id}/history");

        $response->assertStatus(200);
        $this->assertEmpty($response->json('data'));
    }

    public function test_other_user_cannot_access_history(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        [$id] = $this->createEoi($user);

        $this->actingAs($other, 'api')->getJson("/api/v1/eois/{$id}/history")
            ->assertStatus(404);
    }
}
