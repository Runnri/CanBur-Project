<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DestinationController extends Controller
{
    
    // UNTUK HALAMAN DASHBOARD (Beranda)
    public function dashboard()
    {
        // Ambil semua data (tanpa paginasi) untuk dihitung di statistik (Stats row)
        $destinations = Destination::where('user_id', auth()->id())->latest()->get();
        // Pastikan variabel totalPlans dikirim jika digunakan di Blade
        $totalPlans = 0; 
        
        return view('dashboard', compact('destinations', 'totalPlans'));
    }

    public function index(Request $request)
{
    $query = Destination::where('user_id', auth()->id());

    // Fitur Filter berdasarkan Status (Tercapai / Belum)
    if ($request->filled('filter') && $request->filter !== 'all') {
        $isCompleted = $request->filter === 'tercapai' ? 1 : 0;
        $query->where('is_completed', $isCompleted);
    }

    // Eksekusi query dengan paginasi
    $destinations = $query->latest()->paginate(6)->withQueryString();

    return view('destinations.index', compact('destinations'));
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
            'is_completed' => $request->status,
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
        'status' => 'required',
        'foto' => 'nullable|image|max:2048',
    ]);

    $imagePath = $destination->image;

    if ($request->hasFile('foto')) {
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
        'is_completed' => $request->status,
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