<?php

namespace App;

use App\Phone;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Scope users with the name like the given one.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $name
     * @return \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeFindLikeName($query, $name)
    {
        return $query->where('name', 'ilike', "%$name%");
    }

    /**
     * User has many phone numbers.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function phones()
    {
        return $this->hasMany(Phone::class);
    }

    /**
     * @param string $phoneNumber
     * @return Phone
     */
    public function addPhone($phoneNumber)
    {
        return $this->phones()->create([
            'number' => $phoneNumber
        ]);
    }
}
