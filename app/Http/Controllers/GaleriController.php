<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Galeri;

class GaleriController extends Controller
{
    /**
     * Menyimpan foto galeri baru ke database dan storage.
     * P1-Fix: Tambah validasi tipe file gambar.
     * P2-Fix: Inisialisasi $query = false agar tidak undefined.
     *         Tambah id_kategori & nama_kategori ke validated fields.
     */
    public function gallery_submit(Request $request) {
        // P1: Validasi file gambar sebelum proses
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $img    = $request->foto;
        $imgext = $request->foto->extension();
        $imgname = time().'-'.Galeri::galleryGenerateID().'.'.$imgext;

        $request->merge([
            'images'    => $imgname,
            'id_galeri' => Galeri::galleryGenerateID(),
        ]);

        // P2: Inisialisasi $query agar tidak undefined jika kondisi tidak terpenuhi
        $query = false;

        // Jika tidak membuat kategori baru, langsung simpan foto ke galeri
        if (is_null($request->kategori_new)) {
            $validated = $request->validate([
                'id_galeri'   => 'required|unique:galeri',
                'id_kategori' => 'nullable',
                'nama_kategori' => 'nullable',
                'judul'       => 'required|max:100',
                'deskripsi'   => 'required|max:300',
                'images'      => 'required',
            ]);

            $query = Galeri::insert($validated);
        } else {
            // Jika kategori baru diisi, tampilkan pesan bahwa fitur belum sepenuhnya diimplementasikan
            return redirect('/admin-area/galeri')->with('error', 'Mohon pilih kategori yang sudah ada.');
        }

        $img->move(public_path('/img/gallery'), $imgname);

        if ($query) {
            return redirect('/admin-area/galeri')->with('success', 'Berhasil mengunggah foto.');
        } else {
            return redirect('/admin-area/galeri')->with('error', 'Terjadi kesalahan dalam mengunggah foto.');
        }
    }

    /**
     * Menampilkan form edit foto galeri berdasarkan ID terenkripsi.
     */
    public function gallery_edit($id) {
        $gallery = Galeri::vgaleri()->where('id_galeri', decrypt($id))->get();

        return view('admin.gallery_edit', [
            'gallery' => $gallery,
            'title'   => 'Edit Foto',
            'menu'    => 'galeri'
        ]);
    }

