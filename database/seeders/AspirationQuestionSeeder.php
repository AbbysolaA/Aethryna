<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Seeder;

/**
 * The aspiration question: the final assessment question, and the tie-breaker
 * the five-track remap leans on.
 *
 * The clusters weigh what somebody has been near; this asks what they want.
 * Where the two disagree, the want wins (A4 point 3 of the five-track master
 * handoff): a person with old experience in one field and energy for another
 * should see the aspiration track first.
 *
 * Its own seeder rather than a line in QuestionsSeeder, because that seeder
 * creates unconditionally and re-running it on a live database would
 * duplicate all fifteen existing questions. This one is updateOrCreate
 * throughout and safe to run on production as many times as needed.
 *
 * The answers carry no clusters on purpose: this question steers the
 * recommendation directly and must not double-count into the scores.
 */
class AspirationQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $question = Question::updateOrCreate(
            ['question_number' => 16],
            [
                'section'       => 'E',
                'question_text' => 'Which of these would you be most excited to spend the next six months learning?',
                'order'         => 16,
                'is_active'     => true,
            ]
        );

        $tracks = [
            'A' => 'Project Management and Delivery',
            'B' => 'Product Management',
            'C' => 'Data and AI Analytics',
            'D' => 'Product Design and Marketing',
            'E' => 'Software Development',
        ];

        foreach (array_values($tracks) as $index => $name) {
            Answer::updateOrCreate(
                [
                    'question_id'  => $question->id,
                    'option_label' => array_keys($tracks)[$index],
                ],
                [
                    'answer_text' => $name,
                    'clusters'    => [],
                    'order'       => $index + 1,
                    'is_active'   => true,
                ]
            );
        }

        $this->command?->info('Aspiration question seeded as question 16 with the five founding tracks.');
    }
}
