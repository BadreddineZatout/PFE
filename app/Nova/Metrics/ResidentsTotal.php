<?php

namespace App\Nova\Metrics;

use App\Models\Resident;
use App\Nova\Filters\ResidentResidence;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Nemrutco\NovaGlobalFilter\GlobalFilterable;

class ResidentsTotal extends Value
{
    use GlobalFilterable;

    public $refreshWhenActionRuns = true;

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        if ($request->user()->isResidenceDecider() || $request->user()->isAgentHebergement()) {
            return $this->count($request, Resident::where('establishment_id', $request->user()->establishment_id));
        }
        if ($request->user()->isUniversityDecider()) {
            return $this->count(
                $request,
                Resident::join('users', 'users.id', 'user_id')
                    ->where('users.establishment_id', $request->user()->establishment_id)
                    ->whereIn('residents.establishment_id', $request->user()->establishment->establishments->pluck('id'))
            );
        }
        // Filter your model with existing filters
        $model = $this->globalFiltered(Resident::class, [
            ResidentResidence::class
        ]);
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
            'ALL' => 'All Time',
            'YTD' => 'Year To Date',
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
        return 'residents-total';
    }
}
