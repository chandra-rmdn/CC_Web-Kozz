    @extends('layouts.app') <!-- atau layout utama kamu -->

    @section('content')
    <section class="bg-white rounded-t-[20px] shadow-sm px-4 md:px-[102px] pt-8 md:pt-[41px]">
        <div class="flex items-center justify-between mb-2 md:mb-[28px]">
            <h2 class="text-black font-semibold text-[18px]">
                Hasil Pencarian: "{{ request('q') }}"
            </h2>
            <span class="text-sm text-gray-500">{{ $kosList->total() }} hasil ditemukan</span>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-[25px]">
            @forelse($kosList as $kos)
                <!-- Card yang sama dengan halaman utama -->
                <a href="{{ route('detail.kos', $kos->id) }}" class="block">
                    <article class="rounded-[20px]">
                        <div class="w-full h-40 md:h-[162px] bg-gray-100 rounded-[20px] mb-3">
                            @if($kos->fotoKos->count() > 0)
                                @php $foto = $kos->fotoKos->first(); @endphp
                                @if(str_starts_with($foto->path_foto, '/9j/') || str_starts_with($foto->path_foto, 'data:image'))
                                    <img src="data:image/jpeg;base64,{{ $foto->path_foto }}" 
                                        alt="{{ $kos->nama_kos }}"
                                        class="w-full h-full object-cover rounded-[20px]" />
                                @endif
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-purple-100 to-blue-100 flex items-center justify-center rounded-[20px]">
                                    <i class="bi bi-house text-[#5C00CC] text-4xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex justify-between items-center text-[11px]">
                            <h3 class="text-black text-sm font-semibold mb-1">{{ $kos->nama_kos }}</h3>
                            <span class="text-gray-500">★ {{ number_format($kos->mean_rating, 1) }}</span>
                        </div>
                        
                        @php
                            $hargaTerendah = null;
                            foreach ($kos->kamar as $kamar) {
                                if ($kamar->hargaSewa->count() > 0) {
                                    $harga = $kamar->hargaSewa->first()->harga;
                                    if ($hargaTerendah === null || $harga < $hargaTerendah) {
                                        $hargaTerendah = $harga;
                                    }
                                }
                            }
                        @endphp
                        
                        <p class="text-xs text-gray-600 mb-1">
                            @if($hargaTerendah)
                                Rp. {{ number_format($hargaTerendah, 0, ',', '.') }}/bulan
                            @else
                                Harga mulai dari...
                            @endif
                        </p>
                        
                        <div class="flex justify-between items-center text-[11px] text-gray-500">
                            @if($kos->kamar_tersedia == 0)
                                <span class="text-red-500 font-medium">Penuh</span>
                            @elseif($kos->kamar_tersedia <= 3)
                                <span>Sisa {{ $kos->kamar_tersedia }} kamar</span>
                            @else
                                <span>{{ $kos->kamar_tersedia }} Kamar tersedia</span>
                            @endif
                        </div>
                    </article>
                </a>
            @empty
                <div class="col-span-4 text-center py-8 text-gray-500">
                    <p>Tidak ditemukan kos dengan kata kunci "{{ request('q') }}"</p>
                    <a href="{{ url('/') }}" class="text-[#5C00CC] mt-2 inline-block">Kembali ke beranda</a>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($kosList->hasPages())
            <div class="mt-8">
                {{ $kosList->links() }}
            </div>
        @endif
    </section>
    @endsection