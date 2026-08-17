<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'areas';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'city_id',
        'name',
        'slug',
        'pincode',
        'latitude',
        'longitude',
        'is_popular',
        'pg_count',
        'avg_rent',
        'is_active',
        'version',
    ];

    protected function casts(): array
    {
        return [
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'avg_rent' => 'decimal:2',
            'pg_count' => 'integer',
        ];
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'area_id', 'id');
    }
}
