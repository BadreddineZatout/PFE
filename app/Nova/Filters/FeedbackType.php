<?php

namespace App\Nova\Filters;

use App\Models\TypeFeedback;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class FeedbackType extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('type_feedback_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Accommodation' => TypeFeedback::ACCOMMODATION_TYPE,
            'Restauration' => TypeFeedback::RESTAURATION_TYPE,
            'Transport' => TypeFeedback::TRANSPORT_TYPE,
        ];
    }
}
