<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    var start_date = '';
    var end_date = '';
    var data_per_fetch = 500;
    var data_fetched = 0;

    $(document).ready(function() {
        $('#table').DataTable({
            searching: false,
            order: [
                [0, 'desc']
            ],
        });
        getData()
    });

    $('.btn-get-data').click(function() {
        getData()
    })

    function getData() {

        $('#loading-filter').show();
        var dataTableObj = $('#table').DataTable();
        var filter_kode = $('#filter-kode').val()
        var filter_nama = $('#filter-nama').val()
        var filter_harga_min = $('#filter-harga-min').val()
        var filter_harga_max = $('#filter-harga-max').val()
        dataTableObj.clear().draw();

        $.ajax({
            url: '{{ url('master-items/search') }}',
            dataType: 'json',
            tryCount: 0,
            retryLimit: 3,
            data: 'kode=' + filter_kode + '&nama=' + filter_nama + '&hargamin=' + filter_harga_min +
                '&hargamax=' + filter_harga_max,
            success: function(results) {
                var data = results.data

                $.each(data, function(index, item) {
                    var array_temp = [];
                    var harga_jual = item.harga_beli + item.harga_beli * item.laba / 100;
                    harga_jual = Math.round(harga_jual);
                    var kode = item.kode;

                    var html_view = `<a href="{{ url('master-items/view/') }}/` + kode +
                        `" class="btn btn-primary">View</a>`;

                    // Render gambar foto jika ada
                    var html_foto = item.foto ? `<img src="{{ asset('storage') }}/` + item.foto +
                        `" width="50" class="img-thumbnail" alt="Foto">` : 'Tidak ada foto';

                    // Urutan push tepat 8 kolom sesuai <th> di HTML (Kode, Foto, Nama, Jenis, Harga Beli, Harga Jual, Supplier, View)
                    array_temp.push(item.kode); // Kolom 0: Kode
                    array_temp.push(html_foto); // Kolom 1: Foto
                    array_temp.push(item.nama); // Kolom 2: Nama
                    array_temp.push(item.jenis); // Kolom 3: Jenis
                    array_temp.push(item.harga_beli); // Kolom 4: Harga Beli
                    array_temp.push(harga_jual); // Kolom 5: Harga Jual
                    array_temp.push(item.supplier); // Kolom 6: Supplier
                    array_temp.push(html_view); // Kolom 7: View

                    dataTableObj.row.add(array_temp).draw(true);
                });
                $('#loading-filter').hide();
            },
            error: function(xhr, textStatus, errorThrown) {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }
                alert('Terjadi kesalahan server, tidak dapat mengambil data')
                $('#loading-filter').hide();

                return;
            }
        })
    }
</script>
