<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentConfirmation;
use App\Mail\AppointmentAdminNotification;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PasienExport;
use Illuminate\Support\Facades\Log;

class PasienController extends Controller
{
    /**
     * Menyimpan data pendaftaran pasien baru dari form appointment publik.
     */
    public function pasien_submit(Request $request) {
        $validated = $request->validate([
            'nama_pasien'        => 'required|max:50',
            'tanggal_janji'      => 'required|date',
            'email_pasien'       => 'required|email',
            'no_hp_pasien'       => 'required|max:20',
            'alamat_pasien'      => 'required|max:50',
            'keluhan_pasien'     => 'required|max:300',
            'dokter_pilihan'     => 'nullable|max:80',
            'total_harga_pasien' => 'nullable',
            'tindakan_pasien'    => 'nullable',
        ]);

        $validated['status'] = 'pending';

        $result = Pasien::create($validated);

        if ($result) {
            // Kirim konfirmasi ke pasien
            try {
                Mail::to($result->email_pasien)
                    ->send(new AppointmentConfirmation($result));
            } catch (\Exception $e) {
                Log::warning('Email konfirmasi pasien gagal terkirim: ' . $e->getMessage());
            }

            // R1: Kirim notifikasi ke semua akun superadmin
            try {
                $admins = User::where('role', 'superadmin')->get();
                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(new AppointmentAdminNotification($result));
                }
            } catch (\Exception $e) {
                Log::warning('Email notifikasi admin gagal terkirim: ' . $e->getMessage());
            }

            Log::info('Pasien baru mendaftar', ['nama' => $result->nama_pasien, 'id' => $result->id_pasien]);

            return redirect('/appointment')->with('sent-message', 'Pendaftaran berhasil. Kami akan menghubungi Anda segera.');
        } else {
            return redirect('/appointment')->with('error', 'Terjadi kesalahan dalam menambahkan data pasien.');
        }
    }

    /**
     * Mengubah status janji temu pasien (pending, confirmed, completed, cancelled).
     */
    public function pasien_status_update($id, $status) {
        $realId = decrypt($id);
        $validStatuses = ['pending', 'confirmed', 'completed', 'cancelled'];

        if (!in_array($status, $validStatuses)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $pasien = Pasien::findOrFail($realId);
        $pasien->status = $status;
        $pasien->save();

        return redirect()->back()->with('success', 'Status janji temu pasien berhasil diperbarui.');
    }

    /**
     * Menampilkan invoice cetak pembayaran/perawatan pasien.
     */
    public function pasien_invoice($id) {
        $realId = decrypt($id);
        $pasien = Pasien::findOrFail($realId);

        return view('invoice', [
            'title'  => 'Invoice Pasien — '.$pasien->nama_pasien,
            'pasien' => $pasien,
        ]);
    }

    /**
     * Menampilkan form edit data pasien berdasarkan ID terenkripsi.
     * K11 Fix: Gunakan firstOrFail() bukan get() karena hanya butuh 1 record.
     */
    public function pasien_edit($id) {
        $pasien = Pasien::where('id_pasien', decrypt($id))->firstOrFail();

        return view('admin.pasien_edit', [
            'pasien' => $pasien,
            'title'  => 'Edit Data Pasien',
            'menu'   => 'pasien',
        ]);
    }

    /**
     * Menghapus data pasien (Soft Delete).
     */
    public function pasien_delete($id) {
        $query = Pasien::destroy(decrypt($id));

        if ($query) {
            return redirect('/admin-area/pasien')->with('success', 'Berhasil menghapus data pasien.');
        } else {
            return redirect('/admin-area/pasien')->with('error', 'Terjadi kesalahan dalam menghapus data pasien.');
        }
    }

    /**
     * Memperbarui data pasien.
     */
    public function pasien_update(Request $request) {
        $query = $request->validate([
            'nama_pasien'        => 'required|max:50',
            'tanggal_janji'      => 'required',
            'email_pasien'       => 'required|email',
            'no_hp_pasien'       => 'required',
            'alamat_pasien'      => 'required|max:50',
            'keluhan_pasien'     => 'required|max:300',
            'total_harga_pasien' => 'nullable',
            'tindakan_pasien'    => 'nullable',
            'status'             => 'nullable',
            'dokter_pilihan'     => 'nullable',
        ]);

        $query = Pasien::where('id_pasien', $request->id_pasien)->update($query);

        if ($query) {
            return redirect('/admin-area/pasien')->with('success', 'Berhasil mengedit data pasien.');
        } else {
            return redirect('/admin-area/pasien')->with('error', 'Terjadi kesalahan dalam mengedit data pasien.');
        }
    }

    /**
     * Mencari data pasien berdasarkan ID, nama, atau nomor HP.
     */
    public function pasien_search(Request $request) {
        $request->merge([
            'cari' => '%'.$request->cari.'%',
        ]);

        $validated = $request->validate([
            'cari' => 'required',
        ]);

        $query = Pasien::where('id_pasien', 'like', $validated)
                       ->orWhere('nama_pasien', 'like', $validated)
                       ->orWhere('no_hp_pasien', 'like', $validated)
                       ->paginate(8);

        if ($query->isNotEmpty()) {
            return view('admin.pasien', [
                'title'  => 'Hasil Pencarian : '.$request->cari,
                'menu'   => 'pasien',
                'pasien' => $query,
            ]);
        } else {
            return redirect()->back()->with('message', 'Data pasien tidak ditemukan.');
        }
    }

    /**
     * Mengekspor data pasien ke file Excel (.xlsx).
     */
    public function export() {
        return Excel::download(new PasienExport, 'Pasien.xlsx');
    }
}
