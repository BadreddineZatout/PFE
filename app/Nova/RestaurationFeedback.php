<?php

namespace App\Nova;

use App\Models\TypeFeedback;
use Illuminate\Http\Request;
use App\Nova\Metrics\Feedbacks;
use App\Nova\Metrics\FeedbackTotal;
use App\Services\FeedbackBarChartService;
use App\Nova\Metrics\NegativeFeedbackTotal;
use App\Nova\Metrics\PositiveFeedbackTotal;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Lenses\RestaurationNegativeFeedbacks;
use App\Nova\Lenses\RestaurationPositiveFeedbacks;

class RestaurationFeedback extends Feedback
{
    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Restauration';

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
        $query->where('type_feedback_id', TypeFeedback::RESTAURATION_TYPE);
        if ($request->user()->isUniversityDecider())
            $query->join('users', 'user_id', 'users.id')
                ->where('users.establishment_id', $request->user()->establishment_id);
        if ($request->user()->isResidenceDecider())
            $query->join('residents', 'feedback.user_id', 'residents.user_id')
                ->where('residents.establishment_id', $request->user()->establishment_id);
        return $query->select('feedback.*');
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
            (new FeedbackTotal(TypeFeedback::RESTAURATION_TYPE))->width('1/2'),
            (new Feedbacks(TypeFeedback::RESTAURATION_TYPE))->width('1/2'),
            (new PositiveFeedbackTotal(TypeFeedback::RESTAURATION_TYPE))->width('1/2'),
            (new NegativeFeedbackTotal(TypeFeedback::RESTAURATION_TYPE))->width('1/2'),
            FeedbackBarChartService::getBarChart($request->user(), TypeFeedback::RESTAURATION_TYPE)
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
            new RestaurationPositiveFeedbacks(),
            new RestaurationNegativeFeedbacks()
        ];
    }
}
