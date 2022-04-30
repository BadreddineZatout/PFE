<?php

namespace App\Nova\Metrics;

use App\Models\Role;
use App\Models\User;
use App\Nova\Filters\ResidentUniversity;
use App\Nova\Filters\UserUniversity;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Http\Requests\NovaRequest;
use Nemrutco\NovaGlobalFilter\GlobalFilterable;

class ResidentStudents extends Partition
{
    use GlobalFilterable;
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $student_role_id = Role::where('name', 'student')->first()->id;

        // Filter your model with existing filters
        $model = $this->globalFiltered(User::class, [
            UserUniversity::class
        ]);
        $model->where('role_id', $student_role_id);
        if ($request->user()->isUniversityDecider()) $model->where('establishment_id', $request->user()->establishment_id);

        return $this->count($request, $model->select('users.id', 'is_resident'), 'is_resident')
            ->label(function ($value) {
                switch ($value) {
                    case 1:
                        return 'Résidents';
                    case 0:
                        return 'Non Résidents';
                }
            })
            ->colors([
                1 => '#4055B2',
                0 => '#D7E1F3'
            ]);
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
        return 'resident-students';
    }
}
