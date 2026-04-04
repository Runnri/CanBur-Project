<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination; // kalau mau menampilkan destinasi di dashboard

class DashboardController extends Controller
{
   public function index()
{
    $destinations = \App\Models\Destination::where('user_id', auth()->id())->get();

    $total = $destinations->count();
    $completed = $destinations->where('is_completed', 1)->count();
    $pending = $destinations->where('is_completed', 0)->count();

    return view('dashboard', compact('destinations', 'total', 'completed', 'pending'));
}
}