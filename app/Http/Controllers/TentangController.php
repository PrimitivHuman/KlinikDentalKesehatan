<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tentang;
use App\Support\Sanitizer;

class TentangController extends Controller
{
    /**
     * Mengubah foto sampul halaman "Tentang Klinik".
     */
    public function photo_edit(Request $request)
    {
        $img = $request->foto;

        if ($img != null) {
            $request->validate([
                'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
            ]);

            Tentang::deleteImage($request->foto_old);

            $imgext  = $request->foto->extension();
            $imgname = 'about.' . $imgext;

            $request->merge([
                'foto_sampul' => $imgname,
            ]);

            $validated = $request->validate([
                'foto_sampul' => 'required',
            ]);

            $img->move(public_path('/main/img/logo'), $imgname);

            $query = Tentang::where('id_tentang', 'TG-001')->update($validated);
        } else {
            return redirect()->back()->with('error', 'File foto tidak boleh kosong.');
        }

        if ($query) {
            return redirect('/admin-area/informasi-umum')->with('success', 'Foto berhasil diubah.');
        }

        return redirect('/admin-area/informasi-umum')->with('error', 'Terdapat kesalahan dalam mengedit foto.');
    }

    /**
     * Mengubah deskripsi/informasi umum klinik (tersanitasi #5).
     */
    public function informasi_edit(Request $request)
    {
        $validated = $request->validate([
            'informasi_umum' => 'required',
        ]);

        $query = Tentang::where('id_tentang', 'TG-001')->update([
            'informasi_umum' => Sanitizer::html($validated['informasi_umum']),
        ]);

        if ($query) {
            return redirect('/admin-area/informasi-umum')->with('success', 'Deskripsi berhasil diubah.');
        }

        return redirect('/admin-area/informasi-umum')->with('error', 'Terdapat kesalahan dalam mengedit deskripsi.');
    }

    /**
     * Mengubah pernyataan visi klinik (tersanitasi #5).
     */
    public function visi_edit(Request $request)
    {
        $validated = $request->validate([
            'visi' => 'required',
        ]);

        $query = Tentang::where('id_tentang', 'TG-001')->update([
            'visi' => Sanitizer::html($validated['visi']),
        ]);

        if ($query) {
            return redirect('/admin-area/informasi-umum')->with('success', 'Data visi berhasil diubah.');
        }

        return redirect('/admin-area/informasi-umum')->with('error', 'Terdapat kesalahan dalam mengedit data visi.');
    }

    /**
     * Mengubah pernyataan misi klinik (tersanitasi #5).
     */
    public function misi_edit(Request $request)
    {
        $validated = $request->validate([
            'misi' => 'required',
        ]);

        $query = Tentang::where('id_tentang', 'TG-001')->update([
            'misi' => Sanitizer::html($validated['misi']),
        ]);

        if ($query) {
            return redirect('/admin-area/informasi-umum')->with('success', 'Data misi berhasil diubah.');
        }

        return redirect('/admin-area/informasi-umum')->with('error', 'Terdapat kesalahan dalam mengedit data misi.');
    }

    /**
     * Mengubah tugas pokok dan fungsi (tupoksi) klinik (tersanitasi #5).
     * #19 Fix: Terhubung ke route /admin-area/informasi-umum/edit-tupoksi.
     */
    public function tupoksi_edit(Request $request)
    {
        $validated = $request->validate([
            'tupoksi' => 'required',
        ]);

        $query = Tentang::where('id_tentang', 'TG-001')->update([
            'tupoksi' => Sanitizer::html($validated['tupoksi']),
        ]);

        if ($query) {
            return redirect('/admin-area/informasi-umum')->with('success', 'Data tugas pokok dan fungsi berhasil diubah.');
        }

        return redirect('/admin-area/informasi-umum')->with('error', 'Terdapat kesalahan dalam mengedit data tugas pokok dan fungsi.');
    }
}
