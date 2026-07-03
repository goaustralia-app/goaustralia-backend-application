<?php

namespace App\Domains\Onboarding\Repositories;

use App\Models\OnboardingQuestion;
use App\Models\User;
use App\Models\UserOnboardingAnswer;
use App\Models\UserOnboardingOccupationAnswer;
use App\Models\UserOnboardingStateAnswer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OnboardingRepository implements OnboardingRepositoryInterface
{
    public function getAllQuestions(): Collection
    {
        return OnboardingQuestion::with('options')
            ->orderBy('sort_order')
            ->get();
    }

    public function submitAnswers(User $user, array $answers): void
    {
        $this->persistAnswers($user, $answers);
        $user->update(['is_onboard' => true]);
    }

    public function getUserAnswers(User $user): array
    {
        $questions = OnboardingQuestion::query()->orderBy('sort_order')->get()->keyBy('id');

        $generalAnswers = UserOnboardingAnswer::query()
            ->with(['option', 'country'])
            ->where('user_id', $user->id)
            ->get()
            ->keyBy('question_id');

        $stateIds = UserOnboardingStateAnswer::query()
            ->where('user_id', $user->id)
            ->pluck('state_id')
            ->all();

        $occupationIds = UserOnboardingOccupationAnswer::query()
            ->where('user_id', $user->id)
            ->pluck('occupation_list_id')
            ->all();

        $answers = [];

        foreach ($questions as $question) {
            $entry = [
                'question_id' => $question->id,
                'question' => $question->question,
                'answer_type' => $question->answer_type,
                'input_type' => $question->input_type,
            ];

            $generalAnswer = $generalAnswers->get($question->id);

            if (in_array($question->answer_type, ['single_choice', 'multiple_choice'])) {
                $entry['option_id'] = $generalAnswer?->option_id;
                $entry['option_text'] = $generalAnswer?->option?->option_text;
            } elseif ($question->answer_type === 'date') {
                $entry['answer_date'] = $generalAnswer?->answer_date?->toDateString();
            } elseif ($question->answer_type === 'country') {
                $entry['country_id'] = $generalAnswer?->country_id;
                $entry['country_name'] = $generalAnswer?->country?->name;
            } elseif ($question->answer_type === 'states') {
                $entry['state_ids'] = $stateIds;
            } elseif ($question->answer_type === 'occupations') {
                $entry['occupation_list_ids'] = $occupationIds;
            }

            $answers[] = $entry;
        }

        return $answers;
    }

    public function updateAnswers(User $user, array $answers): void
    {
        $this->persistAnswers($user, $answers);
    }

    private function persistAnswers(User $user, array $answers): void
    {
        $questionIds = array_column($answers, 'question_id');
        $questions = OnboardingQuestion::query()
            ->whereIn('id', $questionIds)
            ->get()
            ->keyBy('id');

        DB::transaction(function () use ($user, $answers, $questions) {
            foreach ($answers as $answer) {
                $question = $questions->get($answer['question_id']);

                match ($question->answer_type) {
                    'single_choice', 'multiple_choice' => UserOnboardingAnswer::updateOrCreate(
                        ['user_id' => $user->id, 'question_id' => $question->id],
                        ['option_id' => $answer['option_id'] ?? null, 'answer_date' => null, 'country_id' => null]
                    ),
                    'date' => UserOnboardingAnswer::updateOrCreate(
                        ['user_id' => $user->id, 'question_id' => $question->id],
                        ['answer_date' => $answer['answer_date'] ?? null, 'option_id' => null, 'country_id' => null]
                    ),
                    'country' => UserOnboardingAnswer::updateOrCreate(
                        ['user_id' => $user->id, 'question_id' => $question->id],
                        ['country_id' => $answer['country_id'] ?? null, 'option_id' => null, 'answer_date' => null]
                    ),
                    'states' => $this->syncStateAnswers($user, $answer['state_ids'] ?? []),
                    'occupations' => $this->syncOccupationAnswers($user, $answer['occupation_list_ids'] ?? []),
                    default => null,
                };
            }
        });
    }

    private function syncStateAnswers(User $user, array $stateIds): void
    {
        UserOnboardingStateAnswer::query()->where('user_id', $user->id)->delete();

        $rows = array_map(fn ($id) => ['user_id' => $user->id, 'state_id' => $id, 'created_at' => now(), 'updated_at' => now()], $stateIds);

        if (! empty($rows)) {
            UserOnboardingStateAnswer::insert($rows);
        }
    }

    private function syncOccupationAnswers(User $user, array $occupationListIds): void
    {
        UserOnboardingOccupationAnswer::query()->where('user_id', $user->id)->delete();

        $rows = array_map(fn ($id) => ['user_id' => $user->id, 'occupation_list_id' => $id, 'created_at' => now(), 'updated_at' => now()], $occupationListIds);

        if (! empty($rows)) {
            UserOnboardingOccupationAnswer::insert($rows);
        }
    }
}
