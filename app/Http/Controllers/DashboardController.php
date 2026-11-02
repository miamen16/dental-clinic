<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Service;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = now();

        return view('dashboard', [
            'totalPatients' => Patient::count(),
            'totalAppointments' => Appointment::count(),
            'totalAppointmentsToday' => Appointment::whereDate('start_at', $today->toDateString())->count(),
            'totalAppointmentsThisMonth' => Appointment::whereYear('start_at', $today->year)
                ->whereMonth('start_at', $today->month)
                ->count(),
            'totalAppointmentsThisYear' => Appointment::whereYear('start_at', $today->year)->count(),
            'totalAppointmentsCompleted' => Appointment::where('status', 'completed')->count(),
            'totalAppointmentsCancelled' => Appointment::where('status', 'cancelled')->count(),
            'totalInvoices' => Invoice::count(),
            'totalInvoicesPaid' => Invoice::where('status', 'paid')->count(),
            'totalInvoicesPending' => Invoice::where('status', 'pending')->count(),
            'totalInvoicesOverdue' => Invoice::where('status', 'overdue')->count(),
            'totalServices' => Service::count(),
            'totalDoctors' => User::doctors()->count(),
            'newThisMonth' => Patient::where('created_at', '>=', $today->copy()->startOfMonth())->count(),
            'recentPatients' => Patient::latest()->take(5)->get(),
            'recentDoctors' => User::doctors()->latest()->take(5)->get(),
        ]);
    }
}
