<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
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
        'ad_type',
        'property_category',
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
        'expected_price',
        'booking_token_amount',
        'price_negotiable',
        'ownership_type',
        'possession_status',
        'carpet_area_sqft',
        'bhk_type',
        'furnishing_status',
        'security_deposit',
        'maintenance_charges',
        'notice_period_days',
        'rating',
        'total_reviews',
        'verification_status',
        'status',
        'featured',
        'is_recommended',
        'tag',
        'is_active',
        'version',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_recommended' => 'boolean',
        'featured' => 'boolean',
        'is_active' => 'boolean',
        'price_negotiable' => 'boolean',
        'rating' => 'float',
        'monthly_rent' => 'float',
        'expected_price' => 'float',
        'booking_token_amount' => 'float',
        'carpet_area_sqft' => 'integer',
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
        $typeSlug = strtolower($this->propertyType?->slug ?? '');
        $gender = strtolower($this->gender_preference ?? '');

        // 1. Commercial Property (no gender preference concept)
        if (in_array($typeSlug, ['commercial', 'shop', 'office', 'retail', 'commercial-space']) || $gender === 'not_applicable') {
            return [
                'label' => 'COMMERCIAL',
                'class' => 'bg-amber-50 text-amber-700',
                'btn_class' => 'bg-amber-600 hover:bg-amber-700 text-white shadow-amber-500/30',
                'text_color' => 'text-amber-700',
                'bg_color' => 'bg-amber-50',
                'is_commercial' => true,
            ];
        }

        // 2. Flat / House Rentals (families, couples, mixed groups / all)
        if (in_array($typeSlug, ['flat', 'flat-apartment', 'apartment', 'house', 'villa']) || in_array($gender, ['all', 'any'])) {
            return [
                'label' => 'FLAT',
                'class' => 'bg-indigo-50 text-indigo-700',
                'btn_class' => 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-indigo-500/30',
                'text_color' => 'text-indigo-700',
                'bg_color' => 'bg-indigo-50',
                'is_flat' => true,
            ];
        }

        // 3. PG / Hostel
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

    public function getIsSaleAttribute(): bool
    {
        return strtolower($this->ad_type ?? 'rent') === 'sale';
    }

    public function getIsRentAttribute(): bool
    {
        return strtolower($this->ad_type ?? 'rent') !== 'sale';
    }

    public function getDisplayPriceFormattedAttribute(): string
    {
        if ($this->is_sale) {
            $price = (float)($this->expected_price ?: $this->monthly_rent);
            if ($price >= 10000000) {
                return '₹' . number_format($price / 10000000, 2) . ' Cr';
            } elseif ($price >= 100000) {
                return '₹' . number_format($price / 100000, 2) . ' Lac';
            }
            return '₹' . number_format($price);
        }
        return '₹' . number_format($this->monthly_rent);
    }

    public function getPricePerSqftAttribute(): ?string
    {
        $sqft = $this->carpet_area_sqft;
        $price = (float)($this->expected_price ?: $this->monthly_rent);
        if ($sqft && $sqft > 0 && $price > 0) {
            return '₹' . number_format(round($price / $sqft)) . '/sq ft';
        }
        return null;
    }

    protected function casts(): array
    {
        return [
            'monthly_rent' => 'decimal:2',
            'expected_price' => 'decimal:2',
            'booking_token_amount' => 'decimal:2',
            'security_deposit' => 'decimal:2',
            'rating' => 'decimal:2',
            'total_beds' => 'integer',
            'available_beds' => 'integer',
            'carpet_area_sqft' => 'integer',
            'total_reviews' => 'integer',
            'featured' => 'boolean',
            'price_negotiable' => 'boolean',
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

    /**
     * Get real dynamic approved reviews count from DB.
     */
    public function getDynamicReviewsCountAttribute(): int
    {
        if (array_key_exists('approved_reviews_count', $this->attributes)) {
            return (int) ($this->attributes['approved_reviews_count'] ?? 0);
        }
        if (array_key_exists('reviews_count', $this->attributes)) {
            return (int) ($this->attributes['reviews_count'] ?? 0);
        }
        if ($this->relationLoaded('approvedReviews')) {
            return $this->approvedReviews->count();
        }
        if ($this->total_reviews !== null && (int)$this->total_reviews > 0) {
            return (int) $this->total_reviews;
        }
        return 0;
    }

    /**
     * Get real dynamic rating from DB.
     */
    public function getDynamicRatingAttribute(): string
    {
        if (array_key_exists('dynamic_rating', $this->attributes)) {
            return $this->attributes['dynamic_rating'] !== null 
                ? number_format((float) $this->attributes['dynamic_rating'], 1)
                : ($this->rating && floatval($this->rating) > 0 ? number_format((float) $this->rating, 1) : '4.8');
        }
        if (array_key_exists('approved_reviews_avg_rating', $this->attributes)) {
            return $this->attributes['approved_reviews_avg_rating'] !== null 
                ? number_format((float) $this->attributes['approved_reviews_avg_rating'], 1)
                : ($this->rating && floatval($this->rating) > 0 ? number_format((float) $this->rating, 1) : '4.8');
        }
        if (array_key_exists('avg_rating', $this->attributes)) {
            return $this->attributes['avg_rating'] !== null 
                ? number_format((float) $this->attributes['avg_rating'], 1)
                : ($this->rating && floatval($this->rating) > 0 ? number_format((float) $this->rating, 1) : '4.8');
        }
        if ($this->relationLoaded('approvedReviews')) {
            $count = $this->approvedReviews->count();
            if ($count > 0) {
                return number_format($this->approvedReviews->avg('rating'), 1);
            }
            return $this->rating && floatval($this->rating) > 0 ? number_format((float) $this->rating, 1) : '4.8';
        }
        if ($this->rating && floatval($this->rating) > 0) {
            return number_format((float) $this->rating, 1);
        }
        return '4.8';
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

    public function reports()
    {
        return $this->hasMany(PropertyReport::class, 'property_id', 'id');
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

    public function reviews()
    {
        return $this->hasMany(Review::class, 'property_id', 'id');
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class, 'property_id', 'id')->where('status', 'approved');
    }

    /**
     * Get active Room Configurations & Sharing Types for this Property.
     */
    public function getRoomConfigurationsAttribute()
    {
        return DB::table('rooms')
            ->join('floors', 'rooms.floor_id', '=', 'floors.id')
            ->join('blocks', 'floors.block_id', '=', 'blocks.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('blocks.property_id', $this->id)
            ->whereNull('rooms.deleted_at')
            ->select(
                'rooms.id as room_id',
                'rooms.monthly_rent',
                'rooms.security_deposit',
                'rooms.total_beds',
                'rooms.available_beds',
                'rooms.status as room_status',
                'rooms.attached_bathroom',
                'rooms.ac_available',
                'rooms.balcony',
                'room_types.id as room_type_id',
                'room_types.name as room_type_name',
                'room_types.slug as room_type_slug',
                'room_types.max_occupancy'
            )
            ->orderBy('room_types.max_occupancy', 'asc')
            ->get();
    }

    /**
     * Check if entire property is fully booked / sold out (0 available beds).
     */
    public function getIsFullyBookedAttribute(): bool
    {
        if ($this->status === 'fully_booked' || $this->status === 'occupied' || $this->status === 'sold_out') {
            return true;
        }
        if ($this->available_beds !== null && (int)$this->available_beds === 0) {
            return true;
        }
        return false;
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

    /**
     * Fallback and smart coordinates resolver for Map View.
     */
    public function getMapLatitudeAttribute()
    {
        if ($this->latitude && is_numeric($this->latitude) && (float) $this->latitude != 0) {
            return (float) $this->latitude;
        }
        if ($this->area && $this->area->latitude && is_numeric($this->area->latitude)) {
            return (float) $this->area->latitude;
        }
        if ($this->city && $this->city->latitude && is_numeric($this->city->latitude)) {
            return (float) $this->city->latitude;
        }

        $cityName = strtolower(trim($this->city->name ?? ''));
        $cityDefaults = [
            'noida' => 28.6280,
            'greater noida' => 28.4744,
            'new delhi' => 28.6139,
            'delhi' => 28.6139,
            'bangalore' => 12.9716,
            'bangalore (bengaluru)' => 12.9716,
            'bengaluru' => 12.9716,
            'gurgaon' => 28.4595,
            'gurugram' => 28.4595,
            'mumbai' => 19.0760,
            'pune' => 18.5204,
            'hyderabad' => 17.3850,
            'chennai' => 13.0827,
            'kolkata' => 22.5726,
            'ahmedabad' => 23.0225,
            'jaipur' => 26.9124,
            'chandigarh' => 30.7333,
            'lucknow' => 26.8467,
            'indore' => 22.7196,
        ];

        // If multiple properties in same city, slightly disperse coordinates so pins don't overlap exactly
        $hashOffset = (crc32($this->id ?? '') % 100) * 0.0003;
        return ($cityDefaults[$cityName] ?? 28.6280) + $hashOffset;
    }

    public function getMapLongitudeAttribute()
    {
        if ($this->longitude && is_numeric($this->longitude) && (float) $this->longitude != 0) {
            return (float) $this->longitude;
        }
        if ($this->area && $this->area->longitude && is_numeric($this->area->longitude)) {
            return (float) $this->area->longitude;
        }
        if ($this->city && $this->city->longitude && is_numeric($this->city->longitude)) {
            return (float) $this->city->longitude;
        }

        $cityName = strtolower(trim($this->city->name ?? ''));
        $cityDefaults = [
            'noida' => 77.3649,
            'greater noida' => 77.5030,
            'new delhi' => 77.2090,
            'delhi' => 77.2090,
            'bangalore' => 77.5946,
            'bangalore (bengaluru)' => 77.5946,
            'bengaluru' => 77.5946,
            'gurgaon' => 77.0266,
            'gurugram' => 77.0266,
            'mumbai' => 72.8777,
            'pune' => 73.8567,
            'hyderabad' => 78.4867,
            'chennai' => 80.2707,
            'kolkata' => 88.3639,
            'ahmedabad' => 72.5714,
            'jaipur' => 75.7873,
            'chandigarh' => 76.7794,
            'lucknow' => 80.9462,
            'indore' => 75.8577,
        ];

        $hashOffset = (crc32(($this->id ?? '') . 'lng') % 100) * 0.0003;
        return ($cityDefaults[$cityName] ?? 77.3649) + $hashOffset;
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
