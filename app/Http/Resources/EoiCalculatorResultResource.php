<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EoiCalculatorResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'selected_visa_subclass' => $this->resource['selected_visa_subclass'],
            'points_breakdown' => [
                'categories' => $this->resource['points_breakdown']['categories'],
                'base_points' => $this->resource['points_breakdown']['base_points'],
                'nomination_points' => $this->resource['points_breakdown']['nomination_points'],
                'final_points' => $this->resource['points_breakdown']['final_points'],
            ],
            'eligibility' => [
                'meets_minimum' => $this->resource['eligibility']['meets_minimum'],
                'minimum_required' => $this->resource['eligibility']['minimum_required'],
                'points_difference' => $this->resource['eligibility']['points_difference'],
                'status' => $this->resource['eligibility']['meets_minimum'] ? 'Eligible' : 'Not Eligible',
                'recommendation' => $this->getRecommendation(),
            ],
            'question_details' => $this->resource['question_details'],
            'summary' => $this->getSummary(),
        ];
    }

    private function getRecommendation(): string
    {
        $finalPoints = $this->resource['points_breakdown']['final_points'];
        $meetsMinimum = $this->resource['eligibility']['meets_minimum'];
        $visaSubclass = $this->resource['selected_visa_subclass'];

        if (! $meetsMinimum) {
            $pointsNeeded = 65 - $finalPoints;

            return "You need {$pointsNeeded} more points to meet the minimum requirement. Consider improving your English score, gaining more work experience, or obtaining additional qualifications.";
        }

        if ($finalPoints >= 85) {
            return "Excellent score! You have a strong chance of receiving an invitation for Subclass {$visaSubclass}.";
        } elseif ($finalPoints >= 75) {
            return 'Good score! You meet the requirements and have a reasonable chance of receiving an invitation.';
        } else {
            return 'You meet the minimum requirements. Consider increasing your points to improve your chances of receiving an invitation.';
        }
    }

    private function getSummary(): array
    {
        $finalPoints = $this->resource['points_breakdown']['final_points'];
        $basePoints = $this->resource['points_breakdown']['base_points'];
        $nominationPoints = $this->resource['points_breakdown']['nomination_points'];
        $visaSubclass = $this->resource['selected_visa_subclass'];

        return [
            'total_points' => $finalPoints,
            'base_points' => $basePoints,
            'bonus_points' => $nominationPoints,
            'visa_type' => $this->getVisaTypeName($visaSubclass),
            'next_steps' => $this->getNextSteps(),
        ];
    }

    private function getVisaTypeName(?string $subclass): string
    {
        return match ($subclass) {
            '189' => 'Skilled Independent visa (Subclass 189)',
            '190' => 'Skilled Nominated visa (Subclass 190)',
            '491' => 'Skilled Work Regional (Provisional) visa (Subclass 491)',
            default => 'Unknown visa subclass',
        };
    }

    private function getNextSteps(): array
    {
        $visaSubclass = $this->resource['selected_visa_subclass'];
        $meetsMinimum = $this->resource['eligibility']['meets_minimum'];

        if (! $meetsMinimum) {
            return [
                'Improve your points score to meet the minimum 65 points requirement',
                'Consider alternative visa pathways',
                'Consult with a migration agent for personalized advice',
            ];
        }

        $steps = ['Submit an Expression of Interest (EOI) through SkillSelect'];

        switch ($visaSubclass) {
            case '190':
                $steps[] = 'Apply for state nomination from an Australian state or territory';
                $steps[] = 'Wait for invitation to apply for the visa';
                break;
            case '491':
                $steps[] = 'Obtain sponsorship from an eligible relative or nomination by a state/territory';
                $steps[] = 'Wait for invitation to apply for the visa';
                break;
            case '189':
                $steps[] = 'Wait for invitation to apply for the visa (no nomination required)';
                break;
        }

        $steps[] = 'Prepare required documents and evidence';
        $steps[] = 'Apply for the visa within 60 days of receiving invitation';

        return $steps;
    }
}
