<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Galeri;
use App\Models\Kategori;

class GaleriController extends Controller
{
    /**
     * Menyimpan foto galeri baru ke database dan storage.
     */
    public function gallery_submit(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $idGaleri = Galeri::galleryGenerateID();
        $img      = $request->foto;
        $imgext   = $request->foto->extension();
        $imgname  = time() . '-' . $idGaleri . '.' . $imgext;

        $request->merge([
            'images'    => $imgname,
            'id_galeri' => $idGaleri,
        ]);

        if (is_null($request->kategori_new)) {
            $validated = $request->validate([
                'id_galeri'   => 'required|unique:galeri,id_galeri',
                'id_kategori' => 'nullable|exists:kategori,id_kategori',
                'judul'       => 'required|max:150',
                'deskripsi'   => 'required|max:500',
                'images'      => 'required',
            ]);

            $query = Galeri::create($validated);
        } else {
            return redirect('/admin-area/galeri')->with('error', 'Mohon pilih kategori yang sudah ada atau buat kategori terlebih dahulu.');
        }

        $img->move(public_path('/img/gallery'), $imgname);

        if ($query) {
            return redirect('/admin-area/galeri')->with('success', 'Berhasil mengunggah foto.');
        }

        return redirect('/admin-area/galeri')->with('error', 'Terjadi kesalahan dalam mengunggah foto.');
    }

    /**
     * Menampilkan form edit foto galeri berdasarkan ID terenkripsi.
     */
    public function gallery_edit($id)
    {
        $gallery = Galeri::where('id_galeri', decrypt($id))->firstOrFail();
        $kategori = Kategori::all();

        return view('admin.gallery_edit', [
            'gallery'  => [$gallery],
            'kategori' => $kategori,
            'title'    => 'Edit Foto',
            'menu'     => 'galeri',
        ]);
    }

    /**
     * Memperbarui data foto galeri yang sudah ada.
     * #17 Fix: Validasi id_galeri.
     */
    public function gallery_update(Request $request)
    {
        $request->validate([
            'id_galeri' => 'required|exists:galeri,id_galeri',
        ]);

        $galeri = Galeri::where('id_galeri', $request->id_galeri)->firstOrFail();
        $img    = $request->foto;

        if ($img != null) {
            $request->validate([
                'foto'        => 'image|mimes:jpg,jpeg,png,webp|max:2048',
                'judul'       => 'required|max:150',
                'deskripsi'   => 'required|max:500',
                'id_kategori' => 'nullable|exists:kategori,id_kategori',
            ]);

            $imgext  = $request->foto->extension();
            $imgname = time() . '-' . $request->id_galeri . '.' . $imgext;

            Galeri::deleteImage($request->id_galeri);
            $img->move(public_path('/img/gallery'), $imgname);

            $galeri->update([
                'judul'       => $request->judul,
                'deskripsi'   => $request->deskripsi,
                'id_kategori' => $request->id_kategori,
                'images'      => $imgname,
            ]);
        } else {
            $validated = $request->validate([
                'judul'       => 'required|max:150',
                'deskripsi'   => 'required|max:500',
                'id_kategori' => 'nullable|exists:kategori,id_kategori',
            ]);

            $galeri->update($validated);
        }

        return redirect('/admin-area/galeri')->with('success', 'Berhasil mengedit foto.');
    }

    /**
     * Menghapus foto galeri berdasarkan ID terenkripsi (Soft Delete).
     * #11 Fix: File gambar JANGAN dihapus saat soft-delete (hanya saat force delete di trash).
     */
    public function gallery_delete($id)
    {
        $query = Galeri::destroy(decrypt($id));

        if ($query) {
            return redirect('/admin-area/galeri')->with('success', 'Berhasil menghapus foto.');
        }

        return redirect('/admin-area/galeri')->with('error', 'Terjadi kesalahan dalam menghapus foto.');
    }

