@props(['status'])

@php
    // Tahapan alur penyewaan
    $daftarTahap = ['menunggu_acc' => 'Menunggu ACC', 'siap_diambil' => 'Siap Diambil', 'sedang_disewa' => 'Disewa', 'selesai' => 'Selesai'];
    $urutanAktif = array_search($status, array_keys($daftarTahap), true);
@endphp

@if ($urutanAktif === false)
    <div class="flex items-center gap-2 rounded-xl bg-red-50 px-4 py-3 text-sm font-medium text-red-700 ring-1 ring-inset ring-red-600/10">
        <x-ikon nama="silang" kelas="h-4 w-4" />
        Pesanan {{ strtolower(\App\Models\Penyewaan::LABEL_STATUS[$status]) }}
    </div>
@else
    <ol class="flex items-center">
        @foreach (array_values($daftarTahap) as $urutan => $labelTahap)
            <li @class(['flex items-center', 'flex-1' => ! $loop->last])>
                <div class="flex flex-col items-center gap-1.5">
                    <span @class([
                        'flex h-7 w-7 items-center justify-center rounded-full text-xs font-semibold',
                        'bg-emerald-900 text-white' => $urutan < $urutanAktif || $status === 'selesai',
                        'bg-white text-emerald-900 ring-2 ring-emerald-900' => $urutan === $urutanAktif && $status !== 'selesai',
                        'bg-stone-100 text-zinc-400 ring-1 ring-stone-200' => $urutan > $urutanAktif,
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
                    <span @class(['mx-2 mb-5 h-0.5 flex-1 rounded-full', 'bg-emerald-900' => $urutan < $urutanAktif, 'bg-stone-200' => $urutan >= $urutanAktif])></span>
                @endunless
            </li>
        @endforeach
    </ol>
@endif

