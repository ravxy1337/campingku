@extends('layouts.dashboard')

@section('judul', 'Verifikasi '.$penyewaan->kode_booking)

@section('konten')
@php
    $pelanggan = $penyewaan->pelanggan;
    $sisaBayar = max(0, $penyewaan->total_harga - $penyewaan->jumlah_dibayar);
    $nikValid = $dataApiNik['berhasil'] ?? false;
    $dataWilayah = $nikValid ? $dataApiNik['data'] : [];
@endphp

<a href="{{ route('petugas.antrean') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-zinc-500 transition hover:text-zinc-900">
    <x-ikon nama="panah-kiri" kelas="h-3.5 w-3.5" />
    Kembali ke antrean
</a>

<div class="mt-4 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
    <div class="flex items-center gap-3.5">
        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-zinc-900 text-base font-semibold text-white">
            {{ strtoupper(substr($pelanggan->nama_lengkap, 0, 1)) }}
        </span>
        <div>
            <h2 class="text-lg font-semibold tracking-tight text-zinc-900">{{ $pelanggan->nama_lengkap }}</h2>
            <p class="mt-0.5 flex flex-wrap items-center gap-x-3 text-xs text-zinc-500">
                <span class="font-mono text-zinc-700">{{ $penyewaan->kode_booking }}</span>
                <span class="tabular-nums">{{ $pelanggan->no_hp }}</span>
                <span>{{ $pelanggan->email }}</span>
            </p>
        </div>
    </div>
    <div class="flex flex-wrap gap-2">
        <x-lencana-status :status="$penyewaan->status_sewa" />
        <x-lencana-status :status="$penyewaan->status_jaminan_ktp" />
    </div>
</div>

<div class="kartu mt-6 p-6">
    <x-alur-sewa :status="$penyewaan->status_sewa" />
</div>

