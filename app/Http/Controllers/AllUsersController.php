<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use App\Models\ModelHasRole;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;

class AllUsersController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get(); // Mengambil semua pengguna
        return view('menus.userManagement.allUsers', compact('users'));
    }

    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'profile_pic' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk file gambar max 2MB
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'telepon' => ['required', 'numeric', 'unique:users,contact_info'],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Inisialisasi variabel untuk path gambar
        $profilePicPath = null;

        // Cek apakah ada file gambar yang di-upload
        if ($request->hasFile('profile_pic')) {
            // Simpan file gambar ke folder 'profiles' di storage publik
            $image = $request->file('profile_pic');
            $path = $image->store('profiles', 'public');
            $profilePicPath = 'storage/' . $path; // Buat path relatif yang akan disimpan di database
        }

        // Simpan data ke database (hanya path gambar yang disimpan)
        $user = User::create([
            'profile_picture' => $profilePicPath, // Simpan path gambar
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'contact_info' => $request->telepon,
            'address' => $request->address,
            'password' => Hash::make($request->password), // Enkripsi password
        ]);

        // Beri notifikasi sukses dan redirect kembali
        notify()->success('User was added successfully! ✍️', 'Success!');
        return redirect()->back();
    }
    public function update(Request $request, $id)
    {
        // Ambil ID user dari input
        $user = User::findOrFail($id);

        // Validasi input
        $request->validate([
            'profile_pic' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'telepon' => ['required', 'numeric', 'unique:users,contact_info,' . $id],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Simpan path gambar
        $profilePicPath = $user->profile_picture;

        // Cek apakah ada file gambar yang diupload
        if ($request->hasFile('profile_pic')) {
            // Hapus gambar lama jika ada
            if ($profilePicPath && file_exists(public_path($profilePicPath))) {
                unlink(public_path($profilePicPath));
            }
            // Simpan gambar baru
            $image = $request->file('profile_pic');
            $path = $image->store('profiles', 'public');
            $profilePicPath = 'storage/' . $path; // Simpan path di database
        }

        // Update data user
        $user->update([
            'profile_picture' => $profilePicPath,
            'name' => $request->name,
            'email' => $request->email,
            'contact_info' => $request->telepon,
            'address' => $request->address,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        // Notifikasi sukses dan redirect
        notify()->success('User updated successfully! 👌', 'Success!');
        return redirect()->back();
    }

    public function destroy($id): RedirectResponse
    {
        $user = user::findOrFail($id);
        $user->delete();
        notify()->success('User was deleted successfully! 👍', 'Success!');
        return redirect()->back();
    }

    public function search(Request $request)
    {
        $searchTerm = $request->get('query');

        $users = User::where('username', 'LIKE', "%{$searchTerm}%")
            ->orWhere('email', 'LIKE', "%{$searchTerm}%")
            ->get();

        return response()->json($users);
    }

}
