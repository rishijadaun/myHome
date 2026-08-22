<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bookings';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'booking_id',
        'user_id',
        'tenant_name',
        'tenant_phone',
        'tenant_email',
        'property_id',
        'room_id',
        'bed_id',
        'room_type_name',
        'broker_id',
        'check_in_date',
        'check_out_date',
        'duration_months',
        'base_rent',
        'security_deposit',
        'maintenance_charges',
        'discount_amount',
        'coupon_code',
        'total_amount',
        'paid_amount',
        'payment_status',
        'booking_status',
        'broker_approval',
        'cancellation_reason',
        'special_requests',
        'is_active',
        'version',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected function casts(): array
    {
        return [
            'check_in_date' => 'date',
            'check_out_date' => 'date',
            'base_rent' => 'decimal:2',
            'security_deposit' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
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
            if (empty($model->booking_id)) {
                $model->booking_id = 'BK-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }

    public function broker()
    {
        return $this->belongsTo(User::class, 'broker_id', 'id');
    }

    public function getEffectiveTenantNameAttribute(): string
    {
        if (!empty($this->tenant_name)) {
            return $this->tenant_name;
        }
        if ($this->user) {
            return $this->user->name ?: ($this->user->email ?? 'Guest');
        }
        return 'Guest User';
    }

    public function getEffectiveTenantPhoneAttribute(): string
    {
        if (!empty($this->tenant_phone)) {
            return $this->tenant_phone;
        }
        return $this->user?->phone ?? '';
    }

    public function getDisplayStatusAttribute(): array
    {
        if ($this->broker_approval === 'rejected' || $this->booking_status === 'cancelled') {
            return [
                'label' => 'CANCELLED',
                'bg' => 'bg-red-100',
                'text' => 'text-red-700',
                'border' => 'border-red-200',
                'tab' => 'CANCELLED'
            ];
        }

        if ($this->booking_status === 'completed' || $this->booking_status === 'moved_out') {
            return [
                'label' => 'COMPLETED',
                'bg' => 'bg-blue-100',
                'text' => 'text-blue-700',
                'border' => 'border-blue-200',
                'tab' => 'COMPLETED'
            ];
        }

        if ($this->broker_approval === 'approved' || $this->booking_status === 'confirmed') {
            return [
                'label' => 'CONFIRMED',
                'bg' => 'bg-green-100',
                'text' => 'text-green-700',
                'border' => 'border-green-200',
                'tab' => 'UPCOMING'
            ];
        }

        return [
            'label' => 'PENDING APPROVAL',
            'bg' => 'bg-yellow-100',
            'text' => 'text-yellow-800',
            'border' => 'border-yellow-200',
            'tab' => 'UPCOMING'
        ];
    }
}
