<?php

namespace App\Nova\Metrics;

use App\Models\EquipmentRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class TotalDemandeEquipmentNonTraitee extends Value
{
    public $name = 'Not Treated';

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
            return $this->count($request, EquipmentRequest::join('residents', 'resident_id', 'residents.id')
                ->where([
                    'establishment_id' => $request->user()->establishment_id,
                    'equipment_requests.state' => 'non traité'
                ]));
        }
        return $this->count($request, EquipmentRequest::where('state', 'non traité'));
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            30 => __('30 Days'),
            60 => __('60 Days'),
            365 => __('365 Days'),
            'TODAY' => __('Today'),
            'MTD' => __('Month To Date'),
            'QTD' => __('Quarter To Date'),
            'YTD' => __('Year To Date'),
        ];
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'total-demande-equipment-non-traitee';
    }
}
