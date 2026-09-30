<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;

class KegiatanController extends Controller
{
    /**
     * Menyimpan data agenda kegiatan baru ke database dan upload gambar.
     */
    public function activity_submit(Request $request) {
        $request->validate([
            'judul_kegiatan'     => 'required|max:150',
            'deskripsi_kegiatan' => 'required',
            'tgl_kegiatan'       => 'required|date',
            'foto'               => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $img     = $request->foto;
        $imgext  = $img->extension();
        $id      = Kegiatan::generateID();
        $imgname = time().'-'.$id.'.'.$imgext;

        $query = Kegiatan::create([
            'id_kegiatan'        => $id,
            'judul_kegiatan'     => $request->judul_kegiatan,
            'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
            'tgl_kegiatan'       => $request->tgl_kegiatan,
            'images'             => $imgname,
        ]);

        if ($query) {
            $img->move(public_path('/img/activity'), $imgname);
            return redirect('/admin-area/kegiatan')->with('success', 'Berhasil menambahkan data agenda kegiatan.');
        } else {
            return redirect('/admin-area/kegiatan')->with('error', 'Terjadi kesalahan dalam menambahkan agenda kegiatan.');
        }
    }

    /**
     * Menampilkan form edit agenda kegiatan.
     * K11 Fix: Gunakan firstOrFail() bukan get() karena hanya butuh 1 record.
     */
    public function activity_edit($id) {
        $activity = Kegiatan::where('id_kegiatan', decrypt($id))->firstOrFail();

        return view('admin.activity_edit', [
            'activity' => $activity,
            'title'    => 'Edit Agenda Kegiatan',
            'menu'     => 'kegiatan',
        ]);
    }

    /**
     * Mengubah data agenda kegiatan dan foto.
     */
    public function activity_update(Request $request) {
        $request->validate([
            'id_kegiatan'        => 'required',
            'judul_kegiatan'     => 'required|max:150',
            'deskripsi_kegiatan' => 'required',
            'tgl_kegiatan'       => 'required|date',
            'foto'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $id = decrypt($request->id_kegiatan);
        $kegiatan = Kegiatan::where('id_kegiatan', $id)->first();

        if (!$kegiatan) {
            return redirect('/admin-area/kegiatan')->with('error', 'Data kegiatan tidak ditemukan.');
        }

        $data = [
            'judul_kegiatan'     => $request->judul_kegiatan,
            'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
            'tgl_kegiatan'       => $request->tgl_kegiatan,
        ];

        if ($request->hasFile('foto')) {
            Kegiatan::deleteImage($id);
            $img     = $request->foto;
            $imgext  = $img->extension();
            $imgname = time().'-'.$id.'.'.$imgext;
            $img->move(public_path('/img/activity'), $imgname);
            $data['images'] = $imgname;
        }

        $kegiatan->update($data);

        return redirect('/admin-area/kegiatan')->with('success', 'Berhasil memperbarui agenda kegiatan.');
    }

    /**
     * Menghapus agenda kegiatan (Soft Delete).
     */
    public function activity_delete($id) {
        $realId = decrypt($id);
        $kegiatan = Kegiatan::where('id_kegiatan', $realId)->first();

        if ($kegiatan) {
            $kegiatan->delete();
            return redirect('/admin-area/kegiatan')->with('success', 'Berhasil menghapus agenda kegiatan.');
        } else {
            return redirect('/admin-area/kegiatan')->with('error', 'Data tidak ditemukan.');
        }
    }
}
