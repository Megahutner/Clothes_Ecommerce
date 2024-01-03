<?php

namespace App\Policies;

use App\Models\User;
use App\Models\transaction_product;
use Illuminate\Auth\Access\Response;

class TransactionProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, transaction_product $transactionProduct): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, transaction_product $transactionProduct): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, transaction_product $transactionProduct): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, transaction_product $transactionProduct): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, transaction_product $transactionProduct): bool
    {
        //
    }
}
