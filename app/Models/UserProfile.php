<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_profiles';

    protected $primaryKey = 'user_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'avatar_url',
        'gender',
        'gender_id',
        'date_of_birth',
        'occupation',
        'occupation_id',
        'company_name',
        'bio',
        'preferences',
        'notification_settings',
        'language_id',
        'timezone_id',
        'is_active',
        'version',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function getAgeAttribute(): ?int
    {
        if ($this->date_of_birth) {
            return \Carbon\Carbon::parse($this->date_of_birth)->age;
        }
        return null;
    }

    public function getFullNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }

    public function getTaglineAttribute(): string
    {
        $parts = [];
        if ($this->age) {
            $parts[] = "{$this->age} years";
        }
        if ($this->gender) {
            $parts[] = ucfirst($this->gender);
        }
        if ($this->occupation) {
            $parts[] = $this->occupation;
        }
        return !empty($parts) ? implode(' · ', $parts) : '';
    }

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'preferences' => 'array',
            'notification_settings' => 'array',
            'is_active' => 'boolean',
            'version' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
