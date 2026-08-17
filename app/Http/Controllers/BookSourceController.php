<?php

namespace App\Http\Controllers;

use App\Models\BookSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class BookSourceController extends Controller
{
    public function index()
    {
        // If migration not yet run, return empty list to avoid DB errors
        $sources = collect();
        if (Schema::hasTable('book_sources')) {
            $sources = BookSource::orderBy('name')->get();
        }
        return view('books.sources.index', compact('sources'));
    }

    public function store(Request $request)
    {
        if (!Schema::hasTable('book_sources')) {
            return redirect()->back()->with('error', 'Tabel book_sources belum dibuat. Jalankan php artisan migrate.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:book_sources,name',
            'code' => 'nullable|string|max:50',
        ]);

        BookSource::create([
            'name' => $request->name,
            'code' => $request->code ?? null,
        ]);

        return redirect()->back()->with('success', 'Asal buku berhasil ditambahkan');
    }

    public function update(Request $request, BookSource $book_source)
    {
        if (!Schema::hasTable('book_sources')) {
            return redirect()->back()->with('error', 'Tabel book_sources belum dibuat. Jalankan php artisan migrate.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:book_sources,name,' . $book_source->id,
            'code' => 'nullable|string|max:50',
        ]);

        $book_source->update([
            'name' => $request->name,
            'code' => $request->code ?? null,
        ]);

        return redirect()->back()->with('success', 'Asal buku berhasil diperbarui');
    }

    public function destroy(BookSource $book_source)
    {
        if (!Schema::hasTable('book_sources')) {
            return redirect()->back()->with('error', 'Tabel book_sources belum dibuat. Jalankan php artisan migrate.');
        }

        $book_source->delete();
        return redirect()->back()->with('success', 'Asal buku berhasil dihapus');
    }
}
