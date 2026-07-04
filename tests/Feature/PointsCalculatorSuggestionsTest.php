<?php

namespace Tests\Feature;

use App\Models\EoiAnswer;
use App\Models\EoiQuestion;
use App\Models\PointsCalculator;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PointsCalculatorSuggestionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_suggestions(): void
    {
        $response = $this->getJson('/api/v1/points-calculator/suggestions');

        $response->assertStatus(401);
    }

    public function test_returns_404_when_no_submissions_exist(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')
            ->getJson('/api/v1/points-calculator/suggestions');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'No previous submissions found. Please complete the points calculator first.',
            ]);
    }

    public function test_returns_suggestions_when_better_answers_exist(): void
    {
        $user = User::factory()->create();

        $question = EoiQuestion::factory()->create([
            'category' => 'English Language',
            'is_active' => true,
        ]);

        $currentAnswer = EoiAnswer::factory()->create([
            'eoi_question_id' => $question->id,
            'points' => 10,
            'is_active' => true,
        ]);

        EoiAnswer::factory()->create([
            'eoi_question_id' => $question->id,
            'points' => 20,
            'is_active' => true,
        ]);

        PointsCalculator::factory()->create([
            'user_id' => $user->id,
            'eoi_question_id' => $question->id,
            'eoi_answer_id' => $currentAnswer->id,
            'points' => $currentAnswer->points,
        ]);

        $response = $this->actingAs($user, 'api')
            ->getJson('/api/v1/points-calculator/suggestions');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'data' => [
                    'current_status' => [
                        'base_points',
                        'nomination_points',
                        'total_points',
                        'meets_minimum',
                        'status_message',
                    ],
                    'improvement_suggestions',
                    'alternative_visa_options',
                    'priority_recommendations',
                    'analysis' => [
                        'total_potential_gain',
                        'achievable_short_term',
                        'categories_with_room_for_improvement',
                    ],
                ],
            ]);

        $suggestions = $response->json('data.improvement_suggestions');
        $this->assertNotEmpty($suggestions);
        $this->assertEquals(10, $suggestions[0]['potential_gain']);
        $this->assertEquals(10, $suggestions[0]['current_answer']['points']);
        $this->assertEquals(20, $suggestions[0]['suggested_answer']['points']);
    }

    public function test_no_suggestions_when_user_already_has_best_answers(): void
    {
        $user = User::factory()->create();

        $question = EoiQuestion::factory()->create([
            'category' => 'Education',
            'is_active' => true,
        ]);

        $bestAnswer = EoiAnswer::factory()->create([
            'eoi_question_id' => $question->id,
            'points' => 20,
            'is_active' => true,
        ]);

        PointsCalculator::factory()->create([
            'user_id' => $user->id,
            'eoi_question_id' => $question->id,
            'eoi_answer_id' => $bestAnswer->id,
            'points' => $bestAnswer->points,
        ]);

        $response = $this->actingAs($user, 'api')
            ->getJson('/api/v1/points-calculator/suggestions');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $suggestions = $response->json('data.improvement_suggestions');
        $this->assertEmpty($suggestions);
    }

    public function test_visa_selection_category_is_excluded_from_suggestions(): void
    {
        $user = User::factory()->create();

        $question = EoiQuestion::factory()->create([
            'category' => 'Visa Selection',
            'is_active' => true,
        ]);

        $answer = EoiAnswer::factory()->create([
            'eoi_question_id' => $question->id,
            'answer_text' => 'Subclass 189',
            'points' => 0,
            'is_active' => true,
        ]);

        EoiAnswer::factory()->create([
            'eoi_question_id' => $question->id,
            'answer_text' => 'Subclass 491',
            'points' => 15,
            'is_active' => true,
        ]);

        PointsCalculator::factory()->create([
            'user_id' => $user->id,
            'eoi_question_id' => $question->id,
            'eoi_answer_id' => $answer->id,
            'points' => $answer->points,
        ]);

        $response = $this->actingAs($user, 'api')
            ->getJson('/api/v1/points-calculator/suggestions');

        $response->assertStatus(200);

        $suggestions = $response->json('data.improvement_suggestions');
        $this->assertEmpty($suggestions);
    }

    public function test_current_status_reflects_correct_total_points(): void
    {
        $user = User::factory()->create();

        $question = EoiQuestion::factory()->create([
            'category' => 'Work Experience (In Australia)',
            'is_active' => true,
        ]);

        $answer = EoiAnswer::factory()->create([
            'eoi_question_id' => $question->id,
            'points' => 15,
            'is_active' => true,
        ]);

        PointsCalculator::factory()->create([
            'user_id' => $user->id,
            'eoi_question_id' => $question->id,
            'eoi_answer_id' => $answer->id,
            'points' => 15,
        ]);

        $response = $this->actingAs($user, 'api')
            ->getJson('/api/v1/points-calculator/suggestions');

        $response->assertStatus(200);

        $status = $response->json('data.current_status');
        $this->assertEquals(15, $status['base_points']);
        $this->assertEquals(0, $status['nomination_points']);
        $this->assertEquals(15, $status['total_points']);
        $this->assertFalse($status['meets_minimum']);
    }

    public function test_analysis_total_potential_gain_is_sum_of_all_gains(): void
    {
        $user = User::factory()->create();

        foreach (['English Language', 'Education'] as $category) {
            $question = EoiQuestion::factory()->create([
                'category' => $category,
                'is_active' => true,
            ]);

            $currentAnswer = EoiAnswer::factory()->create([
                'eoi_question_id' => $question->id,
                'points' => 5,
                'is_active' => true,
            ]);

            EoiAnswer::factory()->create([
                'eoi_question_id' => $question->id,
                'points' => 15,
                'is_active' => true,
            ]);

            PointsCalculator::factory()->create([
                'user_id' => $user->id,
                'eoi_question_id' => $question->id,
                'eoi_answer_id' => $currentAnswer->id,
                'points' => $currentAnswer->points,
            ]);
        }

        $response = $this->actingAs($user, 'api')
            ->getJson('/api/v1/points-calculator/suggestions');

        $response->assertStatus(200);

        $totalPotentialGain = $response->json('data.analysis.total_potential_gain');
        $this->assertEquals(20, $totalPotentialGain);
    }
}
