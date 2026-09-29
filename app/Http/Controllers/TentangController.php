<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tentang;

class TentangController extends Controller
{
    /**
     * Mengubah foto sampul halaman "Tentang Klinik".
     * P1-Fix: Tambah validasi tipe & ukuran file gambar.
     */
    public function photo_edit(Request $request) {
        $img = $request->foto;

        if ($img != null) {
            // P1: Validasi tipe dan ukuran file
            $request->validate([
                'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
            ]);

            Tentang::deleteImage($request->foto_old);

            $imgext  = $request->foto->extension();
            $imgname = 'about.'.$imgext;

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

        if ($query == true) {
            return redirect('/admin-area/informasi-umum')->with('success', 'Foto berhasil diubah.');
        } else {
            return redirect('/admin-area/informasi-umum')->with('error', 'Terdapat kesalahan dalam mengedit foto.');
        }
    }

    /**
     * Mengubah deskripsi/informasi umum klinik.
     */
    public function informasi_edit(Request $request) {
        $validated = $request->validate([
            'informasi_umum' => 'required',
        ]);

        $query = Tentang::where('id_tentang', 'TG-001')->update($validated);

        if ($query == true) {
            return redirect('/admin-area/informasi-umum')->with('success', 'Deskripsi berhasil diubah.');
        } else {
            return redirect('/admin-area/informasi-umum')->with('error', 'Terdapat kesalahan dalam mengedit deskripsi.');
        }
    }

    /**
     * Mengubah pernyataan visi klinik.
     */
    public function visi_edit(Request $request) {
        $validated = $request->validate([
            'visi' => 'required',
        ]);

        $query = Tentang::where('id_tentang', 'TG-001')->update($validated);

        if ($query == true) {
            return redirect('/admin-area/informasi-umum')->with('success', 'Data visi berhasil diubah.');
        } else {
            return redirect('/admin-area/informasi-umum')->with('error', 'Terdapat kesalahan dalam mengedit data visi.');
        }
    }

    /**
     * Mengubah pernyataan misi klinik.
     */
    public function misi_edit(Request $request) {
        $validated = $request->validate([
            'misi' => 'required',
        ]);

        $query = Tentang::where('id_tentang', 'TG-001')->update($validated);

        if ($query == true) {
            return redirect('/admin-area/informasi-umum')->with('success', 'Data misi berhasil diubah.');
        } else {
            return redirect('/admin-area/informasi-umum')->with('error', 'Terdapat kesalahan dalam mengedit data misi.');
        }
    }

    /**
     * Mengubah tugas pokok dan fungsi (tupoksi) klinik.
     */
    public function tupoksi_edit(Request $request) {
        $validated = $request->validate([
            'tupoksi' => 'required',
        ]);

        $query = Tentang::where('id_tentang', 'TG-001')->update($validated);

        if ($query == true) {
            return redirect('/admin-area/informasi-umum')->with('success', 'Data tugas pokok dan fungsi berhasil diubah.');
        } else {
            return redirect('/admin-area/informasi-umum')->with('error', 'Terdapat kesalahan dalam mengedit data tugas pokok dan fungsi.');
        }
    }
}
