<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\MachineOperation;

class MachineOperationObserver
{
    /**
     * Handle the MachineOperation "created" event.
     */
    public function created(MachineOperation $machineOperation)
    {
        ActivityLog::create([
            'user_id' => $machineOperation->operator_id,
            'activity_type' => 'Add Machine Operation',
            'activity_details' => 'Added machine operation for design ID: ' . $machineOperation->design_id
                . ', Quantity: ' . $machineOperation->quantity
                . ', Comments: ' . $machineOperation->comments,
        ]);
    }

    /**
     * Handle the MachineOperation "updated" event.
     */
    public function updated(MachineOperation $machineOperation): void
    {
        //
    }

    /**
     * Handle the MachineOperation "deleted" event.
     */
    public function deleted(MachineOperation $machineOperation): void
    {
        //
    }

    /**
     * Handle the MachineOperation "restored" event.
     */
    public function restored(MachineOperation $machineOperation): void
    {
        //
    }

    /**
     * Handle the MachineOperation "force deleted" event.
     */
    public function forceDeleted(MachineOperation $machineOperation): void
    {
        //
    }
}
