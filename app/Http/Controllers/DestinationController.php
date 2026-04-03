<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    // ✅ READ (dashboard)
    public function index()
    {
        $destinations = Destination::all();

        return view('dashboard', compact('destinations'));
    }

    // ✅ CREATE (form tambah)
    public function create()
    {
        return view('destinations.create');
    }

    // ✅ STORE (simpan ke DB)
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'lokasi' => 'required',
            'tanggal' => 'required|date',
            'durasi' => 'required|integer',
            'budget' => 'required|numeric',
        ]);

        Destination::create($request->all());

        return redirect()->route('dashboard')
            ->with('success', 'Destinasi berhasil ditambahkan!');
    }

    // ✅ EDIT (form edit)
    public function edit($id)
    {
        $destination = Destination::findOrFail($id);

        return view('destinations.edit', compact('destination'));
    }

    // ✅ UPDATE
    public function update(Request $request, $id)
    {
        $destination = Destination::findOrFail($id);

        $destination->update($request->all());

        return redirect()->route('dashboard')
            ->with('success', 'Destinasi berhasil diupdate!');
    }

    // ✅ DELETE
    public function destroy($id)
    {
        $destination = Destination::findOrFail($id);
        $destination->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Destinasi berhasil dihapus!');
    }
}