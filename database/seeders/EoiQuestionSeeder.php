<?php

namespace Database\Seeders;

use App\Models\EoiAnswer;
use App\Models\EoiQuestion;
use App\Models\VisaSubclass;
use Illuminate\Database\Seeder;

class EoiQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $visaSubclasses = VisaSubclass::where('points_tested', true)
            ->where('status', 1)
            ->orderBy('subclass_code')
            ->get();

        $visaSubclassAnswers = [];
        foreach ($visaSubclasses as $index => $visa) {
            $visaSubclassAnswers[] = [
                'answer_text' => "Subclass {$visa->subclass_code} - {$visa->name}",
                'points' => 0,
                'description' => $visa->description." (Minimum points required: {$visa->min_points_required})",
                'order_position' => $index + 1,
            ];
        }

        $questionsData = [
            [
                'category' => 'Visa Selection',
                'question' => 'Which skilled visa subclass are you applying for?',
                'description' => 'Select the skilled visa subclass that you are planning to apply for. This helps determine the specific requirements and points needed.',
                'order_position' => 0,
                'answers' => $visaSubclassAnswers,
            ],
            [
                'category' => 'Age',
                'question' => 'What is your age?',
                'description' => 'Points are awarded based on your age at the time of invitation to apply.',
                'order_position' => 1,
                'answers' => [
                    ['answer_text' => '18-24 years', 'points' => 25, 'description' => 'You must be 18 years of age or over but have not turned 25 at the time of invitation', 'order_position' => 1],
                    ['answer_text' => '25-32 years', 'points' => 30, 'description' => 'You have turned 25 but have not turned 33 at the time of invitation (maximum points)', 'order_position' => 2],
                    ['answer_text' => '33-39 years', 'points' => 25, 'description' => 'You have turned 33 but have not turned 40 at the time of invitation', 'order_position' => 3],
                    ['answer_text' => '40-44 years', 'points' => 15, 'description' => 'You have turned 40 but have not turned 45 at the time of invitation', 'order_position' => 4],
                    ['answer_text' => '45+ years', 'points' => 0, 'description' => 'You have turned 45 or over at the time of invitation (no points awarded)', 'order_position' => 5],
                ],
            ],
            [
                'category' => 'English Language',
                'question' => 'What is your English language proficiency level?',
                'description' => 'Points based on your English test results (IELTS, PTE, TOEFL, etc.)',
                'order_position' => 2,
                'answers' => [
                    ['answer_text' => 'Competent English', 'points' => 0, 'description' => 'IELTS: 6.0 overall with no band less than 6.0 | PTE: 50 overall with no communicative skill less than 50 | TOEFL iBT: 64 overall with minimum scores: Reading 13, Listening 12, Speaking 18, Writing 21', 'order_position' => 1],
                    ['answer_text' => 'Proficient English', 'points' => 10, 'description' => 'IELTS: 7.0 overall with no band less than 7.0 | PTE: 65 overall with no communicative skill less than 65 | TOEFL iBT: 94 overall with minimum scores: Reading 19, Listening 20, Speaking 20, Writing 24', 'order_position' => 2],
                    ['answer_text' => 'Superior English', 'points' => 20, 'description' => 'IELTS: 8.0 overall with no band less than 8.0 | PTE: 79 overall with no communicative skill less than 79 | TOEFL iBT: 110 overall with minimum scores: Reading 24, Listening 24, Speaking 25, Writing 27', 'order_position' => 3],
                ],
            ],
            [
                'category' => 'Education',
                'question' => 'What is your highest level of education?',
                'description' => 'Points based on your highest educational qualification',
                'order_position' => 3,
                'answers' => [
                    ['answer_text' => 'Doctorate degree', 'points' => 20, 'description' => 'A doctorate degree from an Australian educational institution or a doctorate from another educational institution that is of a recognised standard', 'order_position' => 1],
                    ['answer_text' => 'Bachelor degree or Masters degree', 'points' => 15, 'description' => 'A bachelor degree or masters degree from an Australian educational institution or bachelor/masters degree from another institution of recognised standard', 'order_position' => 2],
                    ['answer_text' => 'Diploma or trade qualification', 'points' => 10, 'description' => 'A diploma or trade qualification completed in Australia, or a qualification or award recognised by the relevant assessing authority for your nominated skilled occupation', 'order_position' => 3],
                    ['answer_text' => 'Award or qualification not at AQF Diploma level', 'points' => 5, 'description' => 'Any other qualification or award recognised by the relevant assessing authority for your nominated skilled occupation as being suitable for that occupation', 'order_position' => 4],
                ],
            ],
            [
                'category' => 'Work Experience (Outside Australia)',
                'question' => 'How many years of skilled work experience do you have outside Australia?',
                'description' => 'Points for skilled employment outside Australia in your nominated skilled occupation or a closely related skilled occupation',
                'order_position' => 4,
                'answers' => [
                    ['answer_text' => 'Less than 3 years', 'points' => 0, 'description' => 'Less than 3 years of skilled employment outside Australia in your nominated occupation or closely related occupation', 'order_position' => 1],
                    ['answer_text' => '3-4 years', 'points' => 5, 'description' => 'At least 3 but less than 5 years of skilled employment outside Australia in your nominated occupation or closely related occupation', 'order_position' => 2],
                    ['answer_text' => '5-7 years', 'points' => 10, 'description' => 'At least 5 but less than 8 years of skilled employment outside Australia in your nominated occupation or closely related occupation', 'order_position' => 3],
                    ['answer_text' => '8+ years', 'points' => 15, 'description' => 'At least 8 years of skilled employment outside Australia in your nominated occupation or closely related occupation', 'order_position' => 4],
                ],
            ],
            [
                'category' => 'Work Experience (In Australia)',
                'question' => 'How many years of skilled work experience do you have in Australia?',
                'description' => 'Points for skilled employment in Australia in your nominated skilled occupation or a closely related skilled occupation',
                'order_position' => 5,
                'answers' => [
                    ['answer_text' => 'Less than 1 year', 'points' => 0, 'description' => 'Less than 1 year of skilled employment in Australia in your nominated occupation or closely related occupation', 'order_position' => 1],
                    ['answer_text' => '1-2 years', 'points' => 5, 'description' => 'At least 1 but less than 3 years of skilled employment in Australia in your nominated occupation or closely related occupation', 'order_position' => 2],
                    ['answer_text' => '3-4 years', 'points' => 10, 'description' => 'At least 3 but less than 5 years of skilled employment in Australia in your nominated occupation or closely related occupation', 'order_position' => 3],
                    ['answer_text' => '5-7 years', 'points' => 15, 'description' => 'At least 5 but less than 8 years of skilled employment in Australia in your nominated occupation or closely related occupation', 'order_position' => 4],
                    ['answer_text' => '8+ years', 'points' => 20, 'description' => 'At least 8 years of skilled employment in Australia in your nominated occupation or closely related occupation', 'order_position' => 5],
                ],
            ],
            [
                'category' => 'Educational Qualification in Australia',
                'question' => 'Do you have a degree, diploma or trade qualification from an Australian educational institution?',
                'description' => 'Points for educational qualifications obtained in Australia',
                'order_position' => 6,
                'answers' => [
                    ['answer_text' => 'No Australian qualification', 'points' => 0, 'description' => 'You do not have a degree, diploma or trade qualification from an Australian educational institution', 'order_position' => 1],
                    ['answer_text' => 'Doctorate degree from Australian institution', 'points' => 10, 'description' => 'You have a doctorate degree from an Australian educational institution that meets the Australian study requirement', 'order_position' => 2],
                    ['answer_text' => 'Bachelor degree, Masters degree or Diploma from Australian institution', 'points' => 5, 'description' => 'You have a bachelor degree, masters degree or diploma from an Australian educational institution that meets the Australian study requirement', 'order_position' => 3],
                ],
            ],
            [
                'category' => 'Specialist Education Qualification',
                'question' => 'Do you have a Masters degree by research or Doctorate degree from an Australian educational institution?',
                'description' => 'Points for specialist education qualification in STEM field',
                'order_position' => 7,
                'answers' => [
                    ['answer_text' => 'No specialist qualification', 'points' => 0, 'description' => 'You do not have a Masters degree by research or Doctorate degree from an Australian educational institution that included at least 2 academic years study in a relevant field', 'order_position' => 1],
                    ['answer_text' => 'Masters degree by research or Doctorate in STEM field', 'points' => 10, 'description' => 'You have a Masters degree by research or Doctorate degree from an Australian educational institution that included at least 2 academic years study in Science, Technology, Engineering, Mathematics or ICT field', 'order_position' => 2],
                ],
            ],
            [
                'category' => 'Partner Skills',
                'question' => 'What is your partner\'s situation?',
                'description' => 'Points based on your spouse/de facto partner\'s qualifications and English ability',
                'order_position' => 8,
                'answers' => [
                    ['answer_text' => 'No partner or partner is an Australian citizen/PR', 'points' => 10, 'description' => 'You are single or your spouse/de facto partner is an Australian citizen or Australian permanent resident', 'order_position' => 1],
                    ['answer_text' => 'Partner has competent English and positive skills assessment', 'points' => 10, 'description' => 'Your spouse/de facto partner has competent English and has obtained a positive skills assessment from the relevant assessing authority for their nominated skilled occupation', 'order_position' => 2],
                    ['answer_text' => 'Partner has competent English but no skills assessment', 'points' => 5, 'description' => 'Your spouse/de facto partner has competent English but has not obtained a positive skills assessment', 'order_position' => 3],
                    ['answer_text' => 'Partner without competent English', 'points' => 0, 'description' => 'Your spouse/de facto partner does not have competent English', 'order_position' => 4],
                ],
            ],
            [
                'category' => 'Professional Year',
                'question' => 'Have you completed a Professional Year Program in Australia?',
                'description' => 'Points for completing a Professional Year in accounting, engineering or IT',
                'order_position' => 9,
                'answers' => [
                    ['answer_text' => 'No Professional Year completed', 'points' => 0, 'description' => 'You have not completed a Professional Year Program in Australia', 'order_position' => 1],
                    ['answer_text' => 'Completed Professional Year in Australia', 'points' => 5, 'description' => 'You have completed a Professional Year Program in Australia in accounting, engineering (chemical, civil, electrical, electronic, industrial, mechanical, mining, petroleum or telecommunications), or ICT', 'order_position' => 2],
                ],
            ],
            [
                'category' => 'Community Language',
                'question' => 'Do you have a credentialed community language qualification?',
                'description' => 'Points for NAATI accredited interpreter/translator qualification',
                'order_position' => 10,
                'answers' => [
                    ['answer_text' => 'No community language qualification', 'points' => 0, 'description' => 'You do not hold a recognised qualification in a credentialed community language', 'order_position' => 1],
                    ['answer_text' => 'Credentialed community language qualification', 'points' => 5, 'description' => 'You hold a recognised qualification from NAATI (National Accreditation Authority for Translators and Interpreters) at the paraprofessional level or above in a credentialed community language', 'order_position' => 2],
                ],
            ],
            [
                'category' => 'Regional Study',
                'question' => 'Have you studied in regional Australia?',
                'description' => 'Points for studying in a regional or low population growth metropolitan area',
                'order_position' => 11,
                'answers' => [
                    ['answer_text' => 'Did not study in regional Australia', 'points' => 0, 'description' => 'You did not meet the Australian study requirement while living and studying in an eligible area of regional Australia or a low population growth metropolitan area', 'order_position' => 1],
                    ['answer_text' => 'Studied in regional Australia', 'points' => 5, 'description' => 'You have met the Australian study requirement while living and studying in an eligible area of regional Australia or a low population growth metropolitan area for at least 2 years', 'order_position' => 2],
                ],
            ],
        ];

        foreach ($questionsData as $questionData) {
            $answers = $questionData['answers'];
            unset($questionData['answers']);

            $question = EoiQuestion::updateOrCreate(
                ['category' => $questionData['category']],
                $questionData
            );

            foreach ($answers as $answerData) {
                EoiAnswer::updateOrCreate(
                    [
                        'eoi_question_id' => $question->id,
                        'answer_text' => $answerData['answer_text'],
                    ],
                    array_merge($answerData, ['eoi_question_id' => $question->id])
                );
            }
        }
    }
}
