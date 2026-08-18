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
        'is_active',
        'version',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

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
