<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($request->search) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('title', 'like', "%$q%")
                   ->orWhere('author', 'like', "%$q%")
                   ->orWhere('isbn', 'like', "%$q%")
                   ->orWhere('publisher', 'like', "%$q%");
            });
        }

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->available === '1') {
            $query->where('available', '>', 0);
        } elseif ($request->available === '0') {
            $query->where('available', 0);
        }

        $books = $query->latest()->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('books.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'isbn'        => 'required|unique:books',
            'title'       => 'required',
            'author'      => 'required',
            'publisher'   => 'required',
            'year'        => 'required|integer|min:1800|max:' . date('Y'),
            'category_id' => 'required|exists:categories,id',
            'stock'       => 'required|integer|min:1',
            'location'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'cover'       => 'nullable|image|max:2048',
        ]);

        $data['available'] = $data['stock'];

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        Book::create($data);
        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get();
        return view('books.form', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'isbn'        => 'required|unique:books,isbn,' . $book->id,
            'title'       => 'required',
            'author'      => 'required',
            'publisher'   => 'required',
            'year'        => 'required|integer|min:1800|max:' . date('Y'),
            'category_id' => 'required|exists:categories,id',
            'stock'       => 'required|integer|min:1',
            'location'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'cover'       => 'nullable|image|max:2048',
        ]);

        // Stok tidak boleh lebih kecil dari jumlah eksemplar yang sedang dipinjam
        $borrowed = $book->stock - $book->available;
        if ($data['stock'] < $borrowed) {
            return back()->withInput()->withErrors([
                'stock' => "Stok tidak boleh kurang dari jumlah buku yang sedang dipinjam ($borrowed).",
            ]);
        }
        $data['available'] = $data['stock'] - $borrowed;

        if ($request->hasFile('cover')) {
            if ($book->cover) Storage::disk('public')->delete($book->cover);
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $book->update($data);
        return redirect()->route('books.show', $book)->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy(Book $book)
    {
        if ($book->loans()->whereIn('status', ['active', 'overdue'])->exists()) {
            return back()->with('error', 'Buku tidak bisa dihapus karena masih dipinjam!');
        }

        if ($book->cover) Storage::disk('public')->delete($book->cover);
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus!');
    }
}
