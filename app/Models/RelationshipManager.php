<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class RelationshipManager extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'relationship_managers';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'whatsapp_number',
        'designation',
        'zone',
        'city_coverage',
        'working_hours',
        'avatar_url',
        'is_active',
        'is_default',
        'version',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'version' => 'integer',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Get all brokers assigned to this Relationship Manager.
     */
    public function brokers()
    {
        return $this->hasMany(User::class, 'relationship_manager_id', 'id');
    }

    /**
     * Get count of assigned active brokers.
     */
    public function getActiveBrokersCountAttribute(): int
    {
        return $this->brokers()->where('status', 'active')->count();
    }
}
