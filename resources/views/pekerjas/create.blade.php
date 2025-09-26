@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>➕ Tambah Helm / Alat</h2>

    {{-- Flash message jika helm tidak terpakai --}}
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Tampilkan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pekerjas.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="device_id" class="form-label">Device ID (Unik)</label>
            <input type="text" name="device_id" class="form-control" value="{{ old('device_id') }}" required>
        </div>

        <div class="mb-3">
            <label for="status_helm" class="form-label">Status Helm</label>
            <select name="status_helm" class="form-select" required>
                <option value="Helm_Terpakai" {{ old('status_helm') == 'Helm_Terpakai' ? 'selected' : '' }}>Helm Terpakai</option>
                <option value="Helm_Tidak_Terpakai" {{ old('status_helm') == 'Helm_Tidak_Terpakai' ? 'selected' : '' }}>Helm Tidak Terpakai</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="kondisi_pekerja" class="form-label">Kondisi Pekerja</label>
            <select name="kondisi_pekerja" class="form-select" required>
                <option value="Benturan_Ringan" {{ old('kondisi_pekerja') == 'Benturan_Ringan' ? 'selected' : '' }}>Benturan Ringan</option>
                <option value="Benturan_Sedang" {{ old('kondisi_pekerja') == 'Benturan_Sedang' ? 'selected' : '' }}>Benturan Sedang</option>
                <option value="Benturan_Keras" {{ old('kondisi_pekerja') == 'Benturan_Keras' ? 'selected' : '' }}>Benturan Keras</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="latitude" class="form-label">Latitude (opsional)</label>
            <input type="text" name="latitude" class="form-control" value="{{ old('latitude') }}">
        </div>

        <div class="mb-3">
            <label for="longitude" class="form-label">Longitude (opsional)</label>
            <input type="text" name="longitude" class="form-control" value="{{ old('longitude') }}">
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('pekerjas.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
