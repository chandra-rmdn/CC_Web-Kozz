<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use App\Models\Booking;
use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    // Store new review
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kos_id' => 'required|exists:kos,id',
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string|max:1000'
        ]);

        // CEK: Jika mode EDIT (ada ulasan_id di request)
        if ($request->has('ulasan_id') && $request->ulasan_id) {
            // Ini mode EDIT, cari ulasan yang mau diedit
            $ulasan = Ulasan::where('id', $request->ulasan_id)
                        ->where('user_id', Auth::id())
                        ->first();
            if (!$ulasan) {
                return response()->json([
                    'success' => false,
                    'error' => 'Ulasan tidak ditemukan'
                ], 404);
            }
            
            // Update ulasan yang sudah ada
            $ulasan->update([
                'rating' => $request->rating,
                'komentar' => $request->ulasan ?? null
            ]);
            
            $message = 'Ulasan berhasil diupdate';
            
        } else {
            // Ini mode ADD BARU, cek duplikat
            $existing = Ulasan::where('user_id', Auth::id())
                ->where('kos_id', $request->kos_id)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'error' => 'Anda sudah memberikan ulasan untuk kos ini'
                ], 400);
            }

            // Buat ulasan baru
            $ulasan = Ulasan::create([
                'user_id' => Auth::id(),
                'kos_id' => $request->kos_id,
                'rating' => $request->rating,
                'komentar' => $request->ulasan ?? null
            ]);
            
            $message = 'Ulasan berhasil disimpan';
        }

        $ulasan->load('kos');

        // Update rating rata-rata kos
        $averageRating = Ulasan::where('kos_id', $request->kos_id)->avg('rating');
        Kos::where('id', $request->kos_id)->update([
            'mean_rating' => round($averageRating, 2)
        ]);

        return response()->json([
            'success' => true,
            'message' => $message,
            'ulasan' => $ulasan,
            'kosName' => $ulasan->kos->nama_kos ?? 'Kos' // Tambah ini
        ]);
    }

    // Update average rating for a kos
    private function updateKosRating($kosId)
    {
        $averageRating = Ulasan::where('kos_id', $kosId)
            ->where('status', 'active')
            ->avg('rating');

        Kos::where('id', $kosId)->update([
            'mean_rating' => round($averageRating, 2)
        ]);
    }

    // Get reviews for a kos (public)
    public function getByKos($kosId)
    {
        $ulasan = Ulasan::with('user')
            ->where('kos_id', $kosId)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'ulasan' => $ulasan
        ]);
    }

    // Get user's reviews
    public function getUserReviews()
    {
        $ulasan = Ulasan::with('kos')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'ulasan' => $ulasan
        ]);
    }

    // Get detail ulasan untuk edit
    public function show($id)
    {
        $ulasan = Ulasan::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        return response()->json([
            'success' => true,
            'ulasan' => $ulasan
        ]);
    }

    // Update ulasan
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string|max:1000'
        ]);

        // Cari ulasan milik user
        $ulasan = Ulasan::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        // Update
        $ulasan->update([
            'rating' => $request->rating,
            'komentar' => $request->ulasan ?? null
        ]);

        // Update rating rata-rata kos
        $averageRating = Ulasan::where('kos_id', $ulasan->kos_id)->avg('rating');
        Kos::where('id', $ulasan->kos_id)->update([
            'mean_rating' => round($averageRating, 2)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil diupdate',
            'ulasan' => $ulasan
        ]);
    }
}