    /**
     * Memperbarui data foto galeri yang sudah ada.
     * P1-Fix: Tambah validasi tipe file gambar jika ada file baru.
     */
    public function gallery_update(Request $request) {
        $img = $request->foto;

        if ($img != null) {
            // P1: Validasi tipe file gambar
            $request->validate([
                'foto' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            $imgext  = $request->foto->extension();
            $imgname = time().'-'.$request->id_galeri.'.'.$imgext;

            $request->merge([
                'images' => $imgname,
            ]);

            $validated = $request->validate([
                'judul'     => 'required|max:100',
                'deskripsi' => 'required|max:300',
                'images'    => 'required',
            ]);

            Galeri::deleteImage($request->id_galeri);

            $query = Galeri::where('id_galeri', $request->id_galeri)->update($validated);

            $img->move(public_path('/img/gallery'), $imgname);
        } else {
            $validated = $request->validate([
                'judul'     => 'required|max:100',
                'deskripsi' => 'required|max:300',
            ]);

            $query = Galeri::where('id_galeri', $request->id_galeri)->update($validated);
        }

        if ($query) {
            return redirect('/admin-area/galeri')->with('success', 'Berhasil mengedit foto.');
        } else {
            return redirect('/admin-area/galeri')->with('error', 'Terjadi kesalahan dalam mengedit foto.');
        }
    }

    /**
     * Menghapus foto galeri berdasarkan ID terenkripsi.
     * Dengan SoftDeletes aktif, data tidak langsung terhapus permanen.
     */
    public function gallery_delete($id) {
        Galeri::deleteImage(decrypt($id));

        $query = Galeri::destroy(decrypt($id));

        if ($query) {
            return redirect('/admin-area/galeri')->with('success', 'Berhasil menghapus foto.');
        } else {
            return redirect('/admin-area/galeri')->with('error', 'Terjadi kesalahan dalam menghapus foto.');
        }
    }

    /**
     * Mencari data galeri berdasarkan judul atau ID galeri.
     */
    public function gallery_search(Request $request) {
        $request->merge([
            'cari' => '%'.$request->cari.'%',
        ]);

        $validated = $request->validate([
            'cari' => 'required',
        ]);

        $query = Galeri::vgaleri()->where('judul', 'like', $validated)->orWhere('id_galeri', 'like', $validated)->paginate(8);

        if ($query->isNotEmpty()) {
            return view('admin.gallery', [
                'title'   => 'Hasil Pencarian : '.$request->cari,
                'menu'    => 'galeri',
                'gallery' => $query,
            ]);
        } else {
            return redirect()->back()->with('message', 'Data galeri tidak ditemukan.');
        }
    }

    /**
     * Menampilkan detail kategori galeri.
     */
    public function kategori_details($id) {
        $kategori = Galeri::kategori()->where('id_kategori', decrypt($id))->get();
        $gallery  = Galeri::where('id_kategori', decrypt($id))->get();

        return view('admin.gallery_category_details', [
            'kategori' => $kategori,
            'gallery'  => $gallery,
            'title'    => 'Detail Kategori Foto',
            'menu'     => 'kategori',
        ]);
    }

    /**
     * Menyimpan kategori galeri baru.
     */
    public function kategori_submit(Request $request) {
        $request->merge([
            'id_kategori' => Galeri::categoryGenerateID(),
        ]);

        $validated = $request->validate([
            'id_kategori'   => 'required|unique:kategori',
            'nama_kategori' => 'required|max:50',
        ]);

        $query = Galeri::kategori()->insert($validated);

        if ($query) {
            return redirect('/admin-area/kategori-galeri')->with('success', 'Berhasil menambahkan kategori.');
        } else {
            return redirect('/admin-area/kategori-galeri')->with('error', 'Terjadi kesalahan dalam menambahkan kategori.');
        }
    }

    /**
     * Menampilkan form edit kategori berdasarkan ID terenkripsi.
     */
    public function kategori_edit($id) {
        $kategori = Galeri::kategori()->where('id_kategori', decrypt($id))->get();

        return view('admin.gallery_category_edit', [
            'kategori' => $kategori,
            'title'    => 'Edit Kategori Foto',
            'menu'     => 'kategori',
        ]);
    }

    /**
     * Memperbarui data kategori galeri.
     */
    public function kategori_update(Request $request) {
        $validated = $request->validate([
            'nama_kategori' => 'required|max:50',
        ]);

        $query = Galeri::kategori()->where('id_kategori', $request->id_kategori)->update($validated);

        if ($query) {
            return redirect('/admin-area/kategori-galeri')->with('success', 'Berhasil mengedit kategori.');
        } else {
            return redirect('/admin-area/kategori-galeri')->with('error', 'Terjadi kesalahan dalam mengedit kategori.');
        }
    }

    /**
     * Menghapus kategori galeri berdasarkan ID terenkripsi.
     */
    public function kategori_delete($id) {
        $query = Galeri::kategori()->where('id_kategori', decrypt($id))->delete();

        if ($query) {
            return redirect('/admin-area/kategori-galeri')->with('success', 'Berhasil menghapus kategori.');
        } else {
            return redirect('/admin-area/kategori-galeri')->with('error', 'Terjadi kesalahan dalam menghapus kategori.');
        }
    }

    /**
     * Mencari data kategori galeri.
     */
    public function kategori_search(Request $request) {
        $request->merge([
            'cari' => '%'.$request->cari.'%',
        ]);

        $validated = $request->validate([
            'cari' => 'required',
        ]);

        $query = Galeri::kategori()->where('nama_kategori', 'like', $validated)->orWhere('id_kategori', 'like', $validated)->paginate(20);

        if ($query->isNotEmpty()) {
            return view('admin.gallery_category', [
                'title'    => 'Hasil Pencarian : '.$request->cari,
                'menu'     => 'kategori',
                'category' => $query,
            ]);
        } else {
            return redirect()->back()->with('message', 'Data kategori tidak ditemukan.');
        }
    }
}
