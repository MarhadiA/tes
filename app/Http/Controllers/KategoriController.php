<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $query = Kategori::query();

        if ($request->filled('kode')) {
            $query->where('kode', 'LIKE', '%' . $request->kode . '%');
        }
        if ($request->filled('nama')) {
            $query->where('nama', 'LIKE', '%' . $request->nama . '%');
        }

        $data['kategoris'] = $query->orderBy('id', 'desc')->get();
        return view('kategori.index.index', $data);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = new Kategori();
        } else {
            $item = Kategori::findOrFail($id);
        }

        $data['item'] = $item;
        $data['method'] = $method;
        return view('kategori.form.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'kode' => 'required|string|max:50',
            'nama' => 'required|string|max:100',
        ]);

        if ($method == 'new') {
            $kategori = new Kategori();
        } else {
            $kategori = Kategori::findOrFail($id);
        }

        $kategori->kode = $request->kode;
        $kategori->nama = $request->nama;
        $kategori->save();

        return redirect('kategori');
    }

    public function singleView($id)
    {
        $data['kategori'] = Kategori::with('masterItems')->findOrFail($id);
        return view('kategori.single.index', $data);
    }

    public function delete($id)
    {
        $kategori = Kategori::findOrFail($id);
        // Hapus relasi di tabel pivot terlebih dahulu agar bersih
        $kategori->masterItems()->detach();
        $kategori->delete();

        return redirect('kategori');
    }
    public function downloadPdf($id)
    {
        $kategori = Kategori::with('masterItems')->findOrFail($id);
        $pdf = \PDF::loadView('kategori.single.pdf', compact('kategori'));
        return $pdf->download('kategori-' . $kategori->kode . '.pdf');
    }
}
