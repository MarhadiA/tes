<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use App\Models\Kategori;
use Illuminate\Http\Request;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;

        $data_search = MasterItem::query();

        if (!empty($kode)) $data_search = $data_search->where('kode', $kode);
        if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        if (!empty($hargamin)) $data_search = $data_search->where('harga_beli', '>=', $hargamin);
        if (!empty($hargamax)) $data_search = $data_search->where('harga_beli', '<=', $hargamax);

        $data_search = $data_search->select('kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier', 'foto')->orderBy('id')->get();


        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = new MasterItem();
        } else {
            $item = MasterItem::with('kategoris')->find($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;
        $data['kategoris'] = Kategori::all();
        return view('master_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::where('kode', $kode)->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(3);
        } else {
            $data_item = MasterItem::find($id);
            $kode = $data_item->kode;
        }
        // Logika Upload Foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada (saat update data)
            if ($method != 'new' && $data_item->foto && file_exists(public_path('storage/' . $data_item->foto))) {
                unlink(public_path('storage/' . $data_item->foto));
            }

            $file = $request->file('foto');

            // Membersihkan spasi pada nama file asli menjadi underscore (_)
            $cleanOriginalName = str_replace(' ', '_', $file->getClientOriginalName());
            $filename = time() . '_' . $cleanOriginalName;

            // Simpan ke storage/app/public/items
            $file->storeAs('public/items', $filename);

            $data_item->foto = 'items/' . $filename;
        }

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;
        $data_item->save();
        if ($request->has('kategori_ids')) {
            $data_item->kategoris()->sync($request->kategori_ids);
        } else {
            $data_item->kategoris()->detach();
        }

        return redirect('master-items');
    }

    public function delete($id)
    {
        MasterItem::find($id)->delete();
        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach ($data as $item) {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100, 1000000);
            $item->laba = rand(10, 99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi', 'Bukulapuk', 'TokoBagas', 'E Commurz', 'Blublu'];
        $random = rand(0, 4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat', 'Alkes', 'Matkes', 'Umum', 'ATK'];
        $random = rand(0, 4);
        return $array[$random];
    }
    public function exportExcel()
    {
        $fileName = 'master-items-' . date('Y-m-d') . '.xls';
        $items = MasterItem::with('kategoris')->get();

        $headers = [
            "Content-type" => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($items) {
            $file = fopen('php://output', 'w');

            // Header Kolom Excel
            fputcsv($file, ['No', 'Nama Kategori', 'Nama Items', 'Nama Supplier', 'Harga Beli', 'Laba (%)', 'Harga Jual'], "\t");

            foreach ($items as $index => $item) {
                $kategoriNames = $item->kategoris->pluck('nama')->implode(', ');
                $hargaJual = $item->harga_beli + ($item->harga_beli * $item->laba / 100);

                fputcsv($file, [
                    $index + 1,
                    $kategoriNames,
                    $item->nama,
                    $item->supplier,
                    $item->harga_beli,
                    $item->laba,
                    round($hargaJual)
                ], "\t");
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
