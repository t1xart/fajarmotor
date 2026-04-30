<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;

class SalePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('sales.view');
    }

    public function view(User $user, Sale $sale): bool
    {
        return $user->can('sales.view');
    }

    public function create(User $user): bool
    {
        return $user->can('sales.create');
    }

    public function processReturn(User $user, Sale $sale): bool
    {
        return $user->can('sales.return');
    }
}
