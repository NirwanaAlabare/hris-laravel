<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_email',
        'href_menu',
        'status_staff',
        'receiver_email',
        'type',
        'message',
        'is_read',
        'is_delete',
        'enroll_ids',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_delete' => 'boolean',
        'enroll_ids' => 'array',
    ];

    public function sender()
    {
        return $this->belongsTo(Admin::class, 'sender_email', 'email');
    }

    public function receiver()
    {
        return $this->belongsTo(Admin::class, 'receiver_email', 'email');
    }
}
