<?php

use App\Http\Controllers\SpaController;
use Illuminate\Support\Facades\Route;

// El SPA se sirve desde el mismo origen que el API: Caddy entrega los ficheros
// que existen (index.html, /assets/*) y todo lo demás llega hasta aquí.
// El fallback cubre las rutas de React Router (/meters, /subscribers/3, ...).
Route::get('/', SpaController::class)->name('spa');

Route::fallback(SpaController::class);
