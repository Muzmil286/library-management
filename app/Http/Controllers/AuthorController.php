<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Author::query()->when(request('name'), function ($query, $name) {

            $query->whereRaw('CONCAT(first_name , " " , last_name) LIKE ?', ['%' . $name . '%']);
        })->paginate(10)
            ->withQueryString()
            ->through(fn ($author) => [
                'id' => $author->id,
                'name' => $author->full_name,
                'totalBooks' => count($author->books)
            ]);

        return Inertia::render('Author/Index', [
            'authors' => $authors
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Author/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstName' => ['required', 'max:30', 'string'],
            'lastName' => ['required', 'max:30', 'string']
        ]);
        $newAuthor = new Author();
        $newAuthor->first_name = ucfirst($request->firstName);
        $newAuthor->last_name = ucfirst($request->lastName);
        $newAuthor->save();
        return redirect()->route('authors.create')->with('success', $newAuthor->full_name . ' has been added');
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author)
    {
        $author_id = $author->id;

        $books = Book::query()->when(request('title'), function ($query, $title) {
            $query->where('title', 'like', "%$title%");
        })->whereHas('authors', function ($query) use ($author_id) {
            $query->where('authors.id', $author_id);
        })->paginate(10)->withQueryString()->through(fn ($book) => [
            'id' => $book->id,
            'title' => $book->title,
            'categoryId' => $book->category->id ?? null,
            'categoryName' => $book->category->name ?? null,
            'versions' => count($book->versions) > 0 ? $book->versions->map(fn ($version) => [
                'id' => $version->id,
                'language' => $version->language->name
            ]) : null
        ]);

        return Inertia::render('Author/Show', [
            'author' => [
                'id' => $author->id,
                'name' => $author->full_name,
                'totalBooks' => count($author->books),
                'addedAgo' => $author->created_at->format('d/m/y')
            ],
            'books' => $books
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {
        return Inertia::render('Author/Edit', [
            'author' => [
                'id' => $author->id,
                'firstName' => $author->first_name,
                'lastName' => $author->last_name
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author)
    {
        $message = $author->full_name;
        $request->validate([
            'firstName' => ['required', 'max:30', 'string'],
            'lastName' => ['required', 'max:30', 'string'],
        ]);
        $author->first_name = ucfirst($request->firstName);
        $author->last_name = ucfirst($request->lastName);
        $author->save();
        return redirect()->route('authors.edit', $author->id)->with('success', $message . ' has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        $message = $author->full_name;

        if ($author->delete()) {
            $author->books()->detach();
            return redirect()->route('authors.index')->with('success', $message . ' has been remove');
        }
    }
}
