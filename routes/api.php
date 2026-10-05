<?php

use App\Http\Controllers\Api\ChirpstackUplinkController;
use App\Http\Controllers\Api\MeterController;
use App\Http\Controllers\Api\MeterReadingController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::apiResource('organizations', OrganizationController::class)
    ->except('destroy');

Route::apiResource('subscribers', SubscriberController::class)
    ->except('destroy');

Route::apiResource('services', ServiceController::class)
    ->except('destroy');

Route::apiResource('meters', MeterController::class)
    ->except('destroy');

// Readings are an append-only telemetry log: they are read, never edited.
Route::get('meter-readings', [MeterReadingController::class, 'index'])
    ->name('meter-readings.index');

Route::get('meter-readings/{meterReading}', [MeterReadingController::class, 'show'])
    ->name('meter-readings.show');

Route::get('meters/{meter}/readings', [MeterReadingController::class, 'forMeter'])
    ->name('meters.readings.index');

// Where the ChirpStack HTTP integration posts its events.
Route::post('chirpstack/uplink', ChirpstackUplinkController::class)
    ->name('chirpstack.uplink');
