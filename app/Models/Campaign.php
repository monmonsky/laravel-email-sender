<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'name',
        'contact_list_id',
        'template_id',
        'sender_id',
        'subject',
        'content',
        'status',
        'scheduled_at',
        'sent_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function contactList()
    {
        return $this->belongsTo(ContactList::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function sender()
    {
        return $this->belongsTo(Sender::class);
    }

    public function campaignLogs()
    {
        return $this->hasMany(CampaignLog::class);
    }
}
