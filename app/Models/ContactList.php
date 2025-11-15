<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactList extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }
}
