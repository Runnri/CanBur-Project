<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlanController extends Controller
{
    use App\Models\Plan;

public function store(Request $request)
{
    $request->validate([
        'destination_id' => 'required',
        'day_number' => 'required|integer',
        'activity' => 'required|string'
    ]);

    Plan::create([
        'destination_id' => $request->destination_id,
        'day_number' => $request->day_number,
        'activity' => $request->activity,
    ]);

    return back()->with('success', 'Rencana ditambahkan!');
}
}
