<?php

namespace App\Policies;

use App\Consts\PolicyConst;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->id === auth()->id();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        return $user->id === auth()->id();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if($user->id === auth()->id() && $user->is_admin) {
            return true;
        } else {
            return abort(PolicyConst::ABORT_STATUS, PolicyConst::ABORT_MESSAGE);
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        if($user->id === auth()->id() && $user->is_admin) {
            return true;
        } else {
            return abort(PolicyConst::ABORT_STATUS, PolicyConst::ABORT_MESSAGE);
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        if($user->id === auth()->id() && $user->is_admin) {
            return true;
        } else {
            return abort(PolicyConst::ABORT_STATUS, PolicyConst::ABORT_MESSAGE);
        }
    }
}
