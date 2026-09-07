<form method="POST" action="{{ url('kategori/form/submit/' . $method . '/' . ($item->id ?? 0)) }}">
    @csrf
    <div class="form-group">
        <label>Kode Kategori</label>
        <input type="text" class="form-control" name="kode" required value="{{ $item->kode ?? '' }}">
    </div>

    <div class="form-group mt-3">
        <label>Nama Kategori</label>
        <input type="text" class="form-control" name="nama" required value="{{ $item->nama ?? '' }}">
    </div>

    <button class="btn btn-primary mt-3">Submit</button>
</form>
