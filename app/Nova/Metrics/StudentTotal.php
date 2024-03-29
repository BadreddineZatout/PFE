<?php

namespace App\Nova\Metrics;

use App\Models\Role;
use App\Models\User;
use App\Models\Resident;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Http\Requests\NovaRequest;

class StudentTotal extends Value
{
    public $name = 'Students';
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        if ($request->user()->isUniversityDecider() || $request->user()->isUniversityAgentRestauration() || $request->user()->isUniversityAgentIncident())
            return $this->count($request, User::where([
                'role_id' => Role::STUDENT,
                'establishment_id' => $request->user()->establishment_id
            ]));
        if ($request->user()->isResidenceDecider() || $request->user()->isAgentHebergement() || $request->user()->isResidenceAgentRestauration() || $request->user()->isResidenceAgentIncident())
            return $this->count($request, Resident::where([
                'establishment_id' => $request->user()->establishment_id
            ]));
        return $this->count($request, User::where('role_id', Role::STUDENT));
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
            365 => __('This Year'),
        ];
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'student-total';
    }
}
