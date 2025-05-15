<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    /**
     * Show the role-based dashboard.
     */
    /**
     * Show the role-based dashboard.
     */
    public function index()
    {
        // dd(auth()->user()->hasRole('Admin'));
        if (auth()->user()->hasRole('Admin')) {
            return redirect()->route('admin.leave.index');
        } elseif (auth()->user()->hasRole('Employee')) {
            return redirect()->route('employee.leave.index');
        }

        return view('dashboard');
    }
}