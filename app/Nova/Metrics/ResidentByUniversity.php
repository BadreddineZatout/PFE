<?php

namespace App\Nova\Metrics;

use App\Models\Role;
use App\Models\User;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Http\Requests\NovaRequest;

class ResidentByUniversity extends Partition
{
    public $refreshWhenActionRuns = true;

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $student_role_id = Role::where('name', 'Student')->first()->id;
        $model = User::where('role_id', $student_role_id)
            ->join('residents', 'users.id', 'residents.user_id')
            ->join('establishments', 'users.establishment_id', 'establishments.id')
            ->where('residents.establishment_id', $request->user()->establishment_id);

        return $this->count($request, $model, 'establishments.name_fr');
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
        return 'resident-by-university';
    }
}
