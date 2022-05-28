<?php

namespace App\Nova\Metrics;

use App\Models\Feedback;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Http\Requests\NovaRequest;

class NegativeFeedbackTotal extends Value
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
            ->where([
                'type_feedback_id' => $this->type,
                'is_positive' => false
            ]);

        if ($request->user()->isUniversityDecider())
            $model->join('users', 'user_id', 'users.id')
                ->where('users.establishment_id', $request->user()->establishment_id);
        if ($request->user()->isResidenceDecider())
            $model->join('residents', 'feedback.user_id', 'residents.user_id')
                ->where('residents.establishment_id', $request->user()->establishment_id);

        return $this->count($request, $model);
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            'TODAY' => __('Today'),
            30 => __('30 Days'),
            60 => __('60 Days'),
            365 => __('365 Days'),
            'MTD' => __('Month To Date'),
            'QTD' => __('Quarter To Date'),
            'YTD' => __('Year To Date'),
        ];
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
        return 'negative-feedback-total';
    }
}
