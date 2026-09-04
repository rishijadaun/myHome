<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PropertyImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'property_images';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'property_id',
        'image_url',
        'image_type',
        'sort_order',
        'is_primary',
        'is_active',
        'version',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
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

    protected $appends = ['thumbnail_url'];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }

    /**
     * Get responsive WebP thumbnail URL for cards and grid layouts.
     */
    public function getThumbnailUrlAttribute(): string
    {
        $imageUrl = $this->image_url ?? '';
        if (empty($imageUrl)) {
            return 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=400&q=80';
        }

        // If local upload with thumb_ alternative
        if (str_contains($imageUrl, '/uploads/')) {
            $parts = explode('/', $imageUrl);
            $fileName = end($parts);
            if (!str_starts_with($fileName, 'thumb_')) {
                $thumbFileName = 'thumb_' . $fileName;
                return str_replace($fileName, $thumbFileName, $imageUrl);
            }
            return $imageUrl;
        }

        // Unsplash responsive sizing
        if (str_contains($imageUrl, 'images.unsplash.com')) {
            if (str_contains($imageUrl, 'w=')) {
                return preg_replace('/w=\d+/', 'w=450', $imageUrl);
            }
            return $imageUrl . '&w=450';
        }

        return $imageUrl;
    }
}
