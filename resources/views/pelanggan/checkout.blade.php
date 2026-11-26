@extends('layouts.dashboard')

@section('judul', 'Checkout Penyewaan')

@section('konten')
{{-- Kepala halaman --}}
<div class="mb-8">
    <a href="{{ route('alat.detail', $alatCamping) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-zinc-500 hover:text-zinc-900">
        <x-ikon nama="panah-kiri" kelas="h-4 w-4" />
        Kembali ke detail alat
    </a>
    <h1 class="mt-3 text-2xl font-bold tracking-tight text-zinc-900">Checkout Penyewaan</h1>
    <p class="mt-1 text-sm text-zinc-500">Lengkapi pembayaran dan verifikasi identitas untuk mengajukan sewa.</p>
</div>

<form action="{{ route('pelanggan.checkout.simpan', $alatCamping) }}" method="POST" enctype="multipart/form-data" class="grid gap-8 lg:grid-cols-12">
    @csrf
    <input type="hidden" name="tanggal_sewa" value="{{ $tanggalSewa }}">
    <input type="hidden" name="tanggal_kembali" value="{{ $tanggalKembali }}">
    <input type="hidden" name="jumlah" value="{{ $jumlah }}">

    <div class="space-y-6 lg:col-span-7">
        {{-- Langkah pembayaran --}}
        <section class="kartu p-6">
            <div class="flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-900 text-sm font-semibold text-white">1</span>
                <div>
                    <h2 class="font-semibold text-zinc-900">Pembayaran</h2>
                    <p class="text-xs text-zinc-500">Pilih DP atau lunas, lalu unggah bukti transfer.</p>
                </div>
            </div>

            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                @foreach (['lunas' => ['Bayar Lunas', '100% dari total', $totalHarga], 'dp' => ['Bayar DP', '50% sekarang, sisa di toko', $totalHarga * 0.5]] as $nilaiTipe => [$judulTipe, $ketTipe, $nominalTipe])
                    <label class="relative cursor-pointer">
                        <input type="radio" name="tipe_pembayaran" value="{{ $nilaiTipe }}" data-nominal="@rupiah($nominalTipe)" @checked(old('tipe_pembayaran', 'lunas') === $nilaiTipe) class="peer sr-only">
                        <div class="h-full rounded-2xl p-4 ring-1 ring-stone-200 transition peer-checked:bg-emerald-50/50 peer-checked:ring-2 peer-checked:ring-emerald-900">
                            <p class="text-sm font-semibold text-zinc-900">{{ $judulTipe }}</p>
                            <p class="text-xs text-zinc-500">{{ $ketTipe }}</p>
                            <p class="mt-3 text-lg font-bold text-zinc-900">@rupiah($nominalTipe)</p>
                        </div>
                        <span class="absolute right-4 top-4 hidden h-5 w-5 items-center justify-center rounded-full bg-emerald-900 text-white peer-checked:flex"><x-ikon nama="cek" kelas="h-3 w-3" /></span>
                    </label>
                @endforeach
            </div>

            <div class="mt-5 flex items-start gap-3 rounded-xl bg-stone-50 p-4 text-sm ring-1 ring-stone-200">
                <x-ikon nama="kartu" kelas="mt-0.5 h-5 w-5 shrink-0 text-zinc-500" />
                <div class="space-y-0.5 text-zinc-600">
                    <p class="font-medium text-zinc-900">Transfer ke rekening toko</p>
                    <p>BCA <span class="font-mono text-zinc-900">123 456 7890</span> &middot; Mandiri <span class="font-mono text-zinc-900">987 654 3210</span></p>
                    <p class="text-xs">a.n. RimbaCamp Outdoor</p>
                </div>
            </div>

            <div class="mt-5">
                <x-unggah-berkas nama="bukti_bayar" label="Bukti transfer" />
            </div>
        </section>

        {{-- Langkah identitas KTP --}}
        <section class="kartu p-6">
            <div class="flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-900 text-sm font-semibold text-white">2</span>
                <div>
                    <h2 class="font-semibold text-zinc-900">Identitas Jaminan</h2>
                    <p class="text-xs text-zinc-500">Masukkan NIK dan unggah foto KTP asli Anda.</p>
                </div>
            </div>

            <div class="mt-6">
                <div class="flex items-center justify-between">
                    <label for="nik" class="label-input">NIK (16 digit)</label>
                    <span id="hitung-nik" class="mb-1.5 font-mono text-xs text-zinc-400">0/16</span>
                </div>
                <div class="flex gap-2">
                    <input type="text" id="nik" name="nik" inputmode="numeric" maxlength="16" autocomplete="off" value="{{ old('nik', auth()->user()->nik) }}" required placeholder="3204xxxxxxxxxxxx" class="kolom-input font-mono tracking-widest">
                    <button type="button" id="tombol-cek-nik" disabled class="tombol-gelap shrink-0">
                        <svg id="putar-nik" class="hidden h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-opacity=".25" stroke-width="3"/><path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
                        <span id="teks-cek-nik">Cek NIK</span>
                    </button>
                </div>

                {{-- Hasil pratinjau API NIK --}}
                <div id="hasil-nik" class="mt-4 hidden overflow-hidden rounded-2xl ring-1">
                    <div id="kepala-hasil-nik" class="flex items-center gap-2 px-4 py-3 text-sm font-semibold"></div>
                    <dl id="isi-hasil-nik" class="hidden grid-cols-2 gap-px bg-stone-200 sm:grid-cols-3"></dl>
                </div>
            </div>

            <div class="mt-6">
                <x-unggah-berkas nama="foto_ktp_jaminan" label="Foto KTP asli" keterangan="Hanya KTP yang diterima sebagai jaminan. NIK yang Anda masukkan wajib sama dengan NIK pada foto KTP." />
            </div>
        </section>

        {{-- Langkah persetujuan --}}
        <section class="kartu p-6">
            <div class="flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-900 text-sm font-semibold text-white">3</span>
                <h2 class="font-semibold text-zinc-900">Persetujuan</h2>
            </div>
            <div class="mt-5 space-y-3">
                @foreach (['setuju_ambil_di_toko' => ['Ambil sendiri di toko', 'Saya akan mengambil dan mengembalikan alat langsung di toko (self-pickup).'], 'setuju_jaminan_ktp' => ['Serahkan KTP fisik asli', 'Saya bersedia menyerahkan KTP asli sesuai NIK di atas untuk ditahan selama masa sewa.']] as $namaSetuju => [$judulSetuju, $isiSetuju])
                    <label class="flex cursor-pointer items-start gap-3 rounded-xl p-4 ring-1 ring-stone-200 transition has-[:checked]:bg-emerald-50/50 has-[:checked]:ring-emerald-900">
                        <input type="checkbox" name="{{ $namaSetuju }}" value="1" required @checked(old($namaSetuju)) class="mt-0.5 h-4 w-4 rounded border-stone-300 accent-emerald-900">
                        <span>
                            <span class="block text-sm font-medium text-zinc-900">{{ $judulSetuju }}</span>
                            <span class="block text-xs text-zinc-500">{{ $isiSetuju }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
        </section>
    </div>

    {{-- Ringkasan pesanan --}}
    <aside class="lg:col-span-5">
        <div class="kartu overflow-hidden lg:sticky lg:top-24">
            <x-gambar-alat :alat="$alatCamping" kelas="aspect-[16/9]" />
            <div class="p-6">
                <p class="text-xs font-medium uppercase tracking-wider text-emerald-900">{{ $alatCamping->kategori->nama_kategori }}</p>
                <h2 class="mt-1 font-semibold text-zinc-900">{{ $alatCamping->nama_alat }}</h2>

                <div class="mt-5 grid grid-cols-2 gap-3">
                    <div class="rounded-xl bg-stone-50 p-3 ring-1 ring-stone-200">
                        <p class="text-xs text-zinc-500">Ambil</p>
                        <p class="mt-0.5 text-sm font-semibold text-zinc-900">{{ \Carbon\Carbon::parse($tanggalSewa)->translatedFormat('d M Y') }}</p>
                    </div>
                    <div class="rounded-xl bg-stone-50 p-3 ring-1 ring-stone-200">
                        <p class="text-xs text-zinc-500">Kembali</p>
                        <p class="mt-0.5 text-sm font-semibold text-zinc-900">{{ \Carbon\Carbon::parse($tanggalKembali)->translatedFormat('d M Y') }}</p>
                    </div>
                </div>

                <dl class="mt-5 space-y-3 text-sm">
                    <div class="flex justify-between"><dt class="text-zinc-500">Tarif harian</dt><dd class="text-zinc-900">@rupiah($alatCamping->harga_per_hari)</dd></div>
                    <div class="flex justify-between"><dt class="text-zinc-500">Durasi &times; jumlah</dt><dd class="text-zinc-900">{{ $durasiHari }} hari &times; {{ $jumlah }} unit</dd></div>
                    <div class="flex justify-between border-t border-stone-200 pt-3"><dt class="font-medium text-zinc-900">Total sewa</dt><dd class="font-semibold text-zinc-900">@rupiah($totalHarga)</dd></div>
                    <div class="flex items-center justify-between rounded-xl bg-emerald-900 px-4 py-3 text-white"><dt class="text-sm">Bayar sekarang</dt><dd id="bayar-sekarang" class="text-lg font-bold">@rupiah(old('tipe_pembayaran') === 'dp' ? $totalHarga * 0.5 : $totalHarga)</dd></div>
                </dl>

                <button type="submit" class="tombol-utama mt-6 w-full py-3.5">
                    Ajukan Penyewaan
                    <x-ikon nama="panah-kanan" kelas="h-4 w-4" />
                </button>
                <p class="mt-3 text-center text-xs text-zinc-500">Pesanan akan diverifikasi oleh petugas toko.</p>
            </div>
        </div>
    </aside>
</form>
@endsection

@push('skrip')
<script>
    // Perbarui nominal bayar sesuai tipe pembayaran
    document.querySelectorAll('input[name="tipe_pembayaran"]').forEach((pilihan) => {
        pilihan.addEventListener('change', () => document.getElementById('bayar-sekarang').textContent = pilihan.dataset.nominal);
    });

    // Elemen cek NIK
    const masukanNik = document.getElementById('nik');
    const tombolNik = document.getElementById('tombol-cek-nik');
    const hasilNik = document.getElementById('hasil-nik');
    const kepalaHasil = document.getElementById('kepala-hasil-nik');
    const isiHasil = document.getElementById('isi-hasil-nik');

    // Batasi input NIK hanya angka
    const perbaruiNik = () => {
        masukanNik.value = masukanNik.value.replace(/\D/g, '').slice(0, 16);
        document.getElementById('hitung-nik').textContent = `${masukanNik.value.length}/16`;
        tombolNik.disabled = masukanNik.value.length !== 16;
    };
    masukanNik.addEventListener('input', perbaruiNik);
    perbaruiNik();

    // Tampilkan status hasil cek NIK
    const tampilkanHasil = (berhasil, pesan, data = null) => {
        hasilNik.classList.remove('hidden');
        hasilNik.className = `mt-4 overflow-hidden rounded-2xl ring-1 ${berhasil ? 'ring-emerald-900/20' : 'ring-red-600/20'}`;
        kepalaHasil.className = `flex items-center gap-2 px-4 py-3 text-sm font-semibold ${berhasil ? 'bg-emerald-50 text-emerald-900' : 'bg-red-50 text-red-700'}`;
        kepalaHasil.textContent = pesan;
        isiHasil.replaceChildren();
        isiHasil.classList.toggle('hidden', !data);
        isiHasil.classList.toggle('grid', !!data);
        if (!data) return;
        const label = { jenis_kelamin: 'Jenis Kelamin', tanggal_lahir: 'Tanggal Lahir', provinsi: 'Provinsi', kota_kabupaten: 'Kota/Kabupaten', kecamatan: 'Kecamatan', kode_pos: 'Kode Pos' };
        Object.entries(label).forEach(([kunci, judul]) => {
            const sel = document.createElement('div');
            sel.className = 'bg-white px-4 py-3';
            sel.innerHTML = '<dt class="text-xs text-zinc-500"></dt><dd class="mt-0.5 text-sm font-medium text-zinc-900"></dd>';
            sel.querySelector('dt').textContent = judul;
            sel.querySelector('dd').textContent = data[kunci] ?? '-';
            isiHasil.appendChild(sel);
        });
    };

    // Kirim AJAX ke route cek NIK internal
    tombolNik.addEventListener('click', async () => {
        tombolNik.disabled = true;
        document.getElementById('putar-nik').classList.remove('hidden');
        document.getElementById('teks-cek-nik').textContent = 'Memeriksa';
        try {
            const respon = await fetch(@json(route('pelanggan.cek-nik')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ nik: masukanNik.value }),
            });
            const hasil = await respon.json();
            tampilkanHasil(!!hasil.berhasil, hasil.pesan ?? hasil.message ?? 'NIK tidak valid.', hasil.berhasil ? hasil.data : null);
        } catch (galat) {
            tampilkanHasil(false, 'Gagal terhubung ke server. Periksa koneksi Anda.');
        } finally {
            document.getElementById('putar-nik').classList.add('hidden');
            document.getElementById('teks-cek-nik').textContent = 'Cek NIK';
            tombolNik.disabled = masukanNik.value.length !== 16;
        }
    });
</script>
@endpush
