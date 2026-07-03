<?php

namespace Database\Seeders;

use App\Models\OnboardingQuestion;
use App\Models\OnboardingQuestionOption;
use Illuminate\Database\Seeder;

class OnboardingQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        // Q1: Application status — single_choice
        $q1 = OnboardingQuestion::create([
            'question' => 'What is your current application status?',
            'answer_type' => 'single_choice',
            'input_type' => 'single_select',
            'sort_order' => 1,
        ]);

        $alreadyApplied = OnboardingQuestionOption::create([
            'question_id' => $q1->id,
            'option_text' => 'Already applied',
            'sort_order' => 1,
        ]);

        OnboardingQuestionOption::create([
            'question_id' => $q1->id,
            'option_text' => 'Planning to apply',
            'sort_order' => 2,
        ]);

        // Q2: Application date — shown only when Q1 = "Already applied"
        OnboardingQuestion::create([
            'question' => 'When did you apply?',
            'answer_type' => 'date',
            'input_type' => 'id',
            'sort_order' => 2,
            'condition_question_id' => $q1->id,
            'condition_option_id' => $alreadyApplied->id,
        ]);

        // Q3: Country applied from — single country
        OnboardingQuestion::create([
            'question' => 'Which country did you apply from?',
            'answer_type' => 'country',
            'input_type' => 'single_select',
            'sort_order' => 3,
        ]);

        // Q4: Preferred states — multi-select
        OnboardingQuestion::create([
            'question' => 'What are your preferred states?',
            'answer_type' => 'states',
            'input_type' => 'multi_select',
            'sort_order' => 4,
        ]);

        // Q5: Occupation — multi-select
        OnboardingQuestion::create([
            'question' => 'What is your occupation?',
            'answer_type' => 'occupations',
            'input_type' => 'multi_select',
            'sort_order' => 5,
        ]);

        // Q6: Current place — Onshore / Offshore
        $q6 = OnboardingQuestion::create([
            'question' => 'What is your current place?',
            'answer_type' => 'single_choice',
            'input_type' => 'single_select',
            'sort_order' => 6,
        ]);

        OnboardingQuestionOption::create([
            'question_id' => $q6->id,
            'option_text' => 'Onshore',
            'sort_order' => 1,
        ]);

        OnboardingQuestionOption::create([
            'question_id' => $q6->id,
            'option_text' => 'Offshore',
            'sort_order' => 2,
        ]);
    }
}
