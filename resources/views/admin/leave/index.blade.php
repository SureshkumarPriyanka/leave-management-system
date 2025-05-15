@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">All Leave Requests</h3>
    <div class="mb-3">
        <a href="{{ route('admin.leave.report') }}" class="btn btn-primary">
            View Reports
        </a>
    </div>

    


    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Leave Type</th>
                <th>From</th>
                <th>To</th>
                <th>Status</th>
                <th>Reason</th>
                <th>Admin Remarks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaveRequests as $request)
                <tr>
                    <td>{{ $request->user->name }}</td>
                    <td>{{ $request->leave_type }}</td>
                    <td>{{ $request->start_date }}</td>
                    <td>{{ $request->end_date }}</td>
                    <td>{{ $request->status }}</td>
                    <td>{{ $request->reason }}</td>
                    <td>{{ $request->admin_remarks }}</td>
                    <td>
                        @if($request->status == 'Pending')
                            <form action="{{ route('admin.leave.approve', $request->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>

                            <!-- Reject Button (With Modal or Inline Input) -->
                            <button type="button" class="btn btn-danger btn-sm" onclick="document.getElementById('reject-form-{{ $request->id }}').style.display='block'">Reject</button>

                            <form id="reject-form-{{ $request->id }}" action="{{ route('admin.leave.reject', $request->id) }}" method="POST" style="display:none; margin-top: 10px;">
                                @csrf
                                <input type="text" name="reason" class="form-control mb-2" placeholder="Enter rejection reason" required>
                                <button type="submit" class="btn btn-warning btn-sm">Submit Rejection</button>
                            </form>
                        @else
                            <em>N/A</em>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
