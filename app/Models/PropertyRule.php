<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyRule extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'property_rules';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'property_id',
        'rule_text',
        'rule_type',
        'is_active',
        'version'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'version' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
