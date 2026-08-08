<?php

namespace App\Http\Controllers;

use App\Models\Rack;
use Illuminate\Http\Request;

class RackController extends Controller
{
    public function index()
    {
        $racks = Rack::withCount('books')->latest()->get();
        return view('racks.index', compact('racks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'     => 'required|unique:racks,code',
            'name'     => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        Rack::create($request->all());

        return redirect()->route('racks.index')->with('success', 'Data Rak berhasil ditambahkan.');
    }

    public function update(Request $request, Rack $rack)
    {
        $request->validate([
            'code'     => 'required|unique:racks,code,' . $rack->id,
            'name'     => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $rack->update($request->all());

        return redirect()->route('racks.index')->with('success', 'Data Rak berhasil diperbarui.');
    }

    public function destroy(Rack $rack)
    {
        // Cegah penghapusan jika rak masih dipakai buku
        if ($rack->books()->exists()) {
            return redirect()->back()->with('error', 'Rak tidak dapat dihapus karena masih menampung data buku!');
        }

        $rack->delete();
        return redirect()->route('racks.index')->with('success', 'Data Rak berhasil dihapus.');
    }
}
