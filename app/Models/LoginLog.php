<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoginLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['logged_at'];

    protected $fillable = [
        'user_id',
        'status',
        'ip_address',
        'user_agent',
        'message',
        'location',
        'latitude',
        'longitude',
        'logged_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
