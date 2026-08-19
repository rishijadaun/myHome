<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInquiry extends Model
{
    use HasFactory;

    protected $table = 'contact_inquiries';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'user_type',
        'city',
        'message',
        'status',
        'admin_notes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * User type labels map
     */
    public static function userTypes(): array
    {
        return [
            'tenant' => 'Student / Professional (Looking for PG)',
            'owner' => 'PG Owner / Host',
            'partner' => 'Corporate / Broker Partner',
            'support' => 'Existing Resident (Support)',
            'other' => 'General Inquiry',
        ];
    }

    /**
     * Statuses map
     */
    public static function statuses(): array
    {
        return [
            'new' => 'New / Unread',
            'in_progress' => 'In Progress',
            'resolved' => 'Resolved',
            'archived' => 'Archived',
        ];
    }

    /**
     * Helper to get user friendly type name
     */
    public function getUserTypeLabelAttribute(): string
    {
        $types = self::userTypes();
        return $types[$this->user_type] ?? ucfirst($this->user_type ?? 'General');
    }

    /**
     * Scope for searching
     */
    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            $search = trim($search);
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }
        return $query;
    }
}
