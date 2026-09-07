@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="form-group mb-2 d-flex justify-content-between align-items-center">
            <a href="{{ url('kategori') }}" class="btn btn-secondary">Kembali ke Daftar Kategori</a>
            <a href="{{ url('kategori/pdf/' . $kategori->id) }}" class="btn btn-danger" target="_blank">Download PDF</a>
        </div>

        <div class="card mb-4">
            <div class="card-header">Detail Kategori</div>
            <div class="card-body">
                <p><strong>Kode Kategori:</strong> {{ $kategori->kode }}</p>
                <p><strong>Nama Kategori:</strong> {{ $kategori->nama }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Daftar Item dalam Kategori Ini</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Item</th>
                            <th>Nama Item</th>
                            <th>Jenis</th>
                            <th>Harga Beli</th>
                            <th>Supplier</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategori->masterItems as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->kode }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->jenis }}</td>
                                <td>{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                <td>{{ $item->supplier }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada item yang terdaftar pada kategori ini.</td>
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
