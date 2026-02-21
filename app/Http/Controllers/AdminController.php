<?php

namespace App\Http\Controllers;

use App\Models\Skate;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_bookings' => Booking::count(),
            'paid_bookings' => Booking::where('is_paid', true)->count(),
            'total_skates' => Skate::sum('quantity'),
            'today_bookings' => Booking::whereDate('created_at', today())->count()
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function skatesIndex()
    {
        $skates = Skate::paginate(10);
        return view('admin.skates.index', compact('skates'));
    }

    public function skatesCreate()
    {
        return view('admin.skates.form', ['skate' => null]);
    }

    public function skatesStore(Request $request)
    {
        $validated = $request->validate([
            'model' => 'required|string|max:255',
            'size' => 'required|integer|min:20|max:50',
            'quantity' => 'required|integer|min:0'
        ]);

        Skate::create($validated);

        return redirect()->route('admin.skates')->with('success', 'Коньки добавлены');
    }

    public function skatesEdit(Skate $skate)
    {
        return view('admin.skates.form', compact('skate'));
    }

    public function skatesUpdate(Request $request, Skate $skate)
    {
        $validated = $request->validate([
            'model' => 'required|string|max:255',
            'size' => 'required|integer|min:20|max:50',
            'quantity' => 'required|integer|min:0'
        ]);

        $skate->update($validated);

        return redirect()->route('admin.skates')->with('success', 'Коньки обновлены');
    }

    public function skatesDestroy(Skate $skate)
    {
        $skate->delete();
        return redirect()->route('admin.skates')->with('success', 'Коньки удалены');
    }

    public function bookingsIndex(Request $request)
    {
        $query = Booking::with('skate')->latest();

        if ($request->filter == 'paid') {
            $query->where('is_paid', true);
        } elseif ($request->filter == 'unpaid') {
            $query->where('is_paid', false);
        }

        $bookings = $query->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function bookingsShow(Booking $booking)
    {
        return view('admin.bookings.show', compact('booking'));
    }

    public function ticketsIndex()
    {
        $paidBookings = Booking::where('is_paid', true)
            ->with('skate')
            ->latest()
            ->paginate(15);

        return view('admin.tickets.index', compact('paidBookings'));
    }

    public function adminsIndex()
    {
        $admins = User::where('is_admin', true)->get();
        return view('admin.admins.index', compact('admins'));
    }
}
