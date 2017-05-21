<?php

namespace App;

use App\Contact;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable 
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @type array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @type array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at'
    ];

    /**
     * User has many contacts.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * User has many phones.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function phones()
    {
        return $this->hasMany(Phone::class);
    }

    /**
     * @param array $attributes
     * @return Contact
     */
    public function addContact($attributes)
    {
        return $this->contacts()->create($attributes);
    }

    /**
     * @param  Contact $contact
     * @return boolean
     */
    public function hasContact(Contact $contact)
    {
        return $this->contacts()->where('id', $contact->id)->exists();
    }
}
