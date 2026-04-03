<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination; // kalau mau menampilkan destinasi di dashboard

class DashboardController extends Controller
{
    public function index()
    {
        // ambil data destinasi jika perlu
        $destinations = Destination::all();

        return view('dashboard', compact('destinations'));
    }
}