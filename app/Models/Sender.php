<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sender extends Model
{
    protected $fillable = [
        'name',
        'from_email',
        'from_name',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'is_default',
    ];

    protected $hidden = [
        'smtp_password',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'smtp_port' => 'integer',
    ];
}
