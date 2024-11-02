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

use function PHPUnit\Framework\isEmpty;

class DesignRequestController extends Controller
{
    public function index()
    {
        $uncompletedDesignRequestHeaders = DesignRequestHeader::with('designRequests')
            ->whereIn('status', ['in_progress', 'pending'])
            ->get();

        $completedDesignRequestHeaders = DesignRequestHeader::with('designRequests')
            ->whereIn('status', ['completed', 'cancelled'])
            ->get();
        $designers = User::role('designer')->get();
        return view('menus.designRequests.allRequests', compact('uncompletedDesignRequestHeaders', 'completedDesignRequestHeaders', 'designers'));
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

    public function getDesign($id)
    {
        $designRequests = DesignRequest::where('design_request_header_id', $id)->get();
        return response()->json($designRequests);
    }

    public function cancelAll(Request $request)
    {
        DB::beginTransaction();

        try {
            $designRequestHeader = DesignRequestHeader::find($request->headerId);
            $designRequestHeader->status = 'cancelled';
            $designRequestHeader->save();

            foreach ($designRequestHeader->designRequests as $designRequest) {
                $designRequest->status = 'cancelled';
                $designRequest->save();
            }

            DB::commit();

            notify()->success('All requests were cancelled successfully! ���', 'Success!');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e->getMessage());
            notify()->success('User was not successfully! ���', 'Failed!');
            return redirect()->back();
        }
    }

    public function cancel(Request $request)
    {
        $designRequest = DesignRequest::find($request->requestId);
        if (!$designRequest) {
            notify()->error('Design request not found!', 'Error!');
            return redirect()->back();
        }
        $designRequest->status = 'cancelled';
        $designRequest->save();
        $designHeader = DesignRequestHeader::find($designRequest->design_request_header_id);
        if ($designHeader) {
            $allCancelled = $designHeader->designRequests()->where('status', '!=', 'cancelled')->count() === 0;

            if ($allCancelled) {
                $designHeader->status = 'cancelled';
                $designHeader->save();
            }
        }

        notify()->success('Requests were cancelled successfully! 👌', 'Success!');
        return redirect()->back();
    }

    public function insert(Request $request)
    {
        DB::beginTransaction();

        try {
            $designRequestHeader = DesignRequestHeader::find($request->headerId);

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
    {
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

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $referenceImage = $request->file('referenceImage');
            $profilePicPath = null;
            $imagePath = null;
            if ($referenceImage) {
                $imagePath = $referenceImage->store('designs/reference_images', 'public');
                $profilePicPath = 'storage/' . $imagePath;
            }

            $designRequest = DesignRequest::find($request->designRequestId);
            if ($designRequest) {
                $designRequest->update([
                    'reference_image' => $profilePicPath,
                    'name' => $request->name,
                    'size' => $request->sizeW . 'x' . $request->sizeH,
                    'color' => $request->colors,
                    'price_per_piece' => $request->pay_per_piece,
                    'total_pieces' => $request->total_pieces,
                    'description' => $request->description,
                ]);

                $payrollJob = PayrollJob::where('design_request_id', $request->designRequestId)->first();
                if ($payrollJob) {
                    $payrollJob->update([
                        'pay_designer' => $request->pay_designer,
                        'pay_machine_operator' => $request->pay_per_operator,
                        'pay_qc' => $request->pay_per_qc
                    ]);
                } else {
                    notify()->error('Payroll Job for choosen request not found!', 'Error!');
                    return redirect()->back();
                }
            } else {
                notify()->error('Design request not found!', 'Error!');
                return redirect()->back();
            }
            DB::commit();
            notify()->success('Requests were cancelled successfully! 👌', 'Success!');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            notify()->error('Update request failed! ', 'Error!');
            return redirect()->back();
        }
    }

    public function destroy($id): RedirectResponse
    {
        $user = DesignRequest::findOrFail($id);
        $user->delete();
        notify()->success('User was deleted successfully! 👍', 'Success!');
        return redirect()->back();
    }

}
