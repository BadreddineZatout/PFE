<?php

namespace App\Nova;

use App\Models\TypeFeedback;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;

class AccommodationFeedback extends Feedback
{
    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Hebergement';

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
        $query->join('questions', 'question_id', 'questions.id')
            ->where('type_feedback_id', TypeFeedback::ACCOMMODATION_TYPE);
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
        return [];
    }


    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
