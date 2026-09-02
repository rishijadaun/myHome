<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RoommateMessage extends Model
{
    use HasFactory;

    protected $table = 'roommate_messages';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'roommate_post_id',
        'sender_id',
        'receiver_id',
        'sender_name',
        'sender_email',
        'sender_phone',
        'message',
        'is_read',
        'moderation_status',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function post()
    {
        return $this->belongsTo(RoommatePost::class, 'roommate_post_id', 'id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }
}
