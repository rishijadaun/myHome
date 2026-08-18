<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'email',
        'phone',
        'password_hash',
        'remember_token',
        'email_verified_at',
        'phone_verified_at',
        'kyc_verified_at',
        'relationship_manager_id',
        'status',
        'is_active',
        'version',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'kyc_verified_at' => 'datetime',
            'is_active' => 'boolean',
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
     * Override default Laravel password column name
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Dynamic user full name accessor from profile or email.
     */
    public function getNameAttribute()
    {
        if ($this->relationLoaded('profile') && $this->profile) {
            $fullName = trim(($this->profile->first_name ?? '') . ' ' . ($this->profile->last_name ?? ''));
            if (!empty($fullName)) return $fullName;
        } elseif ($this->profile) {
            $fullName = trim(($this->profile->first_name ?? '') . ' ' . ($this->profile->last_name ?? ''));
            if (!empty($fullName)) return $fullName;
        }

        if (!empty($this->email)) {
            $parts = explode('@', $this->email);
            return ucfirst($parts[0]);
        }

        if (!empty($this->phone)) {
            return substr($this->phone, 0, 4) . '****' . substr($this->phone, -2);
        }

        return 'Resident User';
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    public function relationshipManager()
    {
        return $this->belongsTo(RelationshipManager::class, 'relationship_manager_id', 'id');
    }

    public function userRoles()
    {
        return $this->hasMany(UserRole::class, 'user_id', 'id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id')
                    ->wherePivot('is_active', 1);
    }

    public function primaryRole()
    {
        return $this->hasOne(UserRole::class, 'user_id', 'id')
                    ->where('is_primary', 1)
                    ->where('is_active', 1)
                    ->with('role');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'user_id', 'id');
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'broker_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id', 'id');
    }

    public function visits()
    {
        return $this->hasMany(PropertyVisit::class, 'user_id', 'id');
    }

    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class, 'user_id', 'id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'id');
    }
}
