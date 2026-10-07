<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * R3: Controller untuk manajemen layanan/perawatan gigi klinik.
 */
class LayananController extends Controller
{
    /**
     * Daftar semua layanan, bisa diurutkan ulang.
     */
    public function index()
    {
        $layanans = Layanan::orderBy('urutan')->paginate(10);

        return view('admin.layanan', [
            'title'    => 'Manajemen Layanan Klinik',
            'menu'     => 'layanan',
            'layanans' => $layanans,
        ]);
    }

    /**
     * Form tambah layanan baru.
     */
    public function create()
    {
        return view('admin.layanan_new', [
            'title' => 'Tambah Layanan Baru',
            'menu'  => 'layanan',
        ]);
    }

    /**
     * Simpan layanan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|max:150',
            'deskripsi'    => 'nullable',
            'harga_mulai'  => 'nullable|numeric',
            'harga_sampai' => 'nullable|numeric|gte:harga_mulai',
            'durasi'       => 'nullable|max:50',
            'ikon'         => 'nullable|max:50',
            'urutan'       => 'nullable|integer|min:0',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $id      = Layanan::generateID();
        $imgname = null;

        if ($request->hasFile('foto')) {
            $imgext  = $request->foto->extension();
            $imgname = time() . '-' . $id . '.' . $imgext;
            $request->foto->move(public_path('/img/layanan'), $imgname);
        }

        Layanan::create([
            'id_layanan'   => $id,
            'nama_layanan' => $request->nama_layanan,
            'deskripsi'    => $request->deskripsi,
            'harga_mulai'  => $request->harga_mulai,
            'harga_sampai' => $request->harga_sampai,
            'durasi'       => $request->durasi,
            'ikon'         => $request->ikon ?: 'bx-plus-medical',
            'images'       => $imgname,
            'aktif'        => $request->has('aktif'),
            'urutan'       => $request->urutan ?? 0,
        ]);

        Log::info('Layanan baru ditambahkan', ['id' => $id, 'oleh' => Auth::id()]);

        return redirect('/admin-area/layanan')->with('success', 'Berhasil menambahkan layanan.');
    }

    /**
     * Form edit layanan.
     */
    public function edit($id)
    {
        $layanan = Layanan::where('id_layanan', decrypt($id))->firstOrFail();

        return view('admin.layanan_edit', [
            'title'   => 'Edit Layanan',
            'menu'    => 'layanan',
            'layanan' => $layanan,
        ]);
    }

    /**
     * Update data layanan.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id_layanan'   => 'required|exists:layanans,id_layanan',
            'nama_layanan' => 'required|max:150',
            'deskripsi'    => 'nullable',
            'harga_mulai'  => 'nullable|numeric',
            'harga_sampai' => 'nullable|numeric|gte:harga_mulai',
            'durasi'       => 'nullable|max:50',
            'ikon'         => 'nullable|max:50',
            'urutan'       => 'nullable|integer|min:0',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $layanan = Layanan::where('id_layanan', $request->id_layanan)->firstOrFail();

        $data = [
            'nama_layanan' => $request->nama_layanan,
            'deskripsi'    => $request->deskripsi,
            'harga_mulai'  => $request->harga_mulai,
            'harga_sampai' => $request->harga_sampai,
            'durasi'       => $request->durasi,
            'ikon'         => $request->ikon,
            'aktif'        => $request->has('aktif'),
            'urutan'       => $request->urutan ?? 0,
        ];

        if ($request->hasFile('foto')) {
            Layanan::deleteImage($request->id_layanan);
            $imgext  = $request->foto->extension();
            $imgname = time() . '-' . $request->id_layanan . '.' . $imgext;
            $request->foto->move(public_path('/img/layanan'), $imgname);
            $data['images'] = $imgname;
        }

        $layanan->update($data);

        return redirect('/admin-area/layanan')->with('success', 'Berhasil mengedit layanan.');
    }

    /**
     * Hapus layanan (permanent — tidak ada soft delete karena layanan tidak kritis).
     */
    public function destroy($id)
    {
        $layanan = Layanan::where('id_layanan', decrypt($id))->firstOrFail();
        Layanan::deleteImage(decrypt($id));
        $layanan->delete();

        return redirect('/admin-area/layanan')->with('success', 'Layanan berhasil dihapus.');
    }

    /**
     * Toggle aktif/nonaktif layanan.
     */
    public function toggleAktif($id)
    {
        $layanan = Layanan::where('id_layanan', decrypt($id))->firstOrFail();
        $layanan->aktif = !$layanan->aktif;
        $layanan->save();

        $status = $layanan->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Layanan berhasil {$status}.");
    }
}
