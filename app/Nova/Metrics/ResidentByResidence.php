<?php

namespace App\Nova\Metrics;

use App\Models\Role;
use App\Models\User;
use Laravel\Nova\Metrics\Partition;
use App\Nova\Filters\UserUniversity;
use Laravel\Nova\Http\Requests\NovaRequest;
use Nemrutco\NovaGlobalFilter\GlobalFilterable;

class ResidentByResidence extends Partition
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
        $model = $this->globalFiltered(User::class, [
            UserUniversity::class
        ]);

        $model->where('role_id', Role::STUDENT)
            ->join('residents', 'users.id', 'residents.user_id')
            ->join('establishments', 'residents.establishment_id', 'establishments.id');

        if ($request->user()->isUniversityDecider()) {
            $model->where('users.establishment_id', $request->user()->establishment_id);
        }

        return $this->count($request, $model, 'establishments.name_fr');
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'resident-by-residence';
    }
}
