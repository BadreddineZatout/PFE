<?php

namespace App\Nova\Metrics;

use App\Models\Role;
use App\Models\User;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Http\Requests\NovaRequest;

class WorkersTotal extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        if ($request->user()->isAdmin() || $request->user()->isMinister()) {
            $model = User::join('roles', 'role_id', 'roles.id')->whereNotIn('roles.id', [Role::STUDENT, Role::ADMIN, Role::MINISTER]);
            return $this->count($request, $model, 'roles.name');
        }
        $model = User::join('roles', 'role_id', 'roles.id')
            ->where('users.establishment_id', $request->user()->establishment_id)
            ->whereNotIn('roles.id', [Role::STUDENT, Role::ADMIN, Role::MINISTER, Role::DECIDER]);
        return $this->count($request, $model, 'roles.name');
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
        return 'workers-total';
    }
}
