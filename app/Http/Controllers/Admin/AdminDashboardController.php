<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kos;
use App\Models\Booking;
use App\Models\Kontrak;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect('/')->with('error', 'Login dulu!');
        }

        // 1. Data dashboard lama
        $totalKos = Kos::where('user_id', $user->id)->count();

        $totalBookingMenunggu = Booking::whereHas('kos', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', 'menunggu_konfirmasi')
            ->count();

        $totalKontrakAktif = Kontrak::where('status', 'aktif')
            ->whereHas('booking.kos', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->count();

        $kosList = Kos::with('alamatKos', 'kamar')
            ->where('user_id', $user->id)
            ->get();

        // 2. Pendapatan: dihitung dari pembayaran (TANPA tabel pendapatan baru)
        $kosIds = $kosList->pluck('id'); // semua kos milik user ini

        // pendapatan total
        $totalRevenue = Pembayaran::where('status', 'success')
            ->whereHas('booking.kos', function ($q) use ($kosIds) {
                $q->whereIn('id', $kosIds);
            })
            ->sum('total_bayar');

        // pendapatan bulan ini
        $monthlyRevenue = Pembayaran::where('status', 'success')
            ->whereHas('booking.kos', function ($q) use ($kosIds) {
                $q->whereIn('id', $kosIds);
            })
            ->whereMonth('tanggal_bayar', now()->month)
            ->whereYear('tanggal_bayar', now()->year)
            ->sum('total_bayar');

        return view('admin.atmin', compact(
            'user',
            'totalKos',
            'totalBookingMenunggu',
            'totalKontrakAktif',
            'kosList',
            'monthlyRevenue',
            'totalRevenue',
        ));
    }
}
