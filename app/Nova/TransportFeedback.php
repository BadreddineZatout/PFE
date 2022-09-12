<?php

namespace App\Nova;

use App\Models\TypeFeedback;
use Illuminate\Http\Request;
use App\Nova\Metrics\Feedbacks;
use App\Nova\Metrics\FeedbackTotal;
use App\Nova\Metrics\NegativeFeedbackTotal;
use App\Nova\Metrics\PositiveFeedbackTotal;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Lenses\TransportNegativeFeedbacks;
use App\Nova\Lenses\TransportPositiveFeedbacks;
use App\Services\FeedbackBarChartService;

class TransportFeedback extends Feedback
{
    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Transport';

    /**
     * Determine if this resource is available for navigation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function availableForNavigation(Request $request)
    {
        return $request->user()->isAdmin() || $request->user()->isMinister() || $request->user()->isDecider();
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->where('type_feedback_id', TypeFeedback::TRANSPORT_TYPE);
        if ($request->user()->isUniversityDecider())
            $query->join('users', 'user_id', 'users.id')
                ->where('users.establishment_id', $request->user()->establishment_id);
        if ($request->user()->isResidenceDecider())
            $query->join('residents', 'feedback.user_id', 'residents.user_id')
                ->where('residents.establishment_id', $request->user()->establishment_id);
        return $query->select('feedback.*');
    }

    /**
     * Build a "relatable" query for residences.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Laravel\Nova\Fields\Field  $field
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableQuestions(NovaRequest $request, $query)
    {
        return $query->where('type_feedback_id', TypeFeedback::TRANSPORT_TYPE);
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
            (new FeedbackTotal(TypeFeedback::TRANSPORT_TYPE))->width('1/2'),
            (new Feedbacks(TypeFeedback::TRANSPORT_TYPE))->width('1/2'),
            (new PositiveFeedbackTotal(TypeFeedback::TRANSPORT_TYPE))->width('1/2'),
            (new NegativeFeedbackTotal(TypeFeedback::TRANSPORT_TYPE))->width('1/2'),
            FeedbackBarChartService::getBarChart($request->user(), TypeFeedback::TRANSPORT_TYPE)
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [
            new TransportPositiveFeedbacks(),
            new TransportNegativeFeedbacks()
        ];
    }
}
