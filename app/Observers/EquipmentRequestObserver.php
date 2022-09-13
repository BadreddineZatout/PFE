<?php

namespace App\Observers;

use App\Models\EquipmentRequest;
use App\Models\TakenEquipment;
use Carbon\Carbon;

class EquipmentRequestObserver
{
    /**
     * Handle the EquipmentRequest "updated" event.
     *
     * @param  \App\Models\EquipmentRequest  $equipmentRequest
     * @return void
     */
    public function updated(EquipmentRequest $equipmentRequest)
    {
        if ($equipmentRequest->wasChanged('state')) {
            if ($equipmentRequest->state == 'accepté') {
                TakenEquipment::create([
                    'resident_id' => $equipmentRequest->resident_id,
                    'equipment_id' => $equipmentRequest->equipment_id,
                    'quantity' => $equipmentRequest->quantity,
                    'take_date' => Carbon::now()
                ]);
                return;
            }
            TakenEquipment::where([
                'resident_id' => $equipmentRequest->resident_id,
                'equipment_id' => $equipmentRequest->equipment_id,
            ])->delete();
        }
    }
}
