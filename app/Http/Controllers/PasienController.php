<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\Layanan;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentConfirmation;
use App\Mail\AppointmentAdminNotification;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PasienExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Mail\AppointmentStatusUpdate;
use Carbon\Carbon;

class PasienController extends Controller
{
    /**
     * Menyimpan data pendaftaran pasien baru dari form appointment publik.
     * #1 Fix: total_harga_pasien dan tindakan_pasien DIHAPUS dari input publik.
     * #9 Fix: Validasi tanggal janji tidak boleh di masa lalu & cek bentrok jadwal.
     */
    public function pasien_submit(Request $request)
    {
        // Anti spam honeypot
        if ($request->filled('website_hp')) {
            return redirect('/appointment')->with('sent-message', 'Pendaftaran berhasil. Kami akan menghubungi Anda segera.');
        }

        $validated = $request->validate([
            'nama_pasien'    => 'required|max:100',
            'tanggal_janji'  => 'required|date|after_or_equal:today',
            'email_pasien'   => 'required|email|max:100',
            'no_hp_pasien'   => ['required', 'regex:/^(\+62|62|0)8[0-9]{8,13}$/'],
            'alamat_pasien'  => 'required|max:255',
            'keluhan_pasien' => 'required|max:500',
            'dokter_pilihan' => 'nullable|max:80',
            'persetujuan'    => 'nullable',
        ], [
            'tanggal_janji.after_or_equal' => 'Tanggal janji temu tidak boleh di masa lalu.',
            'no_hp_pasien.regex'           => 'Format nomor HP tidak valid (contoh: 081234567890).',
        ]);

        // Cek bentrok jadwal: dokter yang sama dalam rentang < 30 menit dari janji lain
        if (!empty($validated['dokter_pilihan'])) {
            $waktu = Carbon::parse($validated['tanggal_janji']);

            $conflict = Pasien::where('dokter_pilihan', $validated['dokter_pilihan'])
                ->whereBetween('tanggal_janji', [
                    $waktu->copy()->subMinutes(29)->format('Y-m-d H:i:s'),
                    $waktu->copy()->addMinutes(29)->format('Y-m-d H:i:s'),
                ])
                ->whereIn('status', ['pending', 'confirmed'])
                ->exists();

            if ($conflict) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Dokter ' . $validated['dokter_pilihan'] . ' sudah memiliki janji pada jadwal tersebut. Silakan pilih waktu lain.');
            }

            // #23 Fix: Hubungkan via foreign key id_dokter
            $selectedDokter = Dokter::where('nama_dokter', $validated['dokter_pilihan'])->first();
            if ($selectedDokter) {
                $validated['id_dokter'] = $selectedDokter->id_dokter;
            }
        }

        // #1 Fix: Hanya admin yang menentukan harga & tindakan medis
        unset($validated['total_harga_pasien'], $validated['tindakan_pasien'], $validated['persetujuan']);
        $validated['status'] = 'pending';

        $result = Pasien::create($validated);

        if ($result) {
            // Kirim konfirmasi ke pasien
            try {
                Mail::to($result->email_pasien)->send(new AppointmentConfirmation($result));
            } catch (\Exception $e) {
                Log::warning('Email konfirmasi pasien gagal terkirim: ' . $e->getMessage());
            }

            // #37: Kirim notifikasi ke admin (satu pengiriman dengan bcc/to list)
            try {
                // Hanya ada satu role (superadmin): kirim ke semua akun ber-email.
                $adminEmails = User::whereNotNull('email')
                    ->pluck('email')
                    ->toArray();

                if (!empty($adminEmails)) {
                    Mail::to($adminEmails)->send(new AppointmentAdminNotification($result));
                }
            } catch (\Exception $e) {
                Log::warning('Email notifikasi admin gagal terkirim: ' . $e->getMessage());
            }

            Log::info('Pasien baru mendaftar', ['nama' => $result->nama_pasien, 'id' => $result->id_pasien]);

            return redirect('/appointment')->with('sent-message', 'Pendaftaran berhasil. Kami akan menghubungi Anda segera.');
        }

