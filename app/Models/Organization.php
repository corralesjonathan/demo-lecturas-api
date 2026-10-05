<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'chirpstack_tenant_id',
        'chirpstack_application_id',
        'legal_id',
        'legal_name',
        'display_name',
        'contact_phone',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }

    /**
     * The subscribers belonging to the organization.
     *
     * @return HasMany<Subscriber, $this>
     */
    public function subscribers(): HasMany
    {
        return $this->hasMany(Subscriber::class);
    }

    /**
     * The meters owned by the organization.
     *
     * @return HasMany<Meter, $this>
     */
    public function meters(): HasMany
    {
        return $this->hasMany(Meter::class);
    }
}
