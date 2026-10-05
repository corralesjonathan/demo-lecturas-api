<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeterReading extends Model
{
    use HasUuids;

    /**
     * Telemetry is an append-only log, so there is no updated_at column.
     */
    public const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'meter_id',
        'deduplication_id',
        'volume_net_m3',
        'volume_forward_m3',
        'volume_reverse_m3',
        'flow_rate_lh',
        'water_temperature_c',
        'battery_pct',
        'message_type',
        'alarms_raw',
        'alarms',
        'active_alarms',
        'f_cnt',
        'rssi',
        'snr',
        'gateway_id',
        'recorded_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'volume_net_m3' => 'decimal:3',
            'volume_forward_m3' => 'decimal:3',
            'volume_reverse_m3' => 'decimal:3',
            'flow_rate_lh' => 'decimal:3',
            'water_temperature_c' => 'decimal:2',
            'battery_pct' => 'integer',
            'alarms_raw' => 'integer',
            'alarms' => 'array',
            'active_alarms' => 'array',
            'f_cnt' => 'integer',
            'rssi' => 'integer',
            'snr' => 'float',
            'recorded_at' => 'immutable_datetime',
            'created_at' => 'immutable_datetime',
        ];
    }

    /**
     * The meter the reading came from.
     *
     * @return BelongsTo<Meter, $this>
     */
    public function meter(): BelongsTo
    {
        return $this->belongsTo(Meter::class);
    }
}
