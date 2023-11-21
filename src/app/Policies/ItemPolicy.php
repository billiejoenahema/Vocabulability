<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\ResponseMessage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     */
    public function viewAny(User $user): bool
    {
        return $user->id === auth()->id();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     */
    public function view(User $user): bool
    {
        return $user->id === auth()->id();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     */
    public function create(User $user): bool
    {
        if ($user->id === auth()->id() && $user->is_admin) {
            return true;
        }
        return abort(403, ResponseMessage::POLICY_ABORT->value);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     */
    public function update(User $user): bool
    {
        if ($user->id === auth()->id() && $user->is_admin) {
            return true;
        }
        return abort(403, ResponseMessage::POLICY_ABORT->value);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     */
    public function delete(User $user): bool
    {
        if ($user->id === auth()->id() && $user->is_admin) {
            return true;
        }
        return abort(403, ResponseMessage::POLICY_ABORT->value);
    }
}
