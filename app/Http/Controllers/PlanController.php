<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Destination;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Form tambah plan — halaman tersendiri
     */
    public function create(Destination $destination)
    {
        abort_unless($destination->user_id === auth()->id(), 403);
        return view('plans.create', compact('destination'));
    }

    /**
     * Simpan plan baru lalu redirect ke show destinasi
     */
    public function store(Request $request, Destination $destination)
    {
        abort_unless($destination->user_id === auth()->id(), 403);

        $request->validate([
            'hari'     => "required|integer|min:1|max:{$destination->duration}",
            'jam'      => 'required',
            'kegiatan' => 'required|string|max:500',
            'lokasi'   => 'nullable|string|max:255',
        ]);

        Plan::create([
            'destinations_id' => $destination->destinations_id,
            'hari'           => $request->hari,
            'jam'            => $request->jam,
            'kegiatan'       => $request->kegiatan,
            'lokasi'         => $request->lokasi,
        ]);

        return redirect()
            ->route('destinations.show', $destination)
            ->with('success', 'Kegiatan berhasil ditambahkan!');
    }

    /**
     * Form edit plan
     */
    public function edit(Destination $destination, Plan $plan)
    {
        abort_unless($destination->user_id === auth()->id(), 403);
        abort_unless($plan->destination_id === $destination->id, 404);
        return view('plans.edit', compact('destination', 'plan'));
    }

    /**
     * Simpan perubahan plan
     */
    public function update(Request $request, Destination $destination, Plan $plan)
    {
        abort_unless($destination->user_id === auth()->id(), 403);
        abort_unless($plan->destination_id === $destination->id, 404);

        $request->validate([
            'hari'     => "required|integer|min:1|max:{$destination->duration}",
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

        return redirect()
            ->route('destinations.show', $destination)
            ->with('success', 'Kegiatan berhasil diperbarui!');
    }

    /**
     * Hapus plan
     */
    public function destroy(Destination $destination, Plan $plan)
    {
        abort_unless($destination->user_id === auth()->id(), 403);
        abort_unless($plan->destination_id === $destination->id, 404);

        $plan->delete();

        return redirect()
            ->route('destinations.show', $destination)
            ->with('success', 'Kegiatan berhasil dihapus!');
    }
}