{{-- Perbandingan dokumen dan data NIK berdampingan --}}
<div class="mt-6 grid gap-6 lg:grid-cols-3">
    @foreach (['bukti_bayar' => ['Bukti Pembayaran', 'kartu'], 'foto_ktp_jaminan' => ['Foto KTP Jaminan', 'identitas']] as $kolomBerkas => [$judulBerkas, $ikonBerkas])
        <section class="kartu overflow-hidden">
            <div class="flex items-center justify-between border-b border-zinc-200/80 px-5 py-3.5">
                <h3 class="flex items-center gap-2 text-sm font-semibold text-zinc-900">
                    <x-ikon :nama="$ikonBerkas" kelas="h-4 w-4 text-zinc-400" />
                    {{ $judulBerkas }}
                </h3>
                @if ($penyewaan->$kolomBerkas)
                    <a href="{{ asset('storage/'.$penyewaan->$kolomBerkas) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-medium text-[#14532D] hover:underline">
                        <x-ikon nama="mata" kelas="h-3.5 w-3.5" />
                        Perbesar
                    </a>
                @endif
            </div>
            <div class="flex aspect-[4/3] items-center justify-center bg-[#F4F4F5] p-3">
                @if ($penyewaan->$kolomBerkas)
                    <img src="{{ asset('storage/'.$penyewaan->$kolomBerkas) }}" alt="{{ $judulBerkas }}" class="max-h-full rounded-lg object-contain">
                @else
                    <p class="text-sm text-zinc-400">Berkas tidak tersedia</p>
                @endif
            </div>
        </section>
    @endforeach

    <section class="kartu overflow-hidden">
        <div class="flex items-center justify-between border-b border-zinc-200/80 px-5 py-3.5">
            <h3 class="flex items-center gap-2 text-sm font-semibold text-zinc-900">
                <x-ikon nama="lokasi" kelas="h-4 w-4 text-zinc-400" />
                Data NIK (parse1337)
            </h3>
            <span @class([
                'rounded-md border px-2 py-0.5 text-[11px] font-medium',
                'border-emerald-200/80 bg-emerald-50/70 text-[#14532D]' => $nikValid,
                'border-red-200/80 bg-red-50/70 text-red-700' => ! $nikValid,
            ])>
                {{ $nikValid ? 'Valid' : 'Tidak valid' }}
            </span>
        </div>
        <div class="p-5">
            <p class="text-xs text-zinc-500">NIK yang diinput</p>
            <p class="mt-1 rounded-lg border border-zinc-200 bg-[#F7F8F7] px-3 py-2 font-mono text-sm tracking-widest text-zinc-900">{{ $pelanggan->nik ?? '-' }}</p>

            @if ($nikValid)
                <dl class="mt-4 divide-y divide-zinc-100 text-sm">
                    @foreach (['jenis_kelamin' => 'Jenis kelamin', 'tanggal_lahir' => 'Tanggal lahir', 'provinsi' => 'Provinsi', 'kota_kabupaten' => 'Kota/Kab.', 'kecamatan' => 'Kecamatan', 'kode_pos' => 'Kode pos'] as $kunciWilayah => $labelWilayah)
                        <div class="flex justify-between gap-4 py-2">
                            <dt class="text-zinc-500">{{ $labelWilayah }}</dt>
                            <dd class="text-right font-medium text-zinc-900 tabular-nums">{{ $dataWilayah[$kunciWilayah] ?? '-' }}</dd>
                        </div>
                    @endforeach
                </dl>
            @else
                <div class="mt-4 flex items-start gap-2 rounded-lg border border-red-200/80 bg-red-50/60 p-3 text-xs text-red-700">
                    <x-ikon nama="peringatan" kelas="h-4 w-4 shrink-0" />
                    {{ $dataApiNik['pesan'] ?? 'Pelanggan belum mengisi NIK.' }}
                </div>
            @endif
        </div>
    </section>
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-3">
    {{-- Rincian pesanan --}}
    <section class="kartu p-6 lg:col-span-1">
        <h3 class="text-sm font-semibold text-zinc-900">Rincian Pesanan</h3>
        <div class="mt-4 space-y-4">
            @foreach ($penyewaan->detailPenyewaan as $detail)
                <div>
                    <p class="text-sm font-medium text-zinc-900">{{ $detail->alat->nama_alat ?? '-' }}</p>
                    <p class="text-xs text-zinc-500 tabular-nums">{{ $detail->jumlah }} unit &times; @rupiah($detail->harga_satuan)</p>
                    <p class="mt-2 text-xs leading-relaxed text-zinc-500">{{ $detail->alat->kelengkapan_standar ?? '' }}</p>
                </div>
            @endforeach
        </div>
        <dl class="mt-5 space-y-2.5 border-t border-zinc-200 pt-4 text-sm">
            <div class="flex justify-between">
                <dt class="text-zinc-500">Jadwal</dt>
                <dd class="text-zinc-900 tabular-nums">{{ $penyewaan->tanggal_sewa->locale('id')->translatedFormat('d M') }} &ndash; {{ $penyewaan->tanggal_kembali->locale('id')->translatedFormat('d M Y') }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-zinc-500">Total</dt>
                <dd class="font-semibold text-zinc-900 tabular-nums">@rupiah($penyewaan->total_harga)</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-zinc-500">Dibayar ({{ strtoupper($penyewaan->tipe_pembayaran) }})</dt>
                <dd class="font-semibold text-[#14532D] tabular-nums">@rupiah($penyewaan->jumlah_dibayar)</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-zinc-500">Sisa</dt>
                <dd @class(['font-semibold tabular-nums', 'text-red-700' => $sisaBayar > 0, 'text-zinc-900' => $sisaBayar == 0])>@rupiah($sisaBayar)</dd>
            </div>
        </dl>
    </section>

    {{-- Panel aksi petugas --}}
    <section class="kartu p-6 lg:col-span-2">
        <h3 class="text-sm font-semibold text-zinc-900">Tindakan Petugas</h3>

        @if ($penyewaan->status_sewa === 'menunggu_acc')
            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <form action="{{ route('petugas.verifikasi.setujui', $penyewaan) }}" method="POST" class="flex flex-col rounded-xl border border-emerald-200/80 bg-emerald-50/40 p-5">
                    @csrf
                    @method('PATCH')
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#14532D] text-white">
                        <x-ikon nama="cek" kelas="h-4 w-4" />
                    </span>
                    <p class="mt-4 font-semibold text-zinc-900">ACC Pesanan</p>
                    <p class="mt-1 flex-1 text-sm text-zinc-600">Bukti bayar valid dan NIK sesuai foto KTP. Status menjadi Siap Diambil.</p>
                    <button type="submit" class="tombol-utama mt-5 w-full">Setujui Pesanan</button>
                </form>

                <form action="{{ route('petugas.verifikasi.tolak', $penyewaan) }}" method="POST" class="flex flex-col rounded-xl border border-red-200/80 bg-red-50/40 p-5">
                    @csrf
                    @method('PATCH')
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-700 text-white">
                        <x-ikon nama="silang" kelas="h-4 w-4" />
                    </span>
                    <p class="mt-4 font-semibold text-zinc-900">Tolak Pesanan</p>
                    <textarea name="catatan_penolakan" rows="2" required minlength="5" placeholder="Tulis alasan penolakan..." class="kolom-input mt-3 flex-1">{{ old('catatan_penolakan') }}</textarea>
                    <button type="submit" class="tombol-bahaya mt-3 w-full">Tolak Pesanan</button>
                </form>
            </div>
        @elseif ($penyewaan->status_sewa === 'siap_diambil')
            <form action="{{ route('petugas.verifikasi.serah-terima', $penyewaan) }}" method="POST" class="mt-4 rounded-xl border border-zinc-200 bg-[#F7F8F7] p-5">
                @csrf
                @method('PATCH')
                <div class="flex items-start gap-4">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#14532D] text-white">
                        <x-ikon nama="serah" kelas="h-4 w-4" />
                    </span>
                    <div>
                        <p class="font-semibold text-zinc-900">Serah Terima di Toko</p>
                        <ul class="mt-2 space-y-1.5 text-sm text-zinc-600">
                            <li class="flex items-center gap-2"><x-ikon nama="cek" kelas="h-4 w-4 text-[#14532D]" /> Cocokkan KTP fisik dengan NIK di atas, lalu tahan di toko</li>
                            <li class="flex items-center gap-2"><x-ikon nama="cek" kelas="h-4 w-4 text-[#14532D]" /> Terima pelunasan sebesar <strong class="text-zinc-900 tabular-nums">@rupiah($sisaBayar)</strong></li>
                            <li class="flex items-center gap-2"><x-ikon nama="cek" kelas="h-4 w-4 text-[#14532D]" /> Serahkan alat beserta kelengkapan standar</li>
                        </ul>
                    </div>
                </div>
                <button type="submit" class="tombol-utama mt-5 w-full sm:w-auto">Konfirmasi Serah Terima</button>
            </form>
        @elseif ($penyewaan->status_sewa === 'sedang_disewa')
            <form action="{{ route('petugas.verifikasi.pengembalian', $penyewaan) }}" method="POST" class="mt-4 rounded-xl border border-zinc-200 bg-[#F7F8F7] p-5">
                @csrf
                @method('PATCH')
                <div class="flex items-start gap-4">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-zinc-900 text-white">
                        <x-ikon nama="kembali" kelas="h-4 w-4" />
                    </span>
                    <div class="flex-1">
                        <p class="font-semibold text-zinc-900">Pengembalian di Toko</p>
                        <p class="mt-1 text-sm text-zinc-600">Periksa kelengkapan alat, lalu kembalikan KTP fisik kepada pelanggan. Tanggal kembali aktual dicatat hari ini.</p>
                        <label for="catatan_kondisi" class="label-input mt-4">Catatan kondisi alat</label>
                        <input type="text" id="catatan_kondisi" name="catatan_kondisi" value="{{ old('catatan_kondisi', 'Lengkap dan kondisi baik') }}" class="kolom-input">
                    </div>
                </div>
                <button type="submit" class="tombol-gelap mt-5 w-full sm:w-auto">Konfirmasi Pengembalian</button>
            </form>
        @else
            <div class="mt-4 rounded-xl border border-zinc-200 bg-[#F7F8F7] p-5 text-sm text-zinc-600">
                <p>Transaksi telah berstatus <strong class="text-zinc-900">{{ \App\Models\Penyewaan::LABEL_STATUS[$penyewaan->status_sewa] }}</strong>{{ $penyewaan->petugas ? ' oleh '.$penyewaan->petugas->nama_lengkap : '' }}.</p>
                @if ($penyewaan->catatan_penolakan)
                    <p class="mt-2 text-red-700">Alasan penolakan: {{ $penyewaan->catatan_penolakan }}</p>
                @endif
                @if ($penyewaan->tanggal_dikembalikan_aktual)
                    <p class="mt-2">Dikembalikan pada {{ $penyewaan->tanggal_dikembalikan_aktual->locale('id')->translatedFormat('d F Y') }}. {{ $penyewaan->catatan_kondisi }}</p>
                @endif
            </div>
        @endif
    </section>
</div>
@endsection
