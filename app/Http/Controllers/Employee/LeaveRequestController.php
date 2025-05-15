<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LeaveRequestController extends Controller
{
     public function index()
    {
        $user = Auth::user();
        $leaveRequests = LeaveRequest::where('user_id', $user->id)->latest()->get();
        return view('employee.leave.index', [
            'leaveRequests' => $leaveRequests,
            'leaveBalance' => $user->leaveBalance()
        ]);
    }

    public function create()
    {
        return view('employee.leave.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:255',
        ]);

        // Check for overlapping leave
        $overlap = LeaveRequest::where('user_id', Auth::id())
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
            })->exists();

        if ($overlap) {
            return back()->withErrors(['overlap' => 'You already have a leave during this period.']);
        }

        $leave = LeaveRequest::create([
            'user_id' => Auth::id(),
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'Pending',
        ]);

        // Notify admin (send email)
        // Mail::to('admin@example.com')->send(new \App\Mail\LeaveRequested($leave));

        return redirect()->route('employee.leave.index')->with('success', 'Leave request submitted.');
    }
}
