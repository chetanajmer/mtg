<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A colleague with their own login to a company account. account_id is the
 * owner's storeusers id; user_id is the member's own storeusers row.
 */
class CompanyTeamMember extends Model
{
    public const ROLES = [
        'admin' => ['label' => 'Admin', 'hint' => 'Everything, including managing the team (not the owner\'s login and password)'],
        'buyer' => ['label' => 'Buyer', 'hint' => 'Orders, quotes, approvals, collections and employees'],
        'viewer' => ['label' => 'Viewer', 'hint' => 'Can look at everything but not change anything'],
    ];

    public const INVITE_DAYS = 7;

    protected $guarded = ['id'];

    protected $casts = ['invited_at' => 'datetime', 'expires_at' => 'datetime', 'accepted_at' => 'datetime'];

    public function roleLabel()
    {
        return self::ROLES[$this->role]['label'] ?? ucfirst($this->role);
    }

    public function inviteExpired()
    {
        return $this->status === 'invited' && $this->expires_at && $this->expires_at->isPast();
    }
}
