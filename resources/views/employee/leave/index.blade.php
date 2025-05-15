@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">My Leave Requests</h3>
    <div class="mb-3">
        <a href="{{ route('employee.leave.create') }}" class="btn btn-primary">
            + Create Leave Request
        </a>
    </div>

    <div class="mb-4">
    <div class="alert alert-info">
        <p>You have <strong>{{ $leaveBalance }}</strong> leave days remaining out of 20 per year.</p>
    </div>
</div>


    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($leaveRequests->isEmpty())
        <div class="alert alert-info">You have not submitted any leave requests yet.</div>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Duration (Days)</th>
                    <th>Status</th>
                    <th>Reason</th>
                    <th>Admin Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leaveRequests as $request)
                    <tr>
                        <td>{{ $request->leave_type }}</td>
                        <td>{{ $request->start_date }}</td>
                        <td>{{ $request->end_date }}</td>
                        <td>{{ \Carbon\Carbon::parse($request->start_date)->diffInDays($request->end_date) + 1 }}</td>
                        <td>
                            @if($request->status == 'Pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($request->status == 'Approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($request->status == 'Rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                        <td>{{ $request->reason ?? '-' }}</td>
                        <td>{{ $request->admin_remarks ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
