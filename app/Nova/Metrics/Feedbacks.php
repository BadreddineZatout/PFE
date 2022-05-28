<?php

namespace App\Nova\Metrics;

use App\Models\Feedback;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Http\Requests\NovaRequest;

class Feedbacks extends Partition
{
    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $model = Feedback::join('questions', 'question_id', 'questions.id')
            ->where('type_feedback_id', $this->type);

        if ($request->user()->isUniversityDecider())
            $model->join('users', 'user_id', 'users.id')
                ->where('users.establishment_id', $request->user()->establishment_id);
        if ($request->user()->isResidenceDecider())
            $model->join('residents', 'feedback.user_id', 'residents.user_id')
                ->where('residents.establishment_id', $request->user()->establishment_id);

        return $this->count($request, $model, 'is_positive')
            ->label(function ($value) {
                switch ($value) {
                    case 1:
                        return 'Positive';
                    case 0:
                        return 'Negative';
                }
            })->colors([
                1 => '#388E3C',
                0 => '#F51A24',
            ]);
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'feedbacks';
    }
}
