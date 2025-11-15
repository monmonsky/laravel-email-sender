<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'contact_list_id',
        'status',
    ];

    public function contactList()
    {
        return $this->belongsTo(ContactList::class);
    }

    public function campaignLogs()
    {
        return $this->hasMany(CampaignLog::class);
    }
}
