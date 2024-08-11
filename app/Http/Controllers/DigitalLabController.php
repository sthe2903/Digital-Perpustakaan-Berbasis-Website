<?php

namespace App\Http\Controllers;

use App\Models\buku;
use App\Models\kategori;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DigitalLabController extends Controller
{
    public function storekategori(Request $request)
    {
        $request->validate([
            'kategori_buku' => 'required|string|max:255',
        ]);

        kategori::create([
            'kategori_buku' => $request->input('kategori_buku'),
        ]);

        return redirect()->back()->with('success', 'Berhasil Menambah Kategori');
    }

    public function storebuku(Request $request)
    {
        $request->validate([
            'judul_buku' => 'required|string|max:255|unique:buku,judul_buku',
            'kategori_buku' => 'required',
            'jumlah' => 'required|integer',
            'deskripsi' => 'required|string',
            'file_buku' => 'required|mimes:pdf',
            'cover_buku' => 'required|mimes:jpeg,jpg,png',
        ]);

        $fileBukuPath = $request->file_buku->storeAs('public/files', $request->judul_buku . '.' . $request->file_buku->getClientOriginalExtension());
        $coverBukuPath = $request->cover_buku->storeAs('public/covers', $request->judul_buku . '.' . $request->cover_buku->getClientOriginalExtension());


        $file = $request->file('file_buku');
        $nama_file = $request->input('judul_buku') . "." . $request->file('file_buku')->getClientOriginalExtension();
        $file->move(public_path('file_buku'), $nama_file);

        $cover = $request->file('cover_buku');
        $cover_buku = $request->input('judul_buku') . "." . $request->file('cover_buku')->getClientOriginalExtension();
        $cover->move(public_path('cover_buku'), $cover_buku);

        buku::create([
            'judul_buku' => $request->input('judul_buku'),
            'kategori_buku' => $request->input('kategori_buku'),
            'jumlah' => $request->input('jumlah'),
            'deskripsi' => $request->input('deskripsi'),
            'file_buku' => $nama_file,
            'cover_buku' => $cover_buku,
            'uploaded_by' => session('name')
        ]);

        return redirect()->route('dashboard')->with('success', 'Berhasil Menambahkan Buku');
    }


    public function delete($judul_buku)
    {
        $user = Auth::user(); 

        $buku = buku::where('judul_buku', $judul_buku)->first();


        if ($buku && (session('name') == $buku->uploaded_by || session('role') == 'admin')) {
            Storage::delete([
                'public/files/' . $buku->file_buku,
                'public/covers/' . $buku->cover_buku,
            ]);
            $buku->delete();
            return redirect()->route('dashboard')->with('success', 'Berhasil Hapus Data');
        } else {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus data ini.');
        }
    }

    public function deletekategori($kategori_buku)
    {
        $buku = kategori::where('kategori_buku', $kategori_buku)->first();

        $buku->delete();
        return redirect()->back()->with('success', 'Berhasil Hapus Data');

    }

    public function updatekategori(Request $request, $id)
    {
        $request->validate([
            'kategori_buku' => 'required|string|max:255|unique:kategori,kategori_buku',
        ]);

        $kategori = Kategori::find($id);

        if ($kategori) {
            // $kategori->bukus()->update(['kategori_buku' => $request->input('kategori_buku')]);

            $kategori->kategori_buku = $request->input('kategori_buku');
            $kategori->save();

            return redirect()->back()->with('success', 'Berhasil Mengupdate Kategori dan Buku Terkait');
        } else {
            return redirect()->back()->with('error', 'Kategori tidak ditemukan.');
        }
    }


    public function updatebuku(Request $request, $id)
    {
        $request->validate([
            'judul_buku' => 'string|max:255|unique:buku,judul_buku',
            'kategori_buku' => '',
            'jumlah' => 'integer',
            'deskripsi' => 'string',
            'file_buku' => 'nullable|mimes:pdf',
            'cover_buku' => 'nullable|mimes:jpeg,jpg,png',
        ]);

        $buku = buku::where('id', $id)->first();

        if (!$buku) {
            return redirect()->back()->with('error', 'Buku tidak ditemukan');
        }

        Storage::delete([
            'public/files/' . $buku->file_buku,
            'public/covers/' . $buku->cover_buku,
        ]);

        if ($request->hasFile('file_buku')) {
            $file = $request->file('file_buku');
            $nama_file = $request->input('judul_buku') . "." . $request->file('file_buku')->getClientOriginalExtension();
            $file->move(public_path('file_buku'), $nama_file);
            $buku->file_buku = $request->judul_buku . '.' . $request->file_buku->getClientOriginalExtension();
        }

        if ($request->hasFile('cover_buku')) {
            $cover = $request->file('cover_buku');
            $cover_buku = $request->input('judul_buku') . "." . $request->file('cover_buku')->getClientOriginalExtension();
            $cover->move(public_path('cover_buku'), $cover_buku);
            $buku->cover_buku = $request->judul_buku . '.' . $request->cover_buku->getClientOriginalExtension();
        }

        $buku->judul_buku = $request->input('judul_buku');
        $buku->kategori_buku = $request->input('kategori_buku');
        $buku->jumlah = $request->input('jumlah');
        $buku->deskripsi = $request->input('deskripsi');
        $buku->uploaded_by = session('name');
        $buku->save();

        return redirect()->route('dashboard')->with('success', 'Berhasil Memperbarui Buku');
    }

    public function exportToPdf()
    {
        $buku = buku::all();

        $pdf = Pdf::loadView('exports.buku_pdf', ['buku' => $buku]);

        return $pdf->download('buku.pdf');
    }

}
