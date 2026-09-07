@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Daftar Kategori Items</h3>
            <a href="{{ url('kategori/form/new') }}" class="btn btn-success">+ Tambah Kategori Baru</a>
        </div>

        <div class="card p-3 mb-3">
            <h5>Filter Kategori</h5>
            <form method="GET" action="{{ url('kategori') }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="kode" placeholder="Kode Kategori"
                            value="{{ request('kode') }}">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="nama" placeholder="Nama Kategori"
                            value="{{ request('nama') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ url('kategori') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategoris as $index => $kat)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $kat->kode }}</td>
                                <td>{{ $kat->nama }}</td>
                                <td>
                                    <a href="{{ url('kategori/view/' . $kat->id) }}" class="btn btn-info btn-sm">View</a>
                                    <a href="{{ url('kategori/form/edit/' . $kat->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <a href="{{ url('kategori/delete/' . $kat->id) }}" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus?')">Delete</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data kategori.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('js')
@endsection
