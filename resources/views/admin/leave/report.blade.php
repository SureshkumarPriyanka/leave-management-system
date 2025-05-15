@extends('layouts.app')

@section('content')
    <form method="GET" action="{{ route('admin.leave.report') }}" class="mb-4">
    <select name="month">
        <option value="">All Months</option>
        @for($m=1; $m<=12; $m++)
            <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
        @endfor
    </select>
    
    <select name="status">
        <option value="">All Statuses</option>
        <option value="Pending">Pending</option>
        <option value="Approved">Approved</option>
        <option value="Rejected">Rejected</option>
    </select>

    <select name="user_id">
        <option value="">All Users</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>

    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
    <a href="{{ route('admin.leave.report') }}" class="btn btn-sm btn-secondary">
        Reset
    </a>

    <a href="{{ route('admin.leave.export', request()->query()) }}" class="btn btn-sm btn-success">
        Export to Excel
    </a>

</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Employee</th>
            <th>Type</th>
            <th>Duration</th>
            <th>Status</th>
            <th>Reason</th>
            <th>Admin Remarks</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($leaveRequests as $leave)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $leave->user->name }}</td>
                <td>{{ $leave->leave_type }}</td>
                <td>{{ $leave->start_date }} to {{ $leave->end_date }}</td>
                <td>{{ $leave->status }}</td>
                <td>{{ $leave->reason }}</td>
                <td>{{ $leave->admin_remarks }}</td>
            </tr>
        @empty
            <tr><td colspan="8">No leave requests found.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection