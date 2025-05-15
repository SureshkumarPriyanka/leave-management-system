@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <h3 class="mb-4">Dashboard</h3>

            @role('Admin')
                <div class="alert alert-primary">
                    <h5>Welcome, Admin!</h5>
                    <p>You can manage leave requests, view reports, and handle users.</p>
                    <a href="{{ route('admin.leave.index') }}" class="btn btn-outline-primary">View Leave Requests</a>
                </div>
            @endrole

            @role('Employee')
                <div class="alert alert-success">
                    <h5>Welcome, {{ Auth::user()->name }}!</h5>
                    <p>You can request leave and track your leave history.</p>
                    <a href="{{ route('employee.leave.index') }}" class="btn btn-outline-success">Request Leave</a>
                </div>
            @endrole

        </div>
    </div>
</div>
@endsection
