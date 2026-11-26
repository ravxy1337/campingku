@props(['status'])

@php
    // Tahapan alur penyewaan
    $daftarTahap = ['menunggu_acc' => 'Menunggu ACC', 'siap_diambil' => 'Siap Diambil', 'sedang_disewa' => 'Disewa', 'selesai' => 'Selesai'];
    $urutanAktif = array_search($status, array_keys($daftarTahap), true);
@endphp

@if ($urutanAktif === false)
    <div class="flex items-center gap-2 rounded-lg border border-red-200/80 bg-red-50/70 px-3.5 py-2.5 text-sm font-medium text-red-700">
        <x-ikon nama="silang" kelas="h-4 w-4" />
        Pesanan {{ strtolower(\App\Models\Penyewaan::LABEL_STATUS[$status] ?? $status) }}
    </div>
@else
    <ol class="flex items-center">
        @foreach (array_values($daftarTahap) as $urutan => $labelTahap)
            <li @class(['flex items-center', 'flex-1' => ! $loop->last])>
                <div class="flex flex-col items-center gap-1.5">
                    <span @class([
                        'flex h-7 w-7 items-center justify-center rounded-lg text-xs font-semibold tabular-nums',
                        'bg-[#14532D] text-white' => $urutan < $urutanAktif || $status === 'selesai',
                        'border-2 border-[#14532D] bg-white text-[#14532D]' => $urutan === $urutanAktif && $status !== 'selesai',
                        'border border-zinc-200 bg-zinc-50 text-zinc-400' => $urutan > $urutanAktif,
                    ])>
                        @if ($urutan < $urutanAktif || $status === 'selesai')
                            <x-ikon nama="cek" kelas="h-3.5 w-3.5" />
                        @else
                            {{ $urutan + 1 }}
                        @endif
                    </span>
                    <span @class(['whitespace-nowrap text-[11px] font-medium', 'text-zinc-900' => $urutan <= $urutanAktif, 'text-zinc-400' => $urutan > $urutanAktif])>{{ $labelTahap }}</span>
                </div>
                @unless ($loop->last)
                    <span @class(['mx-2 mb-5 h-px flex-1', 'bg-[#14532D]' => $urutan < $urutanAktif, 'bg-zinc-200' => $urutan >= $urutanAktif])></span>
                @endunless
            </li>
        @endforeach
    </ol>
@endif
