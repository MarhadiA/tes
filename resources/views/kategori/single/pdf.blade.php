<!DOCTYPE html>
<html>

<head>
    <title>Print Kategori - {{ $kategori->nama }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }

        h3 {
            margin-bottom: 5px;
        }

        p {
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: right;
            font-size: 10px;
            color: #555;
        }
    </style>
</head>

<body>
    <h3>Detail Kategori Items</h3>
    <p><strong>Kode Kategori:</strong> {{ $kategori->kode }}</p>
    <p><strong>Nama Kategori:</strong> {{ $kategori->nama }}</p>

    <table>
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
            @foreach ($kategori->masterItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->jenis }}</td>
                    <td>{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                    <td>{{ $item->supplier }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ date('d-m-Y H:i:s') }}
    </div>
</body>

</html>
