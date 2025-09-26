@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <!-- Alert Benturan Keras / Terbaring -->
    <div id="daruratAlert" class="alert alert-danger text-center fw-bold fs-5 rounded-3 shadow d-none">
        🚨 PERINGATAN DARURAT! — Terjadi <u>Benturan Keras / Pekerja Terbaring</u>!
    </div>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">📋 Monitoring Helm Pekerja</h3>
    </div>

    <!-- Tombol Tambah & Riwayat -->
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('pekerjas.create') }}" class="btn btn-primary shadow-sm">➕ Tambah Helm</a>
        <a href="{{ route('pekerjas.riwayat') }}" class="btn btn-outline-secondary shadow-sm">📜 Lihat Riwayat</a>
    </div>

    <!-- Tabel -->
    <div class="table-responsive">
        <table class="table table-hover align-middle text-center shadow-sm rounded">
            <thead class="table-dark text-light">
                <tr>
                    <th>Device ID</th>
                    <th>Status Helm</th>
                    <th>Kondisi Pekerja</th>
                    <th>Keadaan Pekerja</th>
                    <th>Lokasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="pekerjaTableBody">
                @foreach ($latestPekerjas as $p)
<tr id="row-{{ $p->id }}" class="{{ ($p->kondisi_pekerja === 'Benturan_Keras' || $p->status_terbaring === 'Terbaring') ? 'table-danger' : '' }}">
    <td class="fw-semibold">{{ $p->device_id }}</td>
    <td>
        <span class="badge bg-{{ $p->status_helm === 'Helm_Terpakai' ? 'success' : 'warning' }}">
            {{ $p->status_helm }}
        </span>
    </td>
    <td>
        <span class="badge bg-{{ $p->kondisi_pekerja === 'Benturan_Ringan' ? 'info' : ($p->kondisi_pekerja === 'Benturan_Sedang' ? 'warning text-dark' : 'danger') }}">
            {{ $p->kondisi_pekerja }}
        </span>
    </td>
    <td>
        @if($p->status_terbaring === 'Terbaring')
            <span class="badge bg-danger">{{ $p->status_terbaring }}</span>
        @else
            <span class="text-muted">Normal</span>
        @endif
    </td>
    <td>
        @if($p->latitude && $p->longitude)
            <a href="https://www.google.com/maps/search/?api=1&query={{ $p->latitude }},{{ $p->longitude }}" target="_blank">Lokasi📍</a>
        @else
            <span class="text-muted">Tidak Ada Lokasi</span>
        @endif
    </td>
  <td>
    <!-- Tombol Kirim Manual -->
    <form action="/pekerjas/${p.id}/kirim-manual" method="POST" class="d-inline">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit" class="btn btn-warning btn-sm">📩 Kirim Manual</button>
    </form>

    <!-- Tombol Hapus -->
    <form action="/pekerjas/${p.id}" method="POST" class="d-inline" onsubmit="return confirm('⚠️ Yakin ingin menghapus data ini?');">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="DELETE">
        <button class="btn btn-outline-danger btn-sm">🗑️ Hapus</button>
    </form>
</td>
</tr>
@endforeach

            </tbody>
        </table>
    </div>
</div>

<!-- Audio alert -->
<audio id="alertSound" src="{{ asset('sounds/alert.mp3') }}" preload="auto"></audio>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const telegramRouteTemplate = "{{ route('pekerjas.kirimTelegram', ':id') }}";

    const fetchData = () => {
        fetch("{{ route('pekerjas.latest') }}")
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById("pekerjaTableBody");
                const alertDiv = document.getElementById('daruratAlert');
                tbody.innerHTML = '';

                const daruratAda = data.some(p => p.kondisi_pekerja === 'Benturan_Keras' || p.status_terbaring === 'Terbaring');
                if(daruratAda){
                    alertDiv.classList.remove('d-none');
                    const alertSound = document.getElementById('alertSound');
                    if(alertSound) alertSound.play().catch(()=>{});
                } else {
                    alertDiv.classList.add('d-none');
                }

                data.forEach(p => {
                    const tr = document.createElement("tr");
                    tr.id = `row-${p.id}`;
                    if(p.kondisi_pekerja === 'Benturan_Keras' || p.status_terbaring === 'Terbaring') tr.classList.add('table-danger');

                    tr.innerHTML = `
    <td class="fw-semibold">${p.device_id}</td>
    <td><span class="badge ${p.status_helm === 'Helm_Terpakai' ? 'bg-success' : 'bg-warning'}">${p.status_helm}</span></td>
    <td><span class="badge ${
        p.kondisi_pekerja === 'Benturan_Ringan' ? 'bg-info' :
        (p.kondisi_pekerja === 'Benturan_Sedang' ? 'bg-warning text-dark' : 'bg-danger')
    }">${p.kondisi_pekerja}</span></td>
    <td>${
        p.status_terbaring === 'Terbaring'
            ? `<span class="badge bg-danger">${p.status_terbaring}</span>`
            : `<span class="text-muted">Normal</span>`
    }</td>
    <td>${
        (p.latitude && p.longitude)
            ? `<a href="https://www.google.com/maps/search/?api=1&query=${p.latitude},${p.longitude}" target="_blank">Lokasi📍</a>`
            : `<span class="text-muted">Tidak Ada Lokasi</span>`
    }</td>
    <td>
        <button class="btn btn-warning btn-sm btn-telegram-manual" data-id="${p.id}">📩 Kirim Manual</button>
        <form action="/pekerjas/${p.id}" method="POST" class="d-inline" onsubmit="return confirm('⚠️ Yakin ingin menghapus data ini?');">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
            <button class="btn btn-outline-danger btn-sm">🗑️ Hapus</button>
        </form>
    </td>
`;

                    tbody.appendChild(tr);

                    // Kirim otomatis hanya jika belum terkirim
                    if (
                        (p.kondisi_pekerja === 'Benturan_Keras' || p.status_terbaring === 'Terbaring')
                        && !p.telegram_sent // cast otomatis ke boolean
                    ) {
                        fetch(telegramRouteTemplate.replace(':id', p.id), {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                        })
                        .then(res => res.json())
                        .then(resp => {
                            if(resp.success){
                                console.log('✅ Telegram otomatis terkirim untuk ID ' + p.id);
                            } else {
                                console.warn('❌ Gagal kirim Telegram otomatis ID ' + p.id, resp.message);
                            }
                        })
                        .catch(err => console.error('❌ Error Telegram otomatis ID ' + p.id, err));
                    }
                });
            })
            .catch(err => console.error(err));
    };

    // Jalankan pertama kali dan set interval 4 detik
    fetchData();
    setInterval(fetchData, 4000);

    // Tombol Telegram manual
document.getElementById('pekerjaTableBody').addEventListener('click', function(e){
    if(e.target && e.target.classList.contains('btn-telegram-manual')){
        const id = e.target.dataset.id;
        fetch(`/pekerjas/${id}/kirim-manual`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
        })
        .then(res => res.json())
        .then(data => {
            alert(data.success ? '✅ Telegram berhasil dikirim!' : '❌ ' + data.message);
        })
        .catch(err => console.error(err));
    }
});

});
</script>

@endsection
