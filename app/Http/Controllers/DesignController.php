<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Design;
use App\Models\PayrollJob;
use Illuminate\Http\Request;
use App\Models\DesignRequest;
use App\Models\DailyPayrollDetail;
use App\Models\DailyPayrollHeader;
use Illuminate\Support\Facades\DB;
use App\Models\DesignRequestHeader;
use App\Models\WeeklyPayrollDetail;
use App\Models\WeeklyPayrollHeader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DesignController extends Controller
{
    public function index()
    {
        $designs = Design::with('designRequest', 'designer')->get(); // Mengambil semua pengguna
        $designDesigner = Design::where('designer_id', Auth::id())->get(); // Mengambil semua pengguna
        $designReqsDesigner = DesignRequest::where('assigned_designer_id', Auth::id())->doesntHave('design')->get(); //
        $designReqsAll = DesignRequest::with('assignedDesigner')->doesntHave('design')->get(); //
        return view('menus.designs.allDesigns', compact('designs', 'designDesigner', 'designReqsAll', 'designReqsDesigner'));
    }
    public function download($design_files)
    {
        // Mengakses file di dalam storage/app/public
        $filePath = storage_path('app/public/designs/files/' . $design_files);

        // Cek apakah file ada di path tersebut
        if (file_exists($filePath)) {
            return response()->download($filePath);
        }

        // Jika file tidak ditemukan, return error
        return response()->json(['error' => 'File not found.'], 404);
    }

    public function downloadReference($reference_image, $name)
    {
        // Mengakses file di dalam storage/app/public
        $filePath = storage_path('app/public/designs/reference_images/' . $reference_image);
        $designName = $name;

        // Cek apakah file ada di path tersebut
        if (file_exists($filePath)) {
            return response()->download($filePath, $designName . '.png');
        }

        // Jika file tidak ditemukan, return error
        echo $filePath;
        // return response()->json(['error' => 'File not found.'], 404);
    }
    public function upload(Request $request)
    {
        $request->validate([
            'design_file' => 'required|file|mimes:zip,rar|max:10240', // Maksimal 10MB
            'name' => ['required', 'string', 'max:255'],
            'request_id' => ['required', 'string', 'max:255'],
        ]);

        $designRequest = DesignRequest::findOrFail($request->request_id);
        if ($designRequest) {
            $designRequest->update(
                [
                    'status' => 'in_design',
                ]
            );


            $profilePicPath = $request->design_file;

            if ($request->hasFile('design_file')) {
                // Hapus gambar lama jika ada
                if ($profilePicPath && file_exists(public_path($profilePicPath))) {
                    unlink(public_path($profilePicPath));
                }
                // Simpan gambar baru
                $image = $request->file('design_file');
                $path = $image->store('designs/files', 'public');
                $profilePicPath = 'storage/' . $path; // Simpan path di database
            }

            $design = new Design();
            $design->request_id = $request->request_id;
            $design->designer_id = Auth::id();
            $design->design_name = $request->name;
            $design->design_files = $profilePicPath;
            $design->status = 'in_design';
            $design->save();
            notify()->success('Design was uploaded successfully! 👌', 'Success!');
            return redirect()->back();
        } else {
            notify()->error('Request not found! ��', 'Failed!');
            return redirect()->back();
        }

    }

    public function approve(Request $request)
    {
        DB::beginTransaction();

        $request->validate([
            'designId' => ['required', 'string', 'max:255'],
            'designRequestId' => ['required', 'string', 'max:255'],
        ]);

        $today = Carbon::today();
        $requestId = $request->designRequestId;
        $designRequest = DesignRequest::findOrFail($requestId);
        $design = Design::findOrFail($request->designId);

        try {

            $designRequest->update([
                'status' => 'in_production'
            ]);

            $design->update([
                'status' => 'approved'
            ]);

            $payrollJob = PayrollJob::where('design_request_id', $request->designRequestId)->first();
            $totalPay = $payrollJob->pay_designer;

            $processPayroll = function ($employeeId, $quantity, $totalPay, $today) use ($requestId) {
                $dailyPayrollHeader = DailyPayrollHeader::firstOrCreate(
                    [
                        'work_date' => $today->toDateString(),
                        'employee_id' => $employeeId,
                    ],
                    [
                        'total_pieces' => 0,
                        'daily_total_pay' => 0,
                    ]
                );

                $dailyPayrollDetail = DailyPayrollDetail::create([
                    'daily_payroll_header_id' => $dailyPayrollHeader->id,
                    'design_request_id' => $requestId,
                    'job_type' => 'designer',
                    'pieces_worked' => $quantity,
                    'pay_per_piece' => $totalPay,
                    'subtotal_pay' => $totalPay,
                ]);

                $dailyPayrollHeader->increment('total_pieces', $quantity);
                $dailyPayrollHeader->increment('daily_total_pay', $totalPay);

                $weekStart = $today->copy()->startOfWeek(0);
                $weekEnd = $weekStart->copy()->endOfWeek(6);
                $weeklyPayrollHeader = WeeklyPayrollHeader::firstOrCreate(
                    [
                        'week_start_date' => $weekStart->toDateString(),
                        'week_end_date' => $weekEnd->toDateString(),
                        'employee_id' => $employeeId,
                    ],
                    [
                        'weekly_total_pay' => 0,
                        'paid' => false,
                    ]
                );

                $weeklyPayrollDetail = WeeklyPayrollDetail::updateOrCreate(
                    [
                        'weekly_payroll_header_id' => $weeklyPayrollHeader->id,
                        'daily_payroll_header_id' => $dailyPayrollHeader->id,
                    ],
                    [
                        'subtotal_pay' => $dailyPayrollHeader->daily_total_pay,
                    ]
                );

                $weeklyPayrollHeader->increment('weekly_total_pay', $totalPay);
            };

            $processPayroll($design->designer_id, 1, $totalPay, $today);

            DB::commit();

            notify()->success('Design was approved successfully! ✍️', 'Success!');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e->getMessage());
            notify()->success('Design was not approved successfully! ✍️', 'Failed!');
            return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'profile_pic' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'telepon' => ['required', 'numeric', 'unique:users,contact_info'],
        ]);

        $profilePicPath = null;

        // Jika ada file yang diupload
        if ($request->hasFile('profile_pic')) {
            $image = $request->file('profile_pic');
            $path = $image->store('profiles', 'public');
            $profilePicPath = 'storage/' . $path; // Simpan path di database
        }

        // Simpan data ke database, misalnya:
        $user = Design::create([
            'profile_picture' => $profilePicPath,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
        ]);

        notify()->success('User was added successfully! ✍️', 'Success!');
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        // Ambil ID user dari input
        $user = Design::findOrFail($id);

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
        $user = Design::findOrFail($id);
        $user->delete();
        notify()->success('User was deleted successfully! 👍', 'Success!');
        return redirect()->back();
    }

}
