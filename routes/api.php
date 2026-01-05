<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::post('/save-local-kos', function(Request $request) {
    // Debug: lihat apa yang dikirim
    \Log::info('Data dari JavaScript:', $request->all());
    
    return response()->json([
        'success' => true,
        'message' => 'API berhasil diakses!',
        'data' => $request->all()
    ]);
});

