<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryCantroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::query()->when(request('name'), function ($query, $name) {
            $query->where('name', 'like', "%$name%");
        })
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($category) => [
                'id' => $category->id,
                'name' => $category->name,
                'totalBooks' => count($category->books)
            ]);
        return Inertia::render('Category/Index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Category/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['alpha:ascii', 'min:1', 'max:30', 'unique:categories'],

        ], ['name.unique' => ucfirst($request->name) . ' already exist']);
        $newCategory = new Category();
        $newCategory->name = ucfirst($request->name);
        $newCategory->save();
        return redirect()->route('categories.create')->with('success', $newCategory->name . ' Category added');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {

        $books = Book::query();
        if (request('title')) {
            $books->when(request('title'), function ($query, $title) {
                $query->where('title', 'like', "%$title%");
            });
        }
        if (request('author')) {
            $books->when(request('author'), function ($query, $author) {
                $query->whereHas('authors', function ($query) use ($author) {
                    $query->whereRaw('CONCAT(first_name,"" ,last_name) LIKE?', ['%' . $author . '%']);
                });
            });
        }
        return Inertia::render('Category/Show', [
            'books' => $books->where('category_id', $category->id)
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($book) => [
                    'id' => $book->id,
                    'title' => $book->title,
                    'orignalLanguage' => $book->language->name,
                    'versions' => count($book->versions) > 0 ? $book->versions->map(fn ($version) => [
                        'id' => $version->id,
                        'language' => $version->language->name
                    ]) : null,
                    'orignal' => $book->orignal ? [
                        'id' => $book->orignal->id,
                        'language' => $book->orignal->language->name,
                    ] : null,
                    'authors' => $book->authors->map(fn ($author) => [
                        'id' => $author->id,
                        'name' => $author->full_name
                    ])
                ]),

            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'createDate' => $category->created_at->format('d/m/y'),
                'totalBooks' => count($category->books)
            ],

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return Inertia::render('Category/Edit', [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,

            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $message = $category->name;
        $request->validate([
            'name' => ['required', 'min:1', 'max:30', 'unique:categories', 'alpha:ascii'],

        ], ['name.unique' => request('name') . ' Category already exist']);
        $category->name = ucfirst($request->name);
        $category->save();
        return redirect()->route('categories.edit', $category->id)->with('success', $message . ' has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $message = $category->name;
        if ($category->delete()) {
            return redirect()->route('categories.index')->with('success', $message . ' has been deleted');
        }
    }
}
