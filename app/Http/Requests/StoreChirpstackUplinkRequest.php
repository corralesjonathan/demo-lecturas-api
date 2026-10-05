<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChirpstackUplinkRequest extends FormRequest
{
    /**
     * Whether this request carries an uplink.
     *
     * ChirpStack posts every integration event to the same URL, tagged with an
     * "event" query parameter (up, join, status, ack, txack, log, location).
     * Only "up" carries a reading.
     */
    public function isUplink(): bool
    {
        return $this->query('event', 'up') === 'up';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // Events other than "up" are acknowledged and ignored by the controller,
        // so there is nothing to validate on them.
        if (! $this->isUplink()) {
            return [];
        }

        return [
            'deduplicationId' => ['required', 'uuid'],
            'time' => ['required', 'date'],
            'fCnt' => ['nullable', 'integer', 'min:0'],

            'deviceInfo' => ['required', 'array'],
            'deviceInfo.devEui' => ['required', 'string', 'regex:/^[0-9a-fA-F]{16}$/'],

            'object' => ['required', 'array'],
            'object.volumeNet_m3' => ['required_without:object.volumeForward_m3', 'numeric', 'min:0'],
            'object.volumeForward_m3' => ['nullable', 'numeric', 'min:0'],
            'object.volumeReverse_m3' => ['nullable', 'numeric', 'min:0'],
            'object.flowRate_Lh' => ['nullable', 'numeric'],
            'object.waterTemperature_C' => ['nullable', 'numeric'],
            'object.battery_pct' => ['nullable', 'integer', 'between:0,100'],
            'object.messageType' => ['nullable', 'string', 'max:50'],
            'object.alarmsRaw' => ['nullable', 'integer'],
            'object.alarms' => ['nullable', 'array'],
            'object.activeAlarms' => ['nullable', 'array'],

            'rxInfo' => ['nullable', 'array'],
            'rxInfo.*.rssi' => ['nullable', 'integer'],
            'rxInfo.*.snr' => ['nullable', 'numeric'],
            'rxInfo.*.gatewayId' => ['nullable', 'string', 'max:16'],
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'deviceInfo.devEui.regex' => 'The devEui must be exactly 16 hexadecimal characters.',
        ];
    }
}
