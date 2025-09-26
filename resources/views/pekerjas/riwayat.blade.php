@extends('layouts.app')

@section('content')
<div class="container mt-4">
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="fw-bold">📜 Riwayat Data Helm Pekerja</h2>
</div>

<div class="d-flex justify-content-end mb-3 gap-2">
    <form method="GET" action="{{ route('pekerjas.riwayat') }}" class="d-flex" style="max-width: 300px;">
        <input type="text" name="device_id" class="form-control me-2" placeholder="Device ID" value="{{ $device_id }}">
        <button class="btn btn-primary" type="submit">🔍</button>
    </form>
   
</div>

 <div class="table-responsive">
       <table class="table table-hover align-middle text-center shadow-sm rounded">
        <thead class="table-dark">
            <tr>
                <th>Waktu</th>
                <th>Device ID</th>
                <th>Status Helm</th>
                <th>Kondisi Pekerja</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Link Lokasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($allPekerjas as $p)
                <tr>
                    <td>{{ $p->created_at }}</td>
                    <td>{{ $p->device_id }}</td>
                    <td>{{ $p->status_helm }}</td>
                    <td>{{ $p->kondisi_pekerja }}</td>
                    <td>{{ $p->latitude ?? '-' }}</td>
                    <td>{{ $p->longitude ?? '-' }}</td>
                    <td>
                        @if($p->latitude && $p->longitude)
                            <a href="https://www.google.com/maps?q={{ $p->latitude }},{{ $p->longitude }}" target="_blank">📍 Lihat</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">Tidak ada data history.</td></tr>
            @endforelse
        </tbody>
    </table>
     <a href="{{ route('pekerjas.index') }}" class="btn btn-secondary">⬅️ Kembali</a>
 </div>  
</div>
</div>

@endsection
