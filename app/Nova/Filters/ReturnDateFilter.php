<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Nova\Filters\DateFilter;

class ReturnDateFilter extends DateFilter
{
    public $name = 'Retrun Date';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        $value = Carbon::parse($value)->format('Y-m-d');

        return $query->where('return_date', $value);
    }
}
