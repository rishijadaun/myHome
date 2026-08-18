<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'properties';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'organization_id',
        'broker_id',
        'city_id',
        'area_id',
        'property_type_id',
        'name',
        'slug',
        'description',
        'address',
        'landmark',
        'latitude',
        'longitude',
        'gender_preference',
        'total_beds',
        'available_beds',
        'monthly_rent',
        'security_deposit',
        'notice_period_days',
        'rating',
        'total_reviews',
        'verification_status',
        'status',
        'featured',
        'tag',
        'is_active',
        'version',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public const ALLOWED_TAGS = [
        'Popular' => [
            'label' => 'Popular',
            'icon' => 'fire',
            'bg_class' => 'bg-orange-50 text-orange-700 border-orange-200',
            'solid_badge' => 'bg-orange-500 text-white',
            'dot_color' => 'bg-orange-500',
        ],
        'Verified' => [
            'label' => 'Verified',
            'icon' => 'check-circle',
            'bg_class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'solid_badge' => 'bg-emerald-500 text-white',
            'dot_color' => 'bg-emerald-500',
        ],
        'Guest Favourite' => [
            'label' => 'Guest Favourite',
            'icon' => 'crown',
            'bg_class' => 'bg-rose-50 text-rose-700 border-rose-200',
            'solid_badge' => 'bg-amber-500 text-white',
            'dot_color' => 'bg-rose-500',
        ],
        'Trending' => [
            'label' => 'Trending',
            'icon' => 'fire',
            'bg_class' => 'bg-purple-50 text-purple-700 border-purple-200',
            'solid_badge' => 'bg-blue-600 text-white',
            'dot_color' => 'bg-purple-500',
        ],
        'Top rated' => [
            'label' => 'Top Rated',
            'icon' => 'star',
            'bg_class' => 'bg-amber-50 text-amber-800 border-amber-200',
            'solid_badge' => 'bg-amber-500 text-white',
            'dot_color' => 'bg-amber-500',
        ],
        'New' => [
            'label' => 'NEW',
            'icon' => 'bolt',
            'bg_class' => 'bg-blue-50 text-blue-700 border-blue-200',
            'solid_badge' => 'bg-red-500 text-white',
            'dot_color' => 'bg-blue-500',
        ],
    ];

    public function getTagMetaAttribute(): ?array
    {
        if (empty($this->tag)) {
            return null;
        }

        foreach (self::ALLOWED_TAGS as $key => $meta) {
            if (strcasecmp($this->tag, $key) === 0) {
                return $meta;
            }
        }

        return [
            'label' => $this->tag,
            'icon' => 'tag',
            'bg_class' => 'bg-gray-50 text-gray-700 border-gray-200',
            'solid_badge' => 'bg-emerald-500 text-white',
            'dot_color' => 'bg-gray-500',
        ];
    }

    public function getDisplayTagMetaAttribute(): array
    {
        if ($this->tag_meta) {
            return $this->tag_meta;
        }
        return [
            'label' => 'Verified',
            'icon' => 'check-circle',
            'bg_class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'solid_badge' => 'bg-emerald-500 text-white',
            'dot_color' => 'bg-emerald-500',
        ];
    }

    public function getDisplayImageUrlAttribute(): string
    {
        if ($this->primaryImage && !empty($this->primaryImage->image_url)) {
            return $this->primaryImage->image_url;
        }
        $first = $this->images->first();
        if ($first && !empty($first->image_url)) {
            return $first->image_url;
        }
        return 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';
    }

    public function getGenderTypeMetaAttribute(): array
    {
        $gender = strtolower($this->gender_preference ?? 'co-ed');
        if ($gender === 'boys' || $gender === 'male') {
            return [
                'label' => 'BOYS',
                'class' => 'bg-blue-50 text-blue-600',
                'btn_class' => 'bg-blue-600 hover:bg-blue-700 text-white shadow-blue-500/30',
                'text_color' => 'text-blue-600',
                'bg_color' => 'bg-blue-50',
            ];
        }
        if ($gender === 'girls' || $gender === 'female') {
            return [
                'label' => 'GIRLS',
                'class' => 'bg-pink-50 text-pink-600',
                'btn_class' => 'bg-brand hover:bg-brand-dark text-white shadow-brand/30',
                'text_color' => 'text-pink-600',
                'bg_color' => 'bg-pink-50',
            ];
        }
        return [
            'label' => 'CO-ED',
            'class' => 'bg-purple-50 text-purple-600',
            'btn_class' => 'bg-brand hover:bg-brand-dark text-white shadow-brand/30',
            'text_color' => 'text-purple-600',
            'bg_color' => 'bg-purple-50',
        ];
    }

    protected function casts(): array
    {
        return [
            'monthly_rent' => 'decimal:2',
            'security_deposit' => 'decimal:2',
            'rating' => 'decimal:2',
            'total_beds' => 'integer',
            'available_beds' => 'integer',
            'total_reviews' => 'integer',
            'featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
            if (empty($model->slug)) {
                $model->slug = static::generateSlug($model->name, $model->id);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('name') && (empty($model->slug) || $model->getOriginal('name') !== $model->name)) {
                $model->slug = static::generateSlug($model->name, $model->id);
            }
        });
    }

    public static function generateSlug(string $name, ?string $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        if (empty($baseSlug)) {
            $baseSlug = 'property';
        }
        $slug = $baseSlug;
        $count = 1;
        while (static::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $count++;
            $slug = "{$baseSlug}-{$count}";
        }
        return $slug;
    }

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class, 'property_type_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class, 'property_id', 'id')->orderBy('sort_order', 'asc');
    }

    public function primaryImage()
    {
        return $this->hasOne(PropertyImage::class, 'property_id', 'id')->where('is_primary', 1);
    }

    public function amenities()
    {
        return $this->belongsToMany(
            Amenity::class,
            'property_amenities',
            'property_id',
            'amenity_id'
        );
    }

    public function broker()
    {
        return $this->belongsTo(User::class, 'broker_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'property_id', 'id');
    }

    public function visits()
    {
        return $this->hasMany(PropertyVisit::class, 'property_id', 'id');
    }

    public function rules()
    {
        return $this->hasMany(PropertyRule::class, 'property_id', 'id');
    }

    /**
     * Scopes for dynamic filtering
     */
    public function scopeOfType($query, $typeSlug)
    {
        return $query->whereHas('propertyType', function ($q) use ($typeSlug) {
            $q->where('slug', $typeSlug);
        });
    }

    public function scopeApproved($query)
    {
        return $query->where('verification_status', 'verified')->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }
}
