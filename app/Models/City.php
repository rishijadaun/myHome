<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cities';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'state_id',
        'name',
        'slug',
        'latitude',
        'longitude',
        'district',
        'is_metro',
        'is_tier1',
        'is_active',
        'version',
    ];

    protected function casts(): array
    {
        return [
            'is_metro' => 'boolean',
            'is_tier1' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function areas()
    {
        return $this->hasMany(Area::class, 'city_id', 'id');
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'city_id', 'id');
    }
}
