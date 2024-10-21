<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\DesignRequest;
use Illuminate\Support\Facades\DB;
use App\Models\DesignRequestHeader;
use App\Models\PayrollJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class DesignRequestController extends Controller
{
    public function index()
    {
        $designRequestHeaders = DesignRequestHeader::with('designRequests')->get(); // Mengambil semua pengguna
        $designers = User::role('designer')->get();
        return view('menus.designRequests.allRequests', compact('designRequestHeaders', 'designers'));
    }

    public function addPage()
    {
        return view('menus.designRequests.addRequest');
    }

    public function index2()
    {
        $designRequests = DesignRequest::all(); // Mengambil semua pengguna
        return view('menus.designRequests.completedDesigns', compact('designRequests'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $designRequestHeader = DesignRequestHeader::create([
                'customer_id' => Auth::id(),
                'supervisor_id' => null,
                'status' => 'pending',
            ]);

            foreach ($request->name as $index => $name) {
                $sizeW = $request->input('sizeW')[$index];
                $sizeH = $request->input('sizeH')[$index];
                $size = $sizeW . 'x' . $sizeH;

                $referenceImage = $request->file('referenceImage')[$index];
                $imagePath = null;
                if ($referenceImage) {
                    $imagePath = $referenceImage->store('designs/reference_images', 'public');
                    $profilePicPath = 'storage/' . $imagePath; // Simpan path di database
                }

                DesignRequest::create([
                    'design_request_header_id' => $designRequestHeader->id,
                    'reference_image' => $profilePicPath,
                    'name' => $name,
                    'size' => $size,
                    'color' => $request->input('colors')[$index],
                    'total_pieces' => $request->input('total_pieces')[$index],
                    'status' => 'pending',
                    'description' => $request->input('description')[$index],
                ]);
            }

            DB::commit();

            notify()->success('Requests was submitted successfully! ✍️', 'Success!');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e->getMessage());
            notify()->success('User was not successfully! ✍️', 'Failed!');
            return redirect()->back();
        }
    }
    public function approve($id, Request $request)
    { {
            DB::beginTransaction();

            $designRequest = DesignRequest::findOrFail($id);
            $idheader = $designRequest->design_request_header_id;
            $designRequestHeader = DesignRequestHeader::findOrFail($idheader);

            try {
                $designRequestHeader->update([
                    'supervisor_id' => Auth::id(),
                ]);


                $designRequest->update([
                    'design_request_header_id' => $designRequestHeader->id,
                    'assigned_designer_id' => $request->designer,
                    'supervisor_id' => Auth::id(),
                    'price_per_piece' => $request->pay_per_piece,
                    'status' => 'Approved',
                ]);

                $payrollJobs = PayrollJob::create([
                    'design_request_id' => $designRequest->id,
                    'pay_designer' => $request->pay_designer,
                    'pay_machine_operator' => $request->pay_per_operator,
                    'pay_qc' => $request->pay_per_qc
                ]);


                DB::commit();

                notify()->success('Requests was approved successfully! ✍️', 'Success!');
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollback();

                Log::error($e->getMessage());
                notify()->success('User was not successfully! ✍️', 'Failed!');
                return redirect()->back();
            }
        }
    }

    public function update(Request $request, $id)
    {
        // Ambil ID user dari input
        $user = DesignRequest::findOrFail($id);

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
            'telepon' => $request->telepon,
        ]);

        // Notifikasi sukses dan redirect
        notify()->success('User updated successfully! 👌', 'Success!');
        return redirect()->back();
    }

    public function destroy($id): RedirectResponse
    {
        $user = DesignRequest::findOrFail($id);
        $user->delete();
        notify()->success('User was deleted successfully! 👍', 'Success!');
        return redirect()->back();
    }

}
