<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class RoommatePost extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'roommate_posts';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 'user_id', 'title', 'slug', 'post_type',
        'city', 'locality', 'full_address',
        'poster_name', 'poster_age', 'poster_gender', 'profession', 'occupation_type',
        'gender_preference', 'bhk_type', 'furnishing',
        'budget_min', 'budget_max', 'move_in_date', 'preferred_duration_months',
        'lifestyle', 'amenities', 'description',
        'contact_phone', 'contact_whatsapp', 'contact_visible_to_all',
        'poster_avatar_url',
        'status', 'is_active', 'expires_at', 'view_count', 'version',
    ];

    protected $casts = [
        'lifestyle'       => 'array',
        'amenities'       => 'array',
        'move_in_date'    => 'date',
        'expires_at'      => 'datetime',
        'is_active'       => 'boolean',
        'contact_visible_to_all' => 'boolean',
        'version'         => 'integer',
        'budget_min'      => 'integer',
        'budget_max'      => 'integer',
        'poster_age'      => 'integer',
        'view_count'      => 'integer',
        'preferred_duration_months' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
            if (empty($model->slug)) {
                $model->slug = self::generateSlug($model->title, $model->city);
            }
            if (empty($model->expires_at)) {
                $model->expires_at = now()->addDays(30);
            }
        });
    }

    public static function generateSlug(string $title, string $city = ''): string
    {
        $base = Str::slug(($city ? $city . '-' : '') . $title);
        $slug = $base;
        $i = 1;
        while (static::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    // ── Relationships ────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where('is_active', true)
                     ->where(function ($q) {
                         $q->whereNull('expires_at')
                           ->orWhere('expires_at', '>', now());
                     });
    }

    public function scopeCity($query, string $city)
    {
        return $query->where('city', 'like', "%{$city}%");
    }

    public function scopeGender($query, string $gender)
    {
        return $query->where(function ($q) use ($gender) {
            $q->where('gender_preference', $gender)
              ->orWhere('gender_preference', 'any');
        });
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    public function getPostTypeLabelAttribute(): string
    {
        return $this->post_type === 'have_room' ? 'Room Available' : 'Looking for Room';
    }

    public function getBudgetRangeAttribute(): string
    {
        if ($this->budget_min && $this->budget_max) {
            return '₹' . number_format($this->budget_min) . ' – ₹' . number_format($this->budget_max) . '/mo';
        }
        if ($this->budget_max) {
            return 'Upto ₹' . number_format($this->budget_max) . '/mo';
        }
        if ($this->budget_min) {
            return '₹' . number_format($this->budget_min) . '+/mo';
        }
        return 'Budget flexible';
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getGenderIconAttribute(): string
    {
        return match($this->poster_gender) {
            'male'   => '👨',
            'female' => '👩',
            default  => '🧑',
        };
    }

    public function getPosterAvatarUrlAttribute($value): ?string
    {
        if (!empty($value)) {
            return $value;
        }
        return $this->user?->profile?->avatar_url;
    }

    public function getGenderPrefColorAttribute(): string
    {
        return match($this->gender_preference) {
            'male'   => 'blue',
            'female' => 'pink',
            default  => 'green',
        };
    }

    // ── Static Helpers ───────────────────────────────────────────────────────

    public static function popularCities(): array
    {
        return [
            'Gurgaon', 'Bangalore', 'Noida', 'Delhi',
            'Hyderabad', 'Pune', 'Mumbai', 'Lucknow', 'Chennai', 'Ghaziabad',
        ];
    }

    public static function bhkOptions(): array
    {
        return [
            'single_room' => 'Single Room',
            '1bhk'        => '1 BHK',
            '2bhk'        => '2 BHK',
            '3bhk'        => '3 BHK',
            'studio'      => 'Studio Apt',
            'any'         => 'Any',
        ];
    }

    public static function lifestyleOptions(): array
    {
        return [
            'veg'            => ['label' => 'Vegetarian',         'icon' => '🥦'],
            'non_veg'        => ['label' => 'Non-Vegetarian OK',  'icon' => '🍗'],
            'no_smoking'     => ['label' => 'Non-Smoker',         'icon' => '🚭'],
            'smoking_ok'     => ['label' => 'Smoker OK',          'icon' => '🚬'],
            'no_pets'        => ['label' => 'No Pets',            'icon' => '🐾'],
            'pets_ok'        => ['label' => 'Pets OK',            'icon' => '🐶'],
            'early_bird'     => ['label' => 'Early Bird',         'icon' => '🌅'],
            'night_owl'      => ['label' => 'Night Owl',          'icon' => '🦉'],
            'party_friendly' => ['label' => 'Party-Friendly',     'icon' => '🥳'],
            'couple_friendly'=> ['label' => 'Couple-Friendly',    'icon' => '💑'],
            'gym_person'     => ['label' => 'Gym / Fitness',      'icon' => '💪'],
            'wfh'            => ['label' => 'Work from Home',     'icon' => '💻'],
        ];
    }

    public static function amenitiesOptions(): array
    {
        return [
            'fridge'          => ['label' => 'Fridge',          'icon' => 'fa-solid fa-temperature-low', 'emoji' => '🧊'],
            'kitchen'         => ['label' => 'Kitchen',         'icon' => 'fa-solid fa-kitchen-set',     'emoji' => '🍳'],
            'wifi'            => ['label' => 'Wifi',            'icon' => 'fa-solid fa-wifi',            'emoji' => '📶'],
            'parking'         => ['label' => 'Parking',         'icon' => 'fa-solid fa-square-parking',  'emoji' => '🚗'],
            'ac'              => ['label' => 'AC',              'icon' => 'fa-solid fa-snowflake',       'emoji' => '❄️'],
            'washing_machine' => ['label' => 'Washing Machine', 'icon' => 'fa-solid fa-soap',            'emoji' => '🧺'],
            'tv'              => ['label' => 'TV',              'icon' => 'fa-solid fa-tv',              'emoji' => '📺'],
            'power_backup'    => ['label' => 'Power Backup',    'icon' => 'fa-solid fa-bolt',            'emoji' => '⚡'],
            'cook_maid'       => ['label' => 'Cook / Maid',     'icon' => 'fa-solid fa-utensils',        'emoji' => '🧹'],
            'ro_water'        => ['label' => 'RO Water',        'icon' => 'fa-solid fa-faucet-drip',     'emoji' => '🚰'],
            'geyser'          => ['label' => 'Geyser',          'icon' => 'fa-solid fa-shower',          'emoji' => '🚿'],
            'balcony'         => ['label' => 'Attached Balcony','icon' => 'fa-solid fa-table-cells-large','emoji' => '🌅'],
        ];
    }
}