    /**
     * #12 & #13 Fix: Mencari foto galeri via GET request dengan pagination yang membawa query string.
     */
    public function gallery_search(Request $request)
    {
        $keyword = trim((string) $request->input('cari'));

        if ($keyword === '') {
            return redirect('/admin-area/galeri');
        }

        $query = Galeri::where(function ($q) use ($keyword) {
            $q->where('judul', 'like', "%{$keyword}%")
              ->orWhere('id_galeri', 'like', "%{$keyword}%")
              ->orWhere('deskripsi', 'like', "%{$keyword}%");
        })->paginate(8)->withQueryString();

        return view('admin.gallery', [
            'title'   => 'Hasil Pencarian: ' . $keyword,
            'menu'    => 'galeri',
            'gallery' => $query,
            'cari'    => $keyword,
        ]);
    }

    /**
     * Menampilkan detail kategori galeri beserta daftar fotonya.
     */
    public function kategori_details($id)
    {
        $realId   = decrypt($id);
        $kategori = Kategori::where('id_kategori', $realId)->get();
        $gallery  = Galeri::where('id_kategori', $realId)->get();

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
    public function kategori_submit(Request $request)
    {
        $idKategori = Kategori::generateID();

        $request->merge([
            'id_kategori' => $idKategori,
        ]);

        $validated = $request->validate([
            'id_kategori'   => 'required|unique:kategori,id_kategori',
            'nama_kategori' => 'required|max:100',
        ]);

        $query = Kategori::create($validated);

        if ($query) {
            return redirect('/admin-area/kategori-galeri')->with('success', 'Berhasil menambahkan kategori.');
        }

        return redirect('/admin-area/kategori-galeri')->with('error', 'Terjadi kesalahan dalam menambahkan kategori.');
    }

    /**
     * Menampilkan form edit kategori berdasarkan ID terenkripsi.
     */
    public function kategori_edit($id)
    {
        $kategori = Kategori::where('id_kategori', decrypt($id))->get();

        return view('admin.gallery_category_edit', [
            'kategori' => $kategori,
            'title'    => 'Edit Kategori Foto',
            'menu'     => 'kategori',
        ]);
    }

    /**
     * Memperbarui data kategori galeri.
     */
    public function kategori_update(Request $request)
    {
        $validated = $request->validate([
            'id_kategori'   => 'required|exists:kategori,id_kategori',
            'nama_kategori' => 'required|max:100',
        ]);

        Kategori::where('id_kategori', $request->id_kategori)->update([
            'nama_kategori' => $validated['nama_kategori'],
        ]);

        return redirect('/admin-area/kategori-galeri')->with('success', 'Berhasil mengedit kategori.');
    }

    /**
     * Menghapus kategori galeri.
     * #22 Fix: Cegah penghapusan jika kategori masih digunakan oleh foto di tabel galeri.
     */
    public function kategori_delete($id)
    {
        $realId = decrypt($id);

        if (Galeri::where('id_kategori', $realId)->exists()) {
            return redirect('/admin-area/kategori-galeri')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh beberapa foto galeri.');
        }

        $query = Kategori::where('id_kategori', $realId)->delete();

        if ($query) {
            return redirect('/admin-area/kategori-galeri')->with('success', 'Berhasil menghapus kategori.');
        }

        return redirect('/admin-area/kategori-galeri')->with('error', 'Terjadi kesalahan dalam menghapus kategori.');
    }

    /**
     * #12 & #13 Fix: Mencari data kategori via GET request dengan pagination yang membawa query string.
     */
    public function kategori_search(Request $request)
    {
        $keyword = trim((string) $request->input('cari'));

        if ($keyword === '') {
            return redirect('/admin-area/kategori-galeri');
        }

        $query = Kategori::where(function ($q) use ($keyword) {
            $q->where('nama_kategori', 'like', "%{$keyword}%")
              ->orWhere('id_kategori', 'like', "%{$keyword}%");
        })->paginate(20)->withQueryString();

        return view('admin.gallery_category', [
            'title'    => 'Hasil Pencarian: ' . $keyword,
            'menu'     => 'kategori',
            'category' => $query,
            'cari'     => $keyword,
        ]);
    }
}