        return redirect('/appointment')->with('error', 'Terjadi kesalahan dalam menambahkan data pasien.');
    }

    /**
     * Mengubah status janji temu pasien (pending, confirmed, completed, cancelled).
     */
    public function pasien_status_update($id, $status)
    {
        $realId = decrypt($id);
        $validStatuses = ['pending', 'confirmed', 'completed', 'cancelled'];

        if (!in_array($status, $validStatuses, true)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $pasien = Pasien::findOrFail($realId);
        $lama   = $pasien->status;
        $pasien->status = $status;
        $pasien->save();

        $this->afterStatusChange($pasien, $lama);

        return redirect()->back()->with('success', 'Status janji temu pasien berhasil diperbarui.');
    }

    /**
     * Catat audit & kirim email ke pasien bila status berubah.
     */
    private function afterStatusChange(Pasien $pasien, ?string $lama): void
    {
        if ($lama === $pasien->status) {
            return;
        }

        Log::info('Status janji temu diubah', [
            'id_pasien' => $pasien->id_pasien,
            'dari'      => $lama,
            'ke'        => $pasien->status,
            'by_user'   => Auth::id(),
        ]);

        if (in_array($pasien->status, ['confirmed', 'completed', 'cancelled'], true) && $pasien->email_pasien) {
            try {
                Mail::to($pasien->email_pasien)->send(new AppointmentStatusUpdate($pasien));
            } catch (\Exception $e) {
                Log::warning('Email perubahan status gagal terkirim: ' . $e->getMessage());
            }
        }
    }

    /**
     * Menampilkan invoice cetak pembayaran/perawatan pasien.
     */
    public function pasien_invoice($id)
    {
        $realId = decrypt($id);
        $pasien = Pasien::findOrFail($realId);

        return view('invoice', [
            'title'  => 'Invoice Pasien — ' . $pasien->nama_pasien,
            'pasien' => $pasien,
        ]);
    }

    /**
     * Menampilkan form edit data pasien berdasarkan ID terenkripsi.
     */
    public function pasien_edit($id, $from = null)
    {
        $pasien = Pasien::where('id_pasien', decrypt($id))->firstOrFail();
        $dokters = Dokter::orderBy('nama_dokter', 'asc')->get();
        $layanans = Layanan::where('aktif', true)->orderBy('nama_layanan', 'asc')->get();

        // Riwayat Kunjungan Sebelumnya (#7) berdasarkan nomor HP atau Email
        $riwayatKunjungan = Pasien::where('id_pasien', '!=', $pasien->id_pasien)
            ->where(function ($q) use ($pasien) {
                if (!empty($pasien->no_hp_pasien)) {
                    $q->where('no_hp_pasien', $pasien->no_hp_pasien);
                }
                if (!empty($pasien->email_pasien)) {
                    $q->orWhere('email_pasien', $pasien->email_pasien);
                }
            })
            ->orderBy('tanggal_janji', 'desc')
            ->get();

        return view('admin.pasien_edit', [
            'pasien'            => $pasien,
            'dokters'           => $dokters,
            'layanans'          => $layanans,
            'riwayat_kunjungan' => $riwayatKunjungan,
            'title'             => 'Edit Data Pasien',
            'menu'              => 'pasien',
        ]);
    }

    /**
     * Menghapus data pasien (Soft Delete).
     */
    public function pasien_delete($id)
    {
        $realId = decrypt($id);
        $query  = Pasien::destroy($realId);

        if ($query) {
            return redirect('/admin-area/pasien')->with('success', 'Berhasil menghapus data pasien.');
        }

        return redirect('/admin-area/pasien')->with('error', 'Terjadi kesalahan dalam menghapus data pasien.');
    }

    /**
     * Memperbarui data pasien dari panel admin.
     * #17 & #21 Fix: Validasi id_pasien dan batasan kolom yang realistis.
     */
    public function pasien_update(Request $request)
    {
        $validated = $request->validate([
            'id_pasien'          => 'required|exists:pasien,id_pasien',
            'nama_pasien'        => 'required|max:100',
            'tanggal_janji'      => 'required',
            'email_pasien'       => 'required|email|max:100',
            'no_hp_pasien'       => 'required|max:25',
            'alamat_pasien'      => 'required|max:255',
            'keluhan_pasien'     => 'required|max:500',
            'total_harga_pasien' => 'nullable|max:100',
            'tindakan_pasien'    => 'nullable|max:255',
            'status'             => 'nullable|in:pending,confirmed,completed,cancelled',
            'dokter_pilihan'     => 'nullable|max:80',
            'id_dokter'          => 'nullable|max:50',
        ]);

        if (!empty($validated['id_dokter'])) {
            $dokter = Dokter::find($validated['id_dokter']);
            if ($dokter) {
                $validated['dokter_pilihan'] = $dokter->nama_dokter;
            }
        } elseif (!empty($validated['dokter_pilihan'])) {
            $validated['id_dokter'] = Dokter::where('nama_dokter', $validated['dokter_pilihan'])->value('id_dokter');
        }

        $idPasien = $validated['id_pasien'];
        unset($validated['id_pasien']);

        // Harga disimpan sebagai angka rupiah (buang "Rp", titik, spasi)
        if (array_key_exists('total_harga_pasien', $validated)) {
            $digits = preg_replace('/\D/', '', (string) $validated['total_harga_pasien']);
            $validated['total_harga_pasien'] = $digits === '' ? null : (int) $digits;
        }

        $pasien = Pasien::findOrFail($idPasien);
        $lama   = $pasien->status;
        $pasien->update($validated);

        $this->afterStatusChange($pasien, $lama);

        return redirect('/admin-area/pasien')->with('success', 'Berhasil mengedit data pasien.');
    }

    /**
     * Daftar pasien + pencarian + filter (status, dokter, rentang tanggal) via GET.
     */
    public function pasien_search(Request $request)
    {
        $keyword = trim((string) $request->input('cari'));
        $status  = $request->input('status');
        $dokter  = $request->input('dokter');
        $dari    = $request->input('dari');
        $sampai  = $request->input('sampai');

        $query = Pasien::query();

        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('id_pasien', 'like', "%{$keyword}%")
                  ->orWhere('nama_pasien', 'like', "%{$keyword}%")
                  ->orWhere('no_hp_pasien', 'like', "%{$keyword}%")
                  ->orWhere('email_pasien', 'like', "%{$keyword}%");
            });
        }

        if (in_array($status, ['pending', 'confirmed', 'completed', 'cancelled'], true)) {
            $query->where('status', $status);
        }

        if (!empty($dokter)) {
            $query->where('id_dokter', $dokter);
        }

        if (!empty($dari) && strtotime($dari)) {
            $query->whereDate('tanggal_janji', '>=', $dari);
        }

        if (!empty($sampai) && strtotime($sampai)) {
            $query->whereDate('tanggal_janji', '<=', $sampai);
        }

        $pasien = $query->orderBy('id_pasien', 'desc')->paginate(10)->withQueryString();

        return view('admin.pasien', [
            'title'   => 'Data Janji Temu Pasien',
            'menu'    => 'pasien',
            'pasien'  => $pasien,
            'cari'    => $keyword,
            'dokters' => Dokter::orderBy('nama_dokter')->get(['id_dokter', 'nama_dokter']),
            'filter'  => compact('status', 'dokter', 'dari', 'sampai'),
        ]);
    }

    /**
     * Mengekspor data pasien ke file Excel (.xlsx).
     */
    public function export()
    {
        return Excel::download(new PasienExport, 'Pasien_' . date('Y-m-d') . '.xlsx');
    }
}
