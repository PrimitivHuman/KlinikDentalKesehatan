<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * R2: Controller untuk manajemen artikel/berita klinik.
 */
class BeritaController extends Controller
{
    /**
     * Tampilkan semua artikel (paginate 10).
     */
    public function index()
    {
        $beritas = Berita::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.berita', [
            'title'   => 'Manajemen Artikel / Berita',
            'menu'    => 'berita',
            'beritas' => $beritas,
        ]);
    }

    /**
     * Form tambah artikel baru.
     */
    public function create()
    {
        return view('admin.berita_new', [
            'title' => 'Tambah Artikel Baru',
            'menu'  => 'berita',
        ]);
    }

    /**
     * Simpan artikel baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'required|max:200',
            'isi'       => 'required',
            'status'    => 'required|in:draft,published',
            'tgl_terbit'=> 'nullable|date',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $id   = Berita::generateID();
        $slug = Berita::generateSlug($request->judul);

        $imgname = null;
        if ($request->hasFile('foto')) {
            $imgext  = $request->foto->extension();
            $imgname = time() . '-' . $id . '.' . $imgext;
            $request->foto->move(public_path('/img/berita'), $imgname);
        }

        Berita::create([
            'id_berita'  => $id,
            'judul'      => $request->judul,
            'slug'       => $slug,
            'isi'        => $request->isi,
            'images'     => $imgname,
            'penulis'    => Auth::user()->name,
            'status'     => $request->status,
            'tgl_terbit' => $request->tgl_terbit ?: now()->toDateString(),
        ]);

        Log::info('Artikel baru dibuat', ['id' => $id, 'judul' => $request->judul, 'oleh' => Auth::id()]);

        return redirect('/admin-area/berita')->with('success', 'Berhasil menambahkan artikel.');
    }

    /**
     * Form edit artikel berdasarkan ID terenkripsi.
     */
    public function edit($id)
    {
        $berita = Berita::where('id_berita', decrypt($id))->firstOrFail();

        return view('admin.berita_edit', [
            'title'  => 'Edit Artikel',
            'menu'   => 'berita',
            'berita' => $berita,
        ]);
    }

    /**
     * Update data artikel.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id_berita'  => 'required',
            'judul'      => 'required|max:200',
            'isi'        => 'required',
            'status'     => 'required|in:draft,published',
            'tgl_terbit' => 'nullable|date',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $berita = Berita::where('id_berita', $request->id_berita)->firstOrFail();

        $data = [
            'judul'      => $request->judul,
            'isi'        => $request->isi,
            'status'     => $request->status,
            'tgl_terbit' => $request->tgl_terbit,
        ];

        if ($request->hasFile('foto')) {
            Berita::deleteImage($request->id_berita);
            $imgext  = $request->foto->extension();
            $imgname = time() . '-' . $request->id_berita . '.' . $imgext;
            $request->foto->move(public_path('/img/berita'), $imgname);
            $data['images'] = $imgname;
        }

        $berita->update($data);

        return redirect('/admin-area/berita')->with('success', 'Berhasil mengedit artikel.');
    }

    /**
     * Hapus artikel (soft delete).
     */
    public function destroy($id)
    {
        $berita = Berita::where('id_berita', decrypt($id))->firstOrFail();
        $berita->delete();

        Log::info('Artikel dihapus (soft delete)', ['id' => decrypt($id), 'oleh' => Auth::id()]);

        return redirect('/admin-area/berita')->with('success', 'Artikel berhasil dihapus.');
    }

    /**
     * Ubah status artikel: draft ↔ published langsung dari list.
     */
    public function toggleStatus($id)
    {
        $berita = Berita::where('id_berita', decrypt($id))->firstOrFail();
        $berita->status = $berita->status === 'published' ? 'draft' : 'published';
        $berita->save();

        return redirect()->back()->with('success', 'Status artikel berhasil diubah.');
    }
}
