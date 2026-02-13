<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EoiSuggestionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'current_status' => [
                'base_points' => $this->resource['current_status']['base_points'],
                'nomination_points' => $this->resource['current_status']['nomination_points'],
                'total_points' => $this->resource['current_status']['total_points'],
                'visa_subclass' => $this->resource['current_status']['visa_subclass'],
                'meets_minimum' => $this->resource['current_status']['meets_minimum'],
                'status_message' => $this->getStatusMessage(),
            ],
            'improvement_suggestions' => $this->formatSuggestions($this->resource['improvement_suggestions']),
            'alternative_visa_options' => $this->resource['alternative_visa_options'],
            'priority_recommendations' => [
                'immediate_actions' => $this->resource['priority_recommendations']['immediate_actions'] ?? [],
                'medium_term_goals' => $this->resource['priority_recommendations']['medium_term_goals'] ?? [],
                'quick_wins' => $this->resource['priority_recommendations']['quick_wins'] ?? [],
                'summary' => $this->getPrioritySummary(),
            ],
            'analysis' => [
                'total_potential_gain' => $this->calculateTotalPotentialGain(),
                'achievable_short_term' => $this->getShortTermGains(),
                'categories_with_room_for_improvement' => $this->getCategoriesForImprovement(),
            ],
        ];
    }

    private function getStatusMessage(): string
    {
        $totalPoints = $this->resource['current_status']['total_points'];
        $meetsMinimum = $this->resource['current_status']['meets_minimum'];

        if (!$meetsMinimum) {
            $pointsNeeded = 65 - $totalPoints;
            return "You need {$pointsNeeded} more points to meet the minimum requirement.";
        }

        if ($totalPoints >= 85) {
            return "Excellent score! You have a highly competitive points total.";
        } elseif ($totalPoints >= 75) {
            return "Good score! You meet requirements with a reasonable competitive advantage.";
        } else {
            return "You meet the minimum requirements. Consider improving your score for better chances.";
        }
    }

    private function formatSuggestions(array $suggestions): array
    {
        return collect($suggestions)->map(function ($suggestion) {
            return [
                'category' => $suggestion['category'],
                'question' => $suggestion['question'],
                'current_answer' => $suggestion['current_answer'],
                'suggested_answer' => $suggestion['suggested_answer'],
                'potential_gain' => $suggestion['potential_gain'],
                'priority_score' => $suggestion['priority'],
                'difficulty_level' => $this->getDifficultyLevel($suggestion['category']),
                'estimated_timeframe' => $this->getEstimatedTimeframe($suggestion['category']),
                'actionable_steps' => $suggestion['actionable_steps'],
            ];
        })->toArray();
    }

    private function getDifficultyLevel(string $category): string
    {
        return match ($category) {
            'Age' => 'Impossible',
            'English Language' => 'Medium',
            'Education' => 'Hard',
            'Work Experience (In Australia)' => 'Medium',
            'Work Experience (Outside Australia)' => 'Easy',
            'Professional Year' => 'Medium',
            'Educational Qualification in Australia' => 'Hard',
            'Specialist Education Qualification' => 'Hard',
            'Partner Skills' => 'Variable',
            'Community Language' => 'Medium',
            'Regional Study' => 'Hard',
            default => 'Unknown',
        };
    }

    private function getEstimatedTimeframe(string $category): string
    {
        return match ($category) {
            'Age' => 'N/A',
            'English Language' => '3-6 months',
            'Education' => '2-4 years',
            'Work Experience (In Australia)' => '1-8 years',
            'Work Experience (Outside Australia)' => 'Immediate (documentation)',
            'Professional Year' => '12 months',
            'Educational Qualification in Australia' => '2-4 years',
            'Specialist Education Qualification' => '2-4 years',
            'Partner Skills' => 'Variable',
            'Community Language' => '6-12 months',
            'Regional Study' => '2-4 years',
            default => 'Variable',
        };
    }

    private function getPrioritySummary(): string
    {
        $suggestions = $this->resource['improvement_suggestions'];
        
        if (empty($suggestions)) {
            return "You're already at maximum points in all categories!";
        }

        $topSuggestion = $suggestions[0] ?? null;
        if (!$topSuggestion) {
            return "No specific improvements identified.";
        }

        $category = $topSuggestion['category'];
        $gain = $topSuggestion['potential_gain'];
        
        return "Focus on {$category} for the highest impact ({$gain} points potential gain).";
    }

    private function calculateTotalPotentialGain(): int
    {
        return collect($this->resource['improvement_suggestions'])
            ->sum('potential_gain');
    }

    private function getShortTermGains(): array
    {
        $shortTermCategories = ['English Language', 'Work Experience (Outside Australia)', 'Community Language'];
        
        return collect($this->resource['improvement_suggestions'])
            ->whereIn('category', $shortTermCategories)
            ->map(function ($suggestion) {
                return [
                    'category' => $suggestion['category'],
                    'potential_gain' => $suggestion['potential_gain'],
                    'timeframe' => $this->getEstimatedTimeframe($suggestion['category']),
                ];
            })
            ->values()
            ->toArray();
    }

    private function getCategoriesForImprovement(): array
    {
        return collect($this->resource['improvement_suggestions'])
            ->groupBy('category')
            ->map(function ($suggestions, $category) {
                $suggestion = $suggestions->first();
                return [
                    'category' => $category,
                    'current_points' => $suggestion['current_answer']['points'],
                    'max_possible_points' => $suggestion['suggested_answer']['points'],
                    'potential_gain' => $suggestion['potential_gain'],
                ];
            })
            ->values()
            ->toArray();
    }
}
