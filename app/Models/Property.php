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
            'solid_badge' => 'bg-gradient-to-r from-orange-500 to-amber-500 text-white',
            'dot_color' => 'bg-orange-500',
        ],
        'Verified' => [
            'label' => 'Verified',
            'icon' => 'check-circle',
            'bg_class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'solid_badge' => 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white',
            'dot_color' => 'bg-emerald-500',
        ],
        'Guest Favourite' => [
            'label' => 'Guest Favourite',
            'icon' => 'heart',
            'bg_class' => 'bg-rose-50 text-rose-700 border-rose-200',
            'solid_badge' => 'bg-gradient-to-r from-rose-500 to-pink-500 text-white',
            'dot_color' => 'bg-rose-500',
        ],
        'Trending' => [
            'label' => 'Trending',
            'icon' => 'bolt',
            'bg_class' => 'bg-purple-50 text-purple-700 border-purple-200',
            'solid_badge' => 'bg-gradient-to-r from-purple-500 to-indigo-600 text-white',
            'dot_color' => 'bg-purple-500',
        ],
        'Top rated' => [
            'label' => 'Top rated',
            'icon' => 'star',
            'bg_class' => 'bg-amber-50 text-amber-800 border-amber-200',
            'solid_badge' => 'bg-gradient-to-r from-amber-400 to-yellow-500 text-slate-900',
            'dot_color' => 'bg-amber-500',
        ],
        'New' => [
            'label' => 'New',
            'icon' => 'sparkles',
            'bg_class' => 'bg-blue-50 text-blue-700 border-blue-200',
            'solid_badge' => 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white',
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
            'solid_badge' => 'bg-gray-800 text-white',
            'dot_color' => 'bg-gray-500',
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
                $model->slug = Str::slug($model->name) . '-' . substr((string) Str::uuid(), 0, 8);
            }
        });
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
