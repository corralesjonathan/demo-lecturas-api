<?php

namespace Database\Seeders;

use App\Enums\MeterStatus;
use App\Models\Meter;
use App\Models\Organization;
use App\Models\Service;
use App\Models\Subscriber;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the demo data.
     *
     * Everything uses firstOrCreate, so running this more than once is safe.
     * The ChirpStack ids and the DevEUI come from a real uplink, which means the
     * webhook can be exercised against this data as-is.
     */
    public function run(): void
    {
        $calema = Organization::firstOrCreate(
            ['legal_id' => '3002555444'],
            [
                'chirpstack_tenant_id' => 'd3f2ab8d-f7ab-44d7-8d49-23efca16d23a',
                'chirpstack_application_id' => '495d9e0d-b249-47d1-a0df-702007322d39',
                'legal_name' => 'ASADA de Calema R.L.',
                'display_name' => 'ASADA Calema',
                'contact_phone' => '+50624500100',
            ],
        );

        $mora = Subscriber::firstOrCreate(
            ['organization_id' => $calema->id, 'identification' => '203450678'],
            ['full_name' => 'José Alberto Mora Chaves', 'phone' => '+50683341122'],
        );

        // Service with no meter yet: this is the one the plumber assigns to.
        Service::firstOrCreate(
            ['service_number' => 'CAL-0035'],
            [
                'subscriber_id' => $mora->id,
                'address' => 'Calema, 500 m este de la escuela',
            ],
        );

        // The meter whose QR code the plumber scans. Still in stock, unassigned.
        Meter::firstOrCreate(
            ['dev_eui' => '4c30280838323aec'],
            [
                'serial_number' => '21711354',
                'app_key' => '00112233445566778899aabbccddeeff',
                'model' => 'Medidor Ultrasónico Sonata ARAD',
                'status' => MeterStatus::InStock,
                'chirpstack_device_profile_id' => '4cbcaeff-164b-4b36-a1a8-86d8035573e3',
                'chirpstack_device_name' => 'MDA-03-5',
                'device_serial_number' => 21711354,
            ],
        );

        $sanRafael = Organization::firstOrCreate(
            ['legal_id' => '3002123456'],
            [
                'chirpstack_tenant_id' => '6f4d5a2e-1c3b-4a7d-8e9f-0a1b2c3d4e5f',
                'chirpstack_application_id' => '7a8b9c0d-1e2f-4a3b-8c5d-6e7f8a9b0c1d',
                'legal_name' => 'ASADA de San Rafael de Heredia R.L.',
                'display_name' => 'ASADA San Rafael',
                'contact_phone' => '+50622370001',
            ],
        );

        $rodriguez = Subscriber::firstOrCreate(
            ['organization_id' => $sanRafael->id, 'identification' => '402310567'],
            [
                'full_name' => 'María Rodríguez Ugalde',
                'phone' => '+50670001234',
                'email' => 'maria.rodriguez@example.com',
            ],
        );

        $instalado = Service::firstOrCreate(
            ['service_number' => 'SR-0001'],
            [
                'subscriber_id' => $rodriguez->id,
                'address' => '200 m norte de la iglesia, San Rafael de Heredia',
            ],
        );

        Service::firstOrCreate(
            ['service_number' => 'SR-0002'],
            [
                'subscriber_id' => $rodriguez->id,
                'address' => 'Costado sur del parque, San Rafael de Heredia',
                'status' => 'SUSPENDED',
            ],
        );

        // Already installed, so the lookup can show the "not assignable" case.
        Meter::firstOrCreate(
            ['dev_eui' => 'a84041fdfe1c2b40'],
            [
                'organization_id' => $sanRafael->id,
                'service_id' => $instalado->id,
                'serial_number' => 'ELS-2024-000146',
                'app_key' => 'fedcba9876543210fedcba9876543210',
                'model' => 'Elster V210 LoRaWAN',
                'status' => MeterStatus::Active,
                'chirpstack_registered' => true,
            ],
        );
    }
}
