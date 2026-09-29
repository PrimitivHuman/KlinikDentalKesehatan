<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokter;

class DokterController extends Controller
{
    /**
     * Menyimpan data dokter baru ke database.
     * P1-Fix: Tambah validasi tipe file gambar.
     * P4-Fix: Tambah PHPDoc dan rapikan kode.
     */
    public function dokter_submit(Request $request) {
        // P1: Validasi file gambar sebelum proses
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $img     = $request->foto;
        $imgext  = $request->foto->extension();
        $imgname = time().'-'.Dokter::dokterGenerateID().'.'.$imgext;

        $request->merge([
            'id_dokter' => Dokter::dokterGenerateID(),
            'images'    => $imgname,
        ]);

        $valo = $request->validate([
            'id_dokter'    => 'required|unique:dokter|max:15',
            'nama_dokter'  => 'required|max:50',
            'no_hp_dokter' => 'required',
            'email_dokter' => 'required|email',
            'jadwal_dokter'=> 'required',
            'str_dokter'   => 'required',
            'sip_dokter'   => 'required',
            'images'       => 'required',
        ]);

        $query = Dokter::create($valo);

        $img->move(public_path('/img/dokter'), $imgname);

        if ($query == true) {
            return redirect('/admin-area/dokter')->with('success', 'Berhasil menambahkan data dokter.');
        } else {
            return redirect('/admin-area/dokter')->with('error', 'Terjadi kesalahan dalam menambahkan data dokter.');
        }
    }

    /**
     * Menampilkan form edit data dokter berdasarkan ID terenkripsi.
     */
    public function dokter_edit($id) {
        $dokter = Dokter::where('id_dokter', decrypt($id))->get();

        return view('admin.dokter_edit', [
            'dokter' => $dokter,
            'title'  => 'Edit Data Dokter',
            'menu'   => 'Dokter'
        ]);
    }

    /**
     * Menghapus data dokter beserta foto profil-nya.
     * Dengan SoftDeletes aktif, data tidak terhapus permanen.
     */
    public function dokter_delete($id) {
        Dokter::deleteImage(decrypt($id));

        $query = Dokter::destroy(decrypt($id));

        if ($query == true) {
            return redirect('/admin-area/dokter')->with('success', 'Berhasil menghapus data dokter.');
        } else {
            return redirect('/admin-area/dokter')->with('error', 'Terjadi kesalahan dalam menghapus data dokter.');
        }
    }

    /**
     * Memperbarui data dokter yang sudah ada.
     * P1-Fix: Tambah validasi tipe file gambar jika ada file baru.
     */
    public function dokter_update(Request $request) {
        $img = $request->foto;

        if ($img != null) {
            // P1: Validasi tipe file gambar
            $request->validate([
                'foto' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            $imgext  = $request->foto->extension();
            $imgname = time().'-'.$request->id_dokter.'.'.$imgext;

            $request->merge([
                'images' => $imgname,
            ]);

            $query = $request->validate([
                'nama_dokter'  => 'required|max:70',
                'no_hp_dokter' => 'required',
                'images'       => 'required',
                'email_dokter' => 'required|email',
                'jadwal_dokter'=> 'required',
                'str_dokter'   => 'required',
                'sip_dokter'   => 'required',
            ]);

            Dokter::deleteImage($request->id_dokter);

            $img->move(public_path('/img/dokter'), $imgname);

            $query = Dokter::where('id_dokter', $request->id_dokter)->update($query);
        } else {
            $query = $request->validate([
                'nama_dokter'  => 'required|max:70',
                'no_hp_dokter' => 'required',
                'email_dokter' => 'required|email',
                'jadwal_dokter'=> 'required',
                'str_dokter'   => 'required',
                'sip_dokter'   => 'required',
            ]);

            $query = Dokter::where('id_dokter', $request->id_dokter)->update($query);
        }

        if ($query == true) {
            return redirect('/admin-area/dokter')->with('success', 'Berhasil mengedit data dokter.');
        } else {
            return redirect('/admin-area/dokter')->with('error', 'Terjadi kesalahan dalam mengedit data dokter.');
        }
    }

    /**
     * Mencari data dokter berdasarkan ID, nama, atau jadwal.
     */
    public function dokter_search(Request $request) {
        $request->merge([
            'cari' => '%'.$request->cari.'%',
        ]);

        $validated = $request->validate([
            'cari' => 'required',
        ]);

        $query = Dokter::where('id_dokter', 'like', $validated)
                       ->orWhere('nama_dokter', 'like', $validated)
                       ->orWhere('jadwal_dokter', 'like', $validated)
                       ->paginate(8);

        if ($query == true) {
            if (count($query) == 0) {
                return redirect()->back()->with('message', 'Data dokter tidak ditemukan.');
            } else {
                return view('admin.dokter', [
                    'title'  => 'Hasil Pencarian : '.$request->cari,
                    'menu'   => 'dokter',
                    'dokter' => $query,
                ]);
            }
        } else {
            return redirect()->back()->with('message', 'Terjadi kesalahan dalam pencarian data.');
        }
    }
}
