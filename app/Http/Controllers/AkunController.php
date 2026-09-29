<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AkunController extends Controller
{
    /**
     * Menyimpan akun admin baru ke database.
     * P1-Fix: Tambah validasi tipe & ukuran file foto profil.
     * P4-Fix: Tambah PHPDoc.
     */
    public function account_submit(Request $request) {
        // P1: Validasi file foto profil sebelum proses
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $img     = $request->foto;
        $imgext  = $request->foto->extension();
        $imgname = time().'-'.User::generateID().'.'.$imgext;

        $request->merge([
            'id'           => User::generateID(),
            'profile_pict' => $imgname,
        ]);

        $validated = $request->validate([
            'id'           => 'required|unique:users',
            'name'         => 'required|max:255',
            'email'        => 'required|email|unique:users',
            'password'     => 'required_with:retype_password|same:retype_password|min:8|max:255',
            'profile_pict' => 'required',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $query = User::create($validated);

        $img->move(public_path('/img/account'), $imgname);

        if ($query == true) {
            return redirect('/admin-area/akun')->with('success', 'Berhasil menambahkan data akun.');
        } else {
            return redirect('/admin-area/akun')->with('error', 'Terjadi kesalahan dalam menambahkan data akun.');
        }
    }

    /**
     * Menampilkan form edit akun admin berdasarkan ID terenkripsi.
     *
     * @param string $id   ID akun terenkripsi
     * @param bool   $from true = dari halaman detail profil, false = dari daftar akun
     */
    public function account_edit($id, $from) {
        $account = User::where('id', decrypt($id))->get();

        if ($from == true) {
            return view('admin.account_edit', [
                'title'   => 'Edit Data Pengguna',
                'menu'    => 'pengguna',
                'account' => $account,
                'admin'   => true,
            ]);
        } else {
            return view('admin.account_edit', [
                'title'   => 'Edit Data Pengguna',
                'menu'    => 'pengguna',
                'account' => $account,
                'admin'   => false,
            ]);
        }
    }

    /**
     * Memperbarui data akun admin: password, foto profil, atau informasi umum.
     * P1-Fix: Tambah validasi tipe file foto profil.
     */
    public function account_update(Request $request) {
        $img         = $request->foto;
        $pass_check  = $request->password;
        $query_check = User::where('id', $request->id)->get();

        if ($pass_check != null) {
            // Update password
            if (Hash::check($request->old_password, $query_check[0]->password)) {
                $validated = $request->validate([
                    'password' => 'required_with:retype_password|same:retype_password|min:8|max:255',
                ]);

                $validated['password'] = Hash::make($validated['password']);
            } else {
                return redirect()->back()->with('error_pass', 'Sandi tidak sama dengan database');
            }
        } elseif ($img != null) {
            // P1: Validasi tipe file foto profil
            $request->validate([
                'foto' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            $imgext  = $request->foto->extension();
            $imgname = time().'-'.$request->id.'.'.$imgext;

            $request->merge([
                'profile_pict' => $imgname,
            ]);

            $validated = $request->validate([
                'name'         => 'required|max:255',
                'email'        => ['required', 'email', Rule::unique('users')->ignore($request->id)],
                'profile_pict' => 'required',
            ]);

            User::deleteImage($request->id);
            $img->move(public_path('/img/account'), $imgname);
        } else {
            // Update informasi umum (nama & email)
            $validated = $request->validate([
                'name'  => 'required|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($request->id)],
            ]);
        }

        $query = User::where('id', $request->id)->update($validated);

        if ($query == true) {
            return redirect('/admin-area/akun')->with('success', 'Berhasil mengedit data akun.');
        } else {
            return redirect('/admin-area/akun')->with('error', 'Terjadi kesalahan dalam mengedit data akun.');
        }
    }

    /**
     * Menghapus akun admin beserta foto profil-nya.
     * Jika yang dihapus adalah akun sendiri, akan logout otomatis.
     */
    public function account_delete($id, $from) {
        User::deleteImage(decrypt($id));

        $query = User::destroy(decrypt($id));

        if ($query == true) {
            if ($from == false) {
                return redirect('/admin-area/akun')->with('success', 'Berhasil menghapus data akun.');
            } else {
                return redirect('/logout')->with('msg', 'deleted');
            }
        } else {
            return redirect('/admin-area/akun')->with('error', 'Terjadi kesalahan dalam menghapus data akun.');
        }
    }

    /**
     * Mencari akun admin berdasarkan nama, email, atau ID.
     */
    public function account_search(Request $request) {
        $request->merge([
            'cari' => '%'.$request->cari.'%',
        ]);

        $validated = $request->validate([
            'cari' => 'required',
        ]);

        $query = User::where('name', 'like', $validated)
                     ->orWhere('email', 'like', $validated)
                     ->orWhere('id', 'like', $validated)
                     ->paginate(8);

        if ($query == true) {
            if (count($query) == 0) {
                return redirect()->back()->with('message', 'Akun tidak ditemukan.');
            } else {
                return view('admin.account', [
                    'title'   => 'Hasil Pencarian Akun : '.$request->cari,
                    'menu'    => 'pengguna',
                    'account' => $query,
                ]);
            }
        } else {
            return redirect()->back()->with('message', 'Terjadi kesalahan dalam pencarian akun.');
        }
    }

    /**
     * Memproses login admin.
     * P1-Fix: Throttle sudah ditambahkan di route (throttle:5,1).
     * P2-Fix (sebelumnya): Validasi email:dns diganti email agar tidak gagal karena DNS lookup.
     */
    public function login(Request $request) {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin-area');
        }

        return back()->with('message', 'E-Mail / Sandi yang anda masukkan salah.');
    }

    /**
     * Memproses logout admin dan mengakhiri sesi.
     */
    public function logout() {
        Auth::logout();

        session()->invalidate();

        session()->regenerateToken();

        if (session()->has('msg')) {
            return redirect('/login')->with('message', 'Penghapusan akun berhasil.');
        } else {
            return redirect('/login');
        }
    }

    /**
     * Menampilkan halaman detail akun yang sedang login.
     */
    public function account_detail() {
        $account = User::where('id', Auth::user()->id)->get();

        return view('admin.account_details', [
            'title'   => 'Detail Akun',
            'menu'    => 'pengguna',
            'account' => $account,
        ]);
    }

    /**
     * Menampilkan halaman pengaturan akun yang sedang login.
     */
    public function settings() {
        return view('admin.settings', [
            'title' => 'Pengaturan Akun',
            'menu'  => 'pengaturan',
            'user'  => Auth::user(),
        ]);
    }

    /**
     * Memperbarui profil / password akun yang sedang login dari halaman Pengaturan.
     */
    public function settings_update(Request $request) {
        $user = Auth::user();

        $request->validate([
            'name'         => 'required|max:255',
            'email'        => 'required|email|unique:users,email,'.$user->id.',id',
            'old_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|same:confirm_password',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->filled('old_password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return redirect()->back()->with('error', 'Kata sandi lama tidak cocok.');
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('foto')) {
            User::deleteImage($user->id);
            $img     = $request->foto;
            $imgext  = $img->extension();
            $imgname = time().'-'.$user->id.'.'.$imgext;
            $img->move(public_path('/img/account'), $imgname);
            $user->profile_pict = $imgname;
        }

        $user->save();

        return redirect('/admin-area/pengaturan')->with('success', 'Pengaturan profil berhasil diperbarui.');
    }
}

