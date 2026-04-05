<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Destination;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Simpan rencana baru ke dalam destinasi tertentu.
     * Dipanggil dari modal di destinations/show.blade.php
     */
    public function store(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'hari'           => 'required|integer|min:1',
            'jam'            => 'required',
            'kegiatan'       => 'required|string|max:500',
            'lokasi'         => 'nullable|string|max:255',
        ]);

        // Pastikan destinasi milik user yang sedang login
        $destination = Destination::where('id', $request->destination_id)
                                  ->where('user_id', auth()->id())
                                  ->firstOrFail();

        Plan::create([
            'destination_id' => $destination->id,
            'hari'           => $request->hari,
            'jam'            => $request->jam,
            'kegiatan'       => $request->kegiatan,
            'lokasi'         => $request->lokasi,
        ]);

        // Kalau request dari fetch/axios (modal Alpine.js), balas JSON
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Rencana berhasil ditambahkan!'], 201);
        }

        return back()->with('success', 'Rencana berhasil ditambahkan!');
    }

    /**
     * Update rencana yang sudah ada.
     */
    public function update(Request $request, Plan $plan)
    {
        // Pastikan plan milik user yang login (lewat destination)
        abort_unless($plan->destination->user_id === auth()->id(), 403);

        $request->validate([
            'hari'     => 'required|integer|min:1',
            'jam'      => 'required',
            'kegiatan' => 'required|string|max:500',
            'lokasi'   => 'nullable|string|max:255',
        ]);

        $plan->update([
            'hari'     => $request->hari,
            'jam'      => $request->jam,
            'kegiatan' => $request->kegiatan,
            'lokasi'   => $request->lokasi,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Rencana berhasil diperbarui!']);
        }

        return back()->with('success', 'Rencana berhasil diperbarui!');
    }

    /**
     * Hapus rencana.
     */
    public function destroy(Plan $plan)
    {
        // Pastikan plan milik user yang login
        abort_unless($plan->destination->user_id === auth()->id(), 403);

        $plan->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Rencana berhasil dihapus!']);
        }

        return back()->with('success', 'Rencana berhasil dihapus!');
    }
}
