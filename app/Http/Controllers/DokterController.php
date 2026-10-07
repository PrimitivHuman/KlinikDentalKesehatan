<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokter;

class DokterController extends Controller
{
    /**
     * Menyimpan data dokter baru ke database.
     */
    public function dokter_submit(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $idDokter = Dokter::dokterGenerateID();
        $img     = $request->foto;
        $imgext  = $request->foto->extension();
        $imgname = time() . '-' . $idDokter . '.' . $imgext;

        $request->merge([
            'id_dokter' => $idDokter,
            'images'    => $imgname,
        ]);

        $valo = $request->validate([
            'id_dokter'     => 'required|unique:dokter,id_dokter|max:20',
            'nama_dokter'   => 'required|max:100',
            'no_hp_dokter'  => 'required|max:25',
            'email_dokter'  => 'required|email|max:100',
            'jadwal_dokter' => 'required|max:255',
            'str_dokter'    => 'required|max:50',
            'sip_dokter'    => 'required|max:50',
            'images'        => 'required',
        ]);

        $query = Dokter::create($valo);

        $img->move(public_path('/img/dokter'), $imgname);

        if ($query) {
            return redirect('/admin-area/dokter')->with('success', 'Berhasil menambahkan data dokter.');
        }

        return redirect('/admin-area/dokter')->with('error', 'Terjadi kesalahan dalam menambahkan data dokter.');
    }

    /**
     * Menampilkan form edit data dokter berdasarkan ID terenkripsi.
     */
    public function dokter_edit($id)
    {
        $dokter = Dokter::where('id_dokter', decrypt($id))->firstOrFail();

        return view('admin.dokter_edit', [
            'dokter' => $dokter,
            'title'  => 'Edit Data Dokter',
            'menu'   => 'dokter',
        ]);
    }

    /**
     * Menghapus data dokter (Soft Delete).
     * #11 Fix: File gambar JANGAN dihapus saat soft-delete (hanya saat force delete di trash).
     */
    public function dokter_delete($id)
    {
        $query = Dokter::destroy(decrypt($id));

        if ($query) {
            return redirect('/admin-area/dokter')->with('success', 'Berhasil menghapus data dokter.');
        }

        return redirect('/admin-area/dokter')->with('error', 'Terjadi kesalahan dalam menghapus data dokter.');
    }

    /**
     * Memperbarui data dokter yang sudah ada.
     * #17 Fix: Validasi id_dokter harus terdaftar di tabel dokter.
     */
    public function dokter_update(Request $request)
    {
        $request->validate([
            'id_dokter' => 'required|exists:dokter,id_dokter',
        ]);

        $dokter = Dokter::where('id_dokter', $request->id_dokter)->firstOrFail();
        $img    = $request->foto;

        if ($img != null) {
            $request->validate([
                'foto'          => 'image|mimes:jpg,jpeg,png,webp|max:2048',
                'nama_dokter'   => 'required|max:100',
                'no_hp_dokter'  => 'required|max:25',
                'email_dokter'  => 'required|email|max:100',
                'jadwal_dokter' => 'required|max:255',
                'str_dokter'    => 'required|max:50',
                'sip_dokter'    => 'required|max:50',
            ]);

            $imgext  = $request->foto->extension();
            $imgname = time() . '-' . $request->id_dokter . '.' . $imgext;

            Dokter::deleteImage($request->id_dokter);
            $img->move(public_path('/img/dokter'), $imgname);

            $data = [
                'nama_dokter'   => $request->nama_dokter,
                'no_hp_dokter'  => $request->no_hp_dokter,
                'email_dokter'  => $request->email_dokter,
                'jadwal_dokter' => $request->jadwal_dokter,
                'str_dokter'    => $request->str_dokter,
                'sip_dokter'    => $request->sip_dokter,
                'images'        => $imgname,
            ];
        } else {
            $data = $request->validate([
                'nama_dokter'   => 'required|max:100',
                'no_hp_dokter'  => 'required|max:25',
                'email_dokter'  => 'required|email|max:100',
                'jadwal_dokter' => 'required|max:255',
                'str_dokter'    => 'required|max:50',
                'sip_dokter'    => 'required|max:50',
            ]);
        }

        $dokter->update($data);

        return redirect('/admin-area/dokter')->with('success', 'Berhasil mengedit data dokter.');
    }

    /**
     * #12 & #13 Fix: Mencari data dokter via GET request dengan pagination yang membawa parameter pencarian.
     */
    public function dokter_search(Request $request)
    {
        $keyword = trim((string) $request->input('cari'));

        if ($keyword === '') {
            return redirect('/admin-area/dokter');
        }

        $query = Dokter::where(function ($q) use ($keyword) {
            $q->where('id_dokter', 'like', "%{$keyword}%")
              ->orWhere('nama_dokter', 'like', "%{$keyword}%")
              ->orWhere('jadwal_dokter', 'like', "%{$keyword}%")
              ->orWhere('email_dokter', 'like', "%{$keyword}%");
        })->paginate(8)->withQueryString();

        return view('admin.dokter', [
            'title'  => 'Hasil Pencarian: ' . $keyword,
            'menu'   => 'dokter',
            'dokter' => $query,
            'cari'   => $keyword,
        ]);
    }
}
