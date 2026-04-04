<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DestinationController extends Controller
{
    // READ (dashboard)
    public function index()
    {
        $destination = Destination::where('destinations_id', $id)
                              ->where('user_id', auth()->id())
                              ->firstOrFail();
        return view('dashboard', compact('destinations'));
    }

    // CREATE (form tambah)
    public function create()
    {
        return view('destinations.create');
    }

    // STORE (simpan ke DB)
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal_berangkat' => 'required|date',
            'lama_hari' => 'required|integer|min:1',
            'budget' => 'required|numeric|min:0',
            'status' => 'required', // 'done' / 'pending'
            'foto' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('destinations', 'public');
        }

        Destination::create([
            'user_id' => auth()->id(),
            'title' => $request->judul,
            'departure_date' => $request->tanggal_berangkat,
            'duration' => $request->lama_hari,
            'budget' => $request->budget,
            'is_completed' => $request->status === 'done' ? 1 : 0,
            'image' => $path,
        ]);

        return redirect()->route('dashboard')->with('success', 'Destinasi berhasil ditambahkan!');
    }

    // SHOW (detail destinasi)
    public function show($id)
    {
        $destination = Destination::where('destinations_id', $id)
                              ->where('user_id', auth()->id())
                              ->firstOrFail();
        return view('destinations.show', compact('destination'));
    }

    // EDIT (form edit)
    public function edit($id)
    {
        $destination = Destination::where('destinations_id', $id)
                              ->where('user_id', auth()->id())
                              ->firstOrFail();
        return view('destinations.edit', compact('destination'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $destination = Destination::where('destinations_id', $id)
                              ->where('user_id', auth()->id())
                              ->firstOrFail();

        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal_berangkat' => 'required|date',
            'lama_hari' => 'required|integer|min:1',
            'budget' => 'required|numeric|min:0',
            'status' => 'required', // 'done' / 'pending'
            'foto' => 'nullable|image|max:2048',
        ]);

        // Jika ada foto baru, simpan dan ganti path lama
        $imagePath = $destination->image;
        if ($request->hasFile('foto')) {
            // Hapus file lama kalau ada
            if ($destination->image && Storage::disk('public')->exists($destination->image)) {
                Storage::disk('public')->delete($destination->image);
            }
            $imagePath = $request->file('foto')->store('destinations', 'public');
        }

        $destination->update([
            'title' => $request->judul,
            'departure_date' => $request->tanggal_berangkat,
            'duration' => $request->lama_hari,
            'budget' => $request->budget,
            'is_completed' => $request->status === 'done' ? 1 : 0,
            'image' => $imagePath,
        ]);

        return redirect()->route('dashboard')->with('success', 'Destinasi berhasil diupdate!');
    }

    // DELETE
    public function destroy($id)
    {
        $destination = Destination::where('destinations_id', $id)
                              ->where('user_id', auth()->id())
                              ->firstOrFail();

        // Hapus file image dari storage jika ada
        if ($destination->image && Storage::disk('public')->exists($destination->image)) {
            Storage::disk('public')->delete($destination->image);
        }

        $destination->delete();

        return redirect()->route('dashboard')->with('success', 'Destinasi berhasil dihapus!');
    }
}