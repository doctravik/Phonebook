<?php

namespace App\Policies;

use App\User;
use App\Phone;
use Illuminate\Auth\Access\HandlesAuthorization;

class PhonePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if user can update the given phone.
     * 
     * @param  User   $user
     * @param  Phone  $phone
     * @return boolean
     */
    public function update(User $user, Phone $phone)
    {
        return $user->id == $phone->user_id;
    }

    /**
     * Determine if user can delete the given phone.
     * 
     * @param  User   $user
     * @param  Phone  $phone
     * @return boolean
     */
    public function delete(User $user, Phone $phone)
    {
        return $user->id == $phone->user_id;
    }
}
