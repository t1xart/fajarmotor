<?php

namespace App\Policies;

use App\Models\WorkOrder;
use App\Models\User;

class WorkOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('wo.view');
    }

    public function view(User $user, WorkOrder $workOrder): bool
    {
        return $user->can('wo.view');
    }

    public function create(User $user): bool
    {
        return $user->can('wo.create');
    }

    public function update(User $user, WorkOrder $workOrder): bool
    {
        return $user->can('wo.update');
    }

    public function delete(User $user, WorkOrder $workOrder): bool
    {
        return $user->can('admin');
    }
}
