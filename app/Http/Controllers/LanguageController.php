<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Language;
use Illuminate\Http\Request;
use Inertia\Inertia;

use function Termwind\render;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $languages = Language::query()->when(request('name'), function ($query, $name) {
            $query->where('name', 'like', "%$name%");
        })
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($language) => [
                'id' => $language->id,
                'name' => $language->name,
                'totalBooks' => count($language->books)
            ]);
        return Inertia::render('Language/Index', [
            'languages' => $languages
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Language/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:30', 'string', 'unique:languages'],
            'name.unique' => "Name already exist"
        ]);
        $newLanguage = new Language();
        $newLanguage->name = ucfirst($request->name);
        $newLanguage->save();
        return redirect()->route('languages.create')->with('success', $newLanguage->name . ' has been added');
    }

    /**
     * Display the specified resource.
     */
    public function show(Language $language)
    {
        $author = request('author');
        $books = Book::query();
        // dd($books->where('language_id', $language->id)->paginate(10);

        // $books = Book::query();
        if (request('title')) {

            $books->when(request('title'), function ($query, $title) {
                $query->where('title', 'like', "%$title%");
            });
        }
        if ($author) {
            $books->when($author, function ($query, $author) {
                $query->whereHas('authors', function ($query) use ($author) {
                    $query->whereRaw('CONCAT(first_name ," " , last_name) LIKE?', ['%' . $author . '%']);
                });
            });
        }
        return Inertia::render('Language/Show', [
            'language' => [
                'id' => $language->id,
                'name' => $language->name,
                'totalBooks' => count($language->books),
                'addedAgo' => $language->created_at->format('d/m/y')
            ],
            'books' => $books->where('language_id', $language->id)->paginate(10)
                ->withQueryString()
                ->through(fn ($book) => [
                    'id' => $book->id,
                    'title' => $book->title,
                    'categoryId' => $book->category->id ?? null,
                    'categoryName' => $book->category->name ?? null,
                    'authors' => $book->authors->map(fn ($author) => [
                        'id' => $author->id,
                        'name' => $author->full_name
                    ]),
                ])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Language $language)
    {
        return Inertia::render('Language/Edit', [
            'language' => [
                'id' => $language->id,
                'name' => $language->name
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Language $language)
    {
        $message = $language->name;
        $request->validate([
            'name' => ['required', 'max:30', 'unique:languages'],
            'name.unique' => 'Language already exsit'
        ]);
        $language->name = ucfirst($request->name);
        $language->save();
        return redirect()->route('languages.edit', $language->id)->with('success', $message .  'has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Language $language)
    {
        $message = $language->name;
        if ($language->delete()) {
            return redirect()->route('languages.index')->with('success', $message .  ' has been deleted');
        }
    }
}
