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

    public function getDisplayImageUrlAttribute(): string
    {
        $name = strtolower($this->name ?? '');

        if (str_contains($name, 'delhi')) {
            return 'https://images.unsplash.com/photo-1587474260584-136574528ed5?auto=format&fit=crop&w=800&q=80'; // India Gate Delhi
        }
        if (str_contains($name, 'bangalore') || str_contains($name, 'bengaluru')) {
            return 'https://images.unsplash.com/photo-1596176530529-78163a4f7af2?auto=format&fit=crop&w=800&q=80'; // Vidhana Soudha Bangalore
        }
        if (str_contains($name, 'noida')) {
            return 'https://images.unsplash.com/photo-1582510003544-4d00b7f74220?auto=format&fit=crop&w=800&q=80'; // Noida Modern Skyline
        }
        if (str_contains($name, 'mumbai') || str_contains($name, 'thane') || str_contains($name, 'navi mumbai')) {
            return 'https://images.unsplash.com/photo-1570168007204-dfb528c6958f?auto=format&fit=crop&w=800&q=80'; // Gateway of India Mumbai
        }
        if (str_contains($name, 'gurugram') || str_contains($name, 'gurgaon')) {
            return 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80'; // DLF Cyber City Gurgaon
        }
        if (str_contains($name, 'hyderabad') || str_contains($name, 'secunderabad')) {
            return 'https://images.unsplash.com/photo-1576487248805-cf45f6bcc67f?auto=format&fit=crop&w=800&q=80'; // Charminar Hyderabad
        }
        if (str_contains($name, 'ahmedabad') || str_contains($name, 'gandhinagar')) {
            return 'https://images.unsplash.com/photo-1599839575945-a9e5af0c3fa5?auto=format&fit=crop&w=800&q=80'; // Atal Bridge & Sabarmati Riverfront Ahmedabad
        }
        if (str_contains($name, 'pune')) {
            return 'https://images.unsplash.com/photo-1627894483216-2138af692e32?auto=format&fit=crop&w=800&q=80'; // Scenic hills & cityscape Pune
        }
        if (str_contains($name, 'lucknow')) {
            return 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=800&q=80'; // Ambedkar Memorial Heritage Lucknow
        }
        if (str_contains($name, 'ghaziabad')) {
            return 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=800&q=80'; // Modern Highrise Cityscape Ghaziabad
        }
        if (str_contains($name, 'jaipur') || str_contains($name, 'jodhpur') || str_contains($name, 'jaisalmer')) {
            return 'https://images.unsplash.com/photo-1599661046289-e31897846e41?auto=format&fit=crop&w=800&q=80'; // Hawa Mahal Jaipur
        }
        if (str_contains($name, 'kolkata') || str_contains($name, 'howrah')) {
            return 'https://images.unsplash.com/photo-1558431382-27e303142255?auto=format&fit=crop&w=800&q=80'; // Howrah Bridge Kolkata
        }
        if (str_contains($name, 'goa') || str_contains($name, 'panaji') || str_contains($name, 'margao')) {
            return 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&w=800&q=80'; // Goa beach & church
        }
        if (str_contains($name, 'udaipur')) {
            return 'https://images.unsplash.com/photo-1615836245337-f5b9b2303f10?auto=format&fit=crop&w=800&q=80'; // Lake Pichola & Palace Udaipur
        }
        if (str_contains($name, 'chandigarh') || str_contains($name, 'mohali') || str_contains($name, 'panchkula')) {
            return 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=800&q=80'; // Planned green city Chandigarh
        }
        if (str_contains($name, 'manali') || str_contains($name, 'shimla') || str_contains($name, 'dehradun') || str_contains($name, 'mussoorie') || str_contains($name, 'nainital') || str_contains($name, 'rishikesh')) {
            return 'https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?auto=format&fit=crop&w=800&q=80'; // Mountains & valley
        }

        return 'https://images.unsplash.com/photo-1587474260584-136574528ed5?auto=format&fit=crop&w=800&q=80';
    }
}
