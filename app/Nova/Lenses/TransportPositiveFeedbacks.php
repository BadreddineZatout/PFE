<?php

namespace App\Nova\Lenses;

use Laravel\Nova\Fields\ID;
use App\Models\TypeFeedback;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Lenses\Lens;
use App\Nova\Filters\FeedbackDate;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Metrics\PositiveFeedbackTotal;
use Laravel\Nova\Http\Requests\LensRequest;

class TransportPositiveFeedbacks extends Lens
{
    public $name = 'Positive Feedback';
    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        if ($request->user()->isUniversityDecider()) {
            return $request->withOrdering($request->withFilters(
                $query->join('users', 'user_id', 'users.id')
                    ->where([
                        'type_feedback_id' => TypeFeedback::TRANSPORT_TYPE,
                        'is_positive' => true,
                        'users.establishment_id' => $request->user()->establishment_id
                    ])
                    ->select('feedback.*')
            ));
        }
        if ($request->user()->isResidenceDecider()) {
            return $request->withOrdering($request->withFilters(
                $query->join('residents', 'feedback.user_id', 'residents.user_id')
                    ->where([
                        'type_feedback_id' => TypeFeedback::TRANSPORT_TYPE,
                        'is_positive' => true,
                        'residents.establishment_id' => $request->user()->establishment_id
                    ])
                    ->select('feedback.*')
            ));
        }
        return $request->withOrdering($request->withFilters(
            $query->where([
                'type_feedback_id' => TypeFeedback::TRANSPORT_TYPE,
                'is_positive' => true,
            ])
                ->select('feedback.*')
        ));
    }

    /**
     * Get the fields available to the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make('user'),
            BelongsTo::make('question'),
            Text::make('description')
                ->rules('required', 'min:1', 'max:255'),
            Date::make('date')
                ->required(),
        ];
    }

    /**
     * Get the cards available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
            new PositiveFeedbackTotal(TypeFeedback::TRANSPORT_TYPE),
        ];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new FeedbackDate,
        ];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'transport-positive-feedbacks';
    }
}
