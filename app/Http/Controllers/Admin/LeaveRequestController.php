<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Mail;
use App\Models\User;


class LeaveRequestController extends Controller
{
    public function index()
    {
        
        // $user = Auth::user();
        // dd($user->getRoleNames());
        $leaveRequests = LeaveRequest::with('user')->latest()->get();
        return view('admin.leave.index', compact('leaveRequests'));
    }

    public function approve($id)
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->status = 'Approved';
        $leave->admin_remarks = 'Approved by admin';
        $leave->save();

        // Send email to employee
        // Mail::to($leave->user->email)->send(new \App\Mail\LeaveApproved($leave));

        return redirect()->back()->with('success', 'Leave approved successfully.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $leave = LeaveRequest::findOrFail($id);
        $leave->status = 'Rejected';
        $leave->admin_remarks = $request->reason;
        $leave->save();

        // Send email to employee
        // Mail::to($leave->user->email)->send(new \App\Mail\LeaveRejected($leave));

        return redirect()->back()->with('error', 'Leave rejected.');
    }

    // public function report()
    // {
    //     $reports = LeaveRequest::with('user')->get();
    //     return view('admin.leave.report', compact('reports'));
    // }

    public function report(Request $request)
{
    $filters = $request->only(['month', 'status', 'user_id']);
    $leaveRequests = LeaveRequest::with('user')->filter($filters)->get();
    $users = User::role('Employee')->get();

    return view('admin.leave.report', compact('leaveRequests', 'users'));
}

    // public function export()
    // {
    //     // Use a package like Laravel Excel to implement this
    //     return response()->json(['message' => 'Export to Excel or PDF feature coming soon.']);
    // }

    public function export(Request $request)
{
    $fileName = 'leave_report.csv';

    $leaveRequests = LeaveRequest::with('user')
        ->when($request->month, fn($q) => $q->whereMonth('start_date', $request->month))
        ->when($request->status, fn($q) => $q->where('status', $request->status))
        ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
        ->get();

    $headers = [
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];

    $columns = [
        'Employee',
        'Leave Type',
        'Start Date',
        'End Date',
        'Status',
        'Reason',
        'Admin Remarks'
    ];

    $callback = function () use ($leaveRequests, $columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($leaveRequests as $leave) {
            fputcsv($file, [
                $leave->user->name,
                $leave->leave_type,
                $leave->start_date,
                $leave->end_date,
                $leave->status,
                $leave->reason,
                $leave->admin_remarks,
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
}
