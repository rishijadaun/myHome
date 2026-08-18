<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PropertyReport extends Model
{
    use HasFactory;

    protected $table = 'property_reports';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'property_id',
        'user_id',
        'reporter_name',
        'reporter_email',
        'reporter_phone',
        'reason',
        'description',
        'status',
        'admin_notes',
        'ip_address',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
