<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $publishers = Publisher::query()->when(request('name'), function ($query, $name) {
            $query->where('name', 'like', "%$name%");
        })->paginate(10)
            ->withQueryString()
            ->through(fn ($publisher) => [
                'id' => $publisher->id,
                'name' => $publisher->name,
                'totalBooks' => count($publisher->books)
            ]);
        return Inertia::render('Publisher/Index', [
            'publishers' => $publishers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Publisher/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required']
        ]);
        $new_publisher = new Publisher();
        $new_publisher->name = ucfirst($request->name);
        if ($new_publisher->save()) {
            return redirect()->back()->with('success', $new_publisher->name . ' has been add');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Publisher $publisher)
    {
        $books = Book::query();
        if (request('title')) {
            $books->where('title', 'like', '%' . request('title') . '%');
        }
        if (request('author')) {
            $author = request('author');
            $books->whereHas('authors', function ($query) use ($author) {
                $query->whereRaw('CONCAT(first_name , " " , last_name) LIKE ?', ['%' . $author . '%']);
            });
        }
        return Inertia::render('Publisher/Show', [
            'publisher' => [
                'id' => $publisher->id,
                'name' => $publisher->name,
                'addDate' => $publisher->created_at->format('d/m/y'),
                'totalBooks' => count($publisher->books),
            ],
            'books' => $books->whereHas('publishers', function ($query) use ($publisher) {
                $query->where('publishers.id', $publisher->id);
            })
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($book) => [
                    'id' => $book->id,
                    'title' => $book->title,
                    'orignalLanguage' => $book->language->name,
                    'authors' => $book->authors->map(fn ($author) => [
                        'id' => $author->id,
                        'name' => $author->full_name,
                    ]),
                    'versions' => count($book->versions) > 0 ? $book->versions->map(fn ($version) => [
                        'id' => $version->id,
                        'language' => $version->language->name,
                    ]) : null,
                    'orignal' => $book->orignal ? [
                        'id' => $book->orignal->id,
                        'language' => $book->orignal->language->name
                    ] : null
                ])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publisher $publisher)
    {
        return Inertia::render('Publisher/Edit', [
            'publisher' => [
                'id' => $publisher->id,
                'name' => $publisher->name
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publisher $publisher)
    {
        $request->validate([
            'name' => ['required']
        ]);
        $publisher->name = ucfirst($request->name);
        if ($publisher->save()) {
            return redirect()->back()->with('success', $publisher->name . ' has been Updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
