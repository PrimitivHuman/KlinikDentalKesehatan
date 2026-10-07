<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class AkunController extends Controller
{
    /**
     * Menyimpan akun admin baru ke database.
     * Saat ini hanya ada satu role: superadmin (otomatis, tanpa pilihan role).
     */
    public function account_submit(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $idUser  = User::generateID();
        $img     = $request->foto;
        $imgext  = $request->foto->extension();
        $imgname = time() . '-' . $idUser . '.' . $imgext;

        $request->merge([
            'id'           => $idUser,
            'profile_pict' => $imgname,
        ]);

        $validated = $request->validate([
            'id'           => 'required|unique:users,id',
            'name'         => 'required|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required_with:retype_password|same:retype_password|min:8|max:255',
            'profile_pict' => 'required',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role']     = 'superadmin';

        $query = User::create($validated);

        $img->move(public_path('/img/account'), $imgname);

        if ($query) {
            return redirect('/admin-area/akun')->with('success', 'Berhasil menambahkan data akun.');
        }

        return redirect('/admin-area/akun')->with('error', 'Terjadi kesalahan dalam menambahkan data akun.');
    }

    /**
     * Menampilkan form edit akun admin berdasarkan ID terenkripsi.
     */
    public function account_edit($id, $from)
    {
        $account = User::where('id', decrypt($id))->get();

        return view('admin.account_edit', [
            'title'   => 'Edit Data Pengguna',
            'menu'    => 'pengguna',
            'account' => $account,
            'admin'   => (bool) $from,
        ]);
    }

    /**
     * Memperbarui data akun admin: password, foto profil, atau informasi umum.
     * Role tidak dapat diubah (hanya superadmin).
     */
    public function account_update(Request $request)
    {
        $targetUser = User::where('id', $request->id)->firstOrFail();
        $img        = $request->foto;
        $pass_check = $request->password;

        if ($pass_check != null) {
            if (Hash::check($request->old_password, $targetUser->password)) {
                $validated = $request->validate([
                    'password' => 'required_with:retype_password|same:retype_password|min:8|max:255',
                ]);

                $targetUser->password = Hash::make($validated['password']);
                $targetUser->save();
            } else {
                return redirect()->back()->with('error_pass', 'Sandi tidak sama dengan database');
            }
        } elseif ($img != null) {
            $request->validate([
                'foto'  => 'image|mimes:jpg,jpeg,png,webp|max:2048',
                'name'  => 'required|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($request->id)],
            ]);

            $imgext  = $request->foto->extension();
            $imgname = time() . '-' . $request->id . '.' . $imgext;

            User::deleteImage($request->id);
            $img->move(public_path('/img/account'), $imgname);

            $targetUser->name         = $request->name;
            $targetUser->email        = $request->email;
            $targetUser->profile_pict = $imgname;

            $targetUser->save();
        } else {
            $validated = $request->validate([
                'name'  => 'required|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($request->id)],
            ]);

            $targetUser->name  = $validated['name'];
            $targetUser->email = $validated['email'];

            $targetUser->save();
        }

        return redirect('/admin-area/akun')->with('success', 'Berhasil mengedit data akun.');
    }

    /**
     * Menghapus akun admin beserta foto profil-nya.
     * #15 Fix: Cegah penghapusan diri sendiri dan superadmin terakhir.
     */
    public function account_delete($id, $from)
    {
        $realId = decrypt($id);

        if ($realId === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $target = User::findOrFail($realId);

        if ($target->role === 'superadmin' && User::where('role', 'superadmin')->count() <= 1) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus superadmin terakhir pada sistem.');
        }

        User::deleteImage($realId);
        $query = User::destroy($realId);

        if ($query) {
            return redirect('/admin-area/akun')->with('success', 'Berhasil menghapus data akun.');
        }

        return redirect('/admin-area/akun')->with('error', 'Terjadi kesalahan dalam menghapus data akun.');
    }

    /**
     * #12 & #13 Fix: Mencari akun admin via GET request dengan pagination yang membawa query string.
     */
    public function account_search(Request $request)
    {
        $keyword = trim((string) $request->input('cari'));

        if ($keyword === '') {
            return redirect('/admin-area/akun');
        }

        $query = User::where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('email', 'like', "%{$keyword}%")
              ->orWhere('id', 'like', "%{$keyword}%")
              ->orWhere('role', 'like', "%{$keyword}%");
        })->paginate(8)->withQueryString();

        return view('admin.account', [
            'title'   => 'Hasil Pencarian Akun: ' . $keyword,
            'menu'    => 'pengguna',
            'account' => $query,
            'cari'    => $keyword,
        ]);
    }

    /**
     * Memproses login admin.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            Log::info('Admin login successful', ['email' => $request->email, 'ip' => $request->ip()]);
            return redirect()->intended('/admin-area');
        }

        Log::warning('Admin login failed', ['email' => $request->email, 'ip' => $request->ip()]);
        return back()->with('message', 'E-Mail / Sandi yang anda masukkan salah.');
    }

    /**
     * Memproses logout admin dan mengakhiri sesi.
     */
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        if (session()->has('msg')) {
            return redirect('/login')->with('message', 'Penghapusan akun berhasil.');
        }

        return redirect('/login');
    }

    /**
     * Menampilkan halaman detail akun yang sedang login.
     */
    public function account_detail()
    {
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
    public function settings()
    {
        return view('admin.settings', [
            'title' => 'Pengaturan Akun',
            'menu'  => 'pengaturan',
            'user'  => Auth::user(),
        ]);
    }

    /**
     * Memperbarui profil / password akun yang sedang login dari halaman Pengaturan.
     */
    public function settings_update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'         => 'required|max:255',
            'email'        => 'required|email|unique:users,email,' . $user->id . ',id',
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
            $imgname = time() . '-' . $user->id . '.' . $imgext;
            $img->move(public_path('/img/account'), $imgname);
            $user->profile_pict = $imgname;
        }

        $user->save();

        return redirect('/admin-area/pengaturan')->with('success', 'Pengaturan profil berhasil diperbarui.');
    }
}
