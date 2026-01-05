<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;

class KosController extends Controller
{
    public function index()
    {
        // Ambil data kos PUTRA dengan eager loading
        $kosPutra = Kos::where('tipe_kos', 'putra')
            ->where('status', 'active')
            ->with([
                'fotoKos' => function ($query) {
                    $query->where('tipe', 'bangunan')->take(1);
                }
            ])
            ->with(['kamar.hargaSewa'])
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Ambil data kos PUTRI
        $kosPutri = Kos::where('tipe_kos', 'putri')
            ->where('status', 'active')
            ->with([
                'fotoKos' => function ($query) {
                    $query->where('tipe', 'bangunan')->take(1);
                }
            ])
            ->with(['kamar.hargaSewa'])
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Ambil data kos CAMPUR
        $kosCampur = Kos::where('tipe_kos', 'campur')
            ->where('status', 'active')
            ->with([
                'fotoKos' => function ($query) {
                    $query->where('tipe', 'bangunan')->take(1);
                }
            ])
            ->with(['kamar.hargaSewa'])
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return view('main', compact('kosPutra', 'kosPutri', 'kosCampur'));
    }

    public function show($id)
    {
        $kos = Kos::with([
            'fotoKos',
            'kamar.hargaSewa',
            'fasilitas',
            'peraturan',
            'alamatKos',
            'ulasan.user',
            'owner'
        ])->findOrFail($id);

        $semuaUlasan = $kos->ulasan;
        $ulasanDitampilkan = $semuaUlasan->take(2);

        return view('detail_kos', compact('kos', 'semuaUlasan', 'ulasanDitampilkan'));
    }

    public function search(Request $request)
    {
        $query = Kos::query()->where('status', 'active');

        // Filter pencarian teks
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_kos', 'like', "%{$searchTerm}%")
                    ->orWhere('deskripsi', 'like', "%{$searchTerm}%")
                    ->orWhere('tipe_kos', 'like', "%{$searchTerm}%");
            });
        }

        // Filter tipe kos (optional)
        if ($request->filled('tipe') && in_array($request->tipe, ['putra', 'putri', 'campur'])) {
            $query->where('tipe_kos', $request->tipe);
        }

        // Ambil data dengan eager loading
        $kosList = $query->with([
            'fotoKos' => function ($q) {
                $q->where('tipe', 'bangunan')->take(1);
            },
            'kamar.hargaSewa'
        ])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('search', compact('kosList'));
    }
}