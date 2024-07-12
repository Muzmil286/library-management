<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\Language;
use App\Models\Member;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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
                    $query->whereRaw('CONCAT(first_name," " , last_name) LIKE ?', ['%' . $author . '%']);
                });
            });
        }
        if (request('language')) {
            $books->when(request('language'), function ($query, $language) {
                $query->whereHas('language', function ($query) use ($language) {
                    $query->where('name', 'like', "%$language%");
                });
            });
        }

        return Inertia::render('Book/Index', [
            'books' => $books->paginate(10)->through(fn ($book) => [
                'id' => $book->id,
                'title' => $book->title,
                'authors' => $book->authors->map(fn ($author) => [
                    'id' => $author->id,
                    'name' => $author->full_name
                ]),
                'category' => $book->category->name ?? null
            ]),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all()->map(fn ($category) => [
            'value' => $category->id,
            'label' => $category->name
        ]);
        $languages = Language::all()->map(fn ($language) => [
            'value' => $language->id,
            'label' => $language->name
        ]);
        $publishers = Publisher::all()->map(fn ($publisher) => [
            'value' => $publisher->id,
            'label' => $publisher->name
        ]);
        $authors  = Author::all()->map(fn ($author) => [
            'value' => $author->id,
            'label' => $author->full_name
        ]);
        // dd($publishers);

        return Inertia::render('Book/Create', [
            'categories' => $categories,
            'languages' => $languages,
            'publishers' => $publishers,
            'authors' => $authors
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        if ($request->picked) {
            $request->validate([
                'book' => ['required', 'integer']
            ]);
        }
        $request->validate([
            'category' => ['required'],
            'language' => ['required'],
            'copy' => ['required'],
            'publishers' => ['required'],
            'authors' => ['required'],
            'publishDate' => ['required', 'date'],
            'language' => ['required']
        ]);
        $new_book = new Book();
        $new_book->category_id = is_array($request->category) ? $request->category['value'] : $request->category;
        $new_book->title = ucfirst($request->title);
        $new_book->copy = $request->copy;
        $new_book->language_id = $request->language;
        $new_book->publish_date = $request->publishDate;
        // dd($new_book);
        if ($new_book->save()) {
            $new_book->publishers()->sync($request->publishers);
            $new_book->authors()->sync($request->authors);
            if ($request->picked == 'isTranslation') {
                $new_book->is_translation = 1;
                $new_book->parent_id = $request->book;
                $new_book->save();
            }
            if ($request->picked == 'isOrigen') {
                $translations = Book::find($request->book);
                $translations->is_translation = 1;
                $translations->parent_id = $new_book->id;
                $translations->save();
            }
            return redirect()->back()->with('success', $new_book->title . ' has been added');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $notReturn = Borrowing::where([
            ['book_id', '=', $book->id],
            ['return_date', '=', null]
        ])->count();
        $avialbaleCopy = $book->copy - $notReturn;

        $members = Borrowing::where([
            ['book_id', '=', $book->id],
            ['return_date', '=', null]
        ])->get()->map(fn ($member) => [
            'id' => $member->member->id,
            'name' => $member->member->full_name
        ]);

        return Inertia::render('Book/Show', [
            'book' => [
                'id' => $book->id,
                'title' => $book->title,
                'category' => $book->category->name ?? null,
                'language' => $book->language->name ?? null,
                'copy' => $book->copy,
                'availableCopy' => $avialbaleCopy,
                'members' => $members,
                'authors' => $book->authors->map(fn ($author) => [
                    'name' => $author->full_name
                ]),
                'versions' => count($book->versions) > 0 ? $book->versions->map(fn ($version) => [
                    'id' => $version->id,
                    'language' => $version->language->name
                ]) : null,

                'orignal' => $book->orignal ? ['id' => $book->orignal->id, 'language' => $book->orignal->language->name] : null


            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $categories = Category::all()->map(fn ($category) => [
            'value' => $category->id,
            'label' => $category->name
        ]);
        $authors = Author::all()->map(fn ($author) => [
            'value' => $author->id,
            'label' => $author->full_name
        ]);
        $languages = Language::all()->map(fn ($language) => [
            'value' => $language->id,
            'label' => $language->name
        ]);
        $publishers = Publisher::all()->map(fn ($publisher) => [
            'value' => $publisher->id,
            'label' => $publisher->name
        ]);

        return Inertia::render('Book/Edit', [
            'book' => [
                'id' => $book->id,
                'title' => $book->title,
                'versions' => count($book->versions) > 0 ? $book->versions->map(fn ($version) => [
                    'value' => $version->id,
                    'label' => $version->title
                ]) : null,
                'orignal' => $book->orignal ? ['value' => $book->orignal->id, 'label' => $book->orignal->title] : null,
                'copy' => $book->copy,
                'category' => $book->category_id,
                'language' => $book->language_id,
                'publishDate' => $book->publish_date,
                'authors' => $book->authors->map(fn ($author) => [
                    'value' => $author->id,

                ]),
                'publishers' => $book->publishers->map(fn ($publisher) => [
                    'value' => $publisher->id,
                ]),
                'picked' => $book->is_translation > 0 ? 'translation' : 'orignal'
            ],
            'categories' => $categories,
            'authors' => $authors,
            'languages' => $languages,
            'publishers' => $publishers
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {


        if ($request->picked) {
            $request->validate([
                'book' => ['required']
            ]);
        }
        $request->validate([
            'category' => ['required'],
            'language' => ['required'],
            'copy' => ['required'],
            'publishers' => ['required'],
            'authors' => ['required'],
            'publishDate' => ['required', 'date'],
            'language' => ['required']
        ]);
        $book->category_id = $request->category;
        $book->title = ucfirst($request->title);
        $book->copy = $request->copy;
        $book->language_id = $request->language;
        $book->publish_date = $request->publishDate;
        if ($book->save()) {
            $publishers = [];
            foreach ($request->publishers as $publisher) {
                $publishers[] = $publisher['value'];
            };
            $authors = [];
            foreach ($request->authors as $author) {
                $authors[] = $author['value'];
            }

            $book->publishers()->sync($publishers);
            $book->authors()->sync($authors);
            if ($request->picked == 'translation') {
                $orignal_book = $request->book['value'];
                $orignal_book = Book::find($orignal_book);
                $orignal_book->category_id = $book->category_id;
                $orignal_book->save();

                // $orignal_category = $request->book['value'];
                // $category = Book::select('category_id')->where('id', $orignal_category)->first();
                $book->is_translation = 1;
                // $book->category_id = $category;
                $book->parent_id = $request->book['value'];
                $book->update();
            }
            if ($request->picked == 'orignal' && is_array($request->book)) {
                $versions = [];
                foreach ($request->book as $version) {
                    $book_versions = Book::find($version['value']);
                    $book_versions->category_id = $book->category_id;
                    $book_versions->parent_id = $book->id;
                    $book_versions->is_translation = 1;
                    $book_versions->save();
                }
            }
            return redirect()->back()->with('success', $book->title . ' has been updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        if ($book->delete()) {
            return redirect()->back()->with('success', 'Book has been deleted');
        }
    }

    public function books()
    {
        if (request('version')) {
            $book = Book::find(request('version'));
            return response()->json([
                'value' => $book->category_id,
                'label' => $book->category->name
            ]);
        }
        $books = Book::query()->when(request('title'), function ($query, $title) {
            $query->where('title', '!=', $title);
        })->get()->map(fn ($book) => [
            'value' => $book->id,
            'label' => $book->title
        ]);

        return response()->json($books);
    }
}
