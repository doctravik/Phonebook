<?php

namespace App\Policies;

use App\User;
use App\Contact;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can update contact.
     *
     * @param  User  $user
     * @param  Contact $contact
     * @return bool
     */
    public function update(User $user, Contact $contact)
    {
        return $user->id == $contact->user_id;        
    }

    /**
     * Determine if the user can delete contact.
     *
     * @param  User  $user
     * @param  Contact $contact
     * @return bool
     */
    public function delete(User $user, Contact $contact)
    {
        return $user->id == $contact->user_id;        
    }
}
