<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $borrowings = Borrowing::query();
        if (request('title')) {
            $title = request('title');
            $borrowings->whereHas('book', function ($query) use ($title) {
                $query->where('title', 'like', "%$title%");
            });
        }
        if (request('user')) {
            $user = request('user');
            $borrowings->whereHas('member', function ($query) use ($user) {
                $query->whereRaw('CONCAT(first_name," ",last_name) LIKE?', ['%' . $user . '%']);
            });
        }

        return Inertia::render('Borrowing/Index', [
            'borrowings' => $borrowings->where('return_date', null)
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($borrow) => [
                    'id' => $borrow->id,
                    'book' => [
                        'id' => $borrow->book_id,
                        'title' => $borrow->book->title,
                    ],
                    'member' => [
                        'id' => $borrow->member_id,
                        'name' => $borrow->member->full_name
                    ]
                ])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::all()->map(fn ($member) => [
            'value' => $member->id,
            'label' => $member->full_name
        ]);
        $books = Book::all()->map(fn ($book) => [
            'value' => $book->id,
            'label' => $book->title
        ]);
        return Inertia::render('Borrowing/Create', [
            'members' => $members,
            'books' => $books
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'issueDate' => ['required', 'date'],
            'member' => ['required'],
            'book' => ['required'],
        ]);
        $member = Borrowing::where([
            ['return_date', '=', null],
            ['member_id', '=', $request->member]
        ])->count();
        if ($member > 0) {
            return redirect()->back()->withErrors([
                'member' => 'Already have a book ask for return first'
            ]);
        }
        $book_copy = Book::where('id', $request->book)->sum('copy');
        $borrowed_copy = Borrowing::where([
            ['return_date', '=', null],
            ['book_id', '=', $request->book],
        ])->count();
        // dd($borrowed_copy);
        if ($borrowed_copy >= $book_copy) {
            return redirect()->back()->withErrors([
                'book' => 'All copy has been borrowed'
            ]);
        }
        $borrow_book = new Borrowing();
        $borrow_book->issue_date = $request->issueDate;
        $borrow_book->book_id = $request->book;
        $borrow_book->member_id = $request->member;
        if ($borrow_book->save()) {
            return redirect()->back()->with('success', 'Book has been borrowod');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Inertia::render('Borrowing/Show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrowing $borrowing)
    {
        $members = Member::all()->map(fn ($member) => [
            'value' => $member->id,
            'label' => $member->full_name
        ]);
        $books = Book::all()->map(fn ($book) => [
            'value' => $book->id,
            'label' => $book->title
        ]);
        return Inertia::render('Borrowing/Edit', [
            'members' => $members,
            'books' => $books,
            'borrowing' => $borrowing
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'issueDate' => ['required', 'date'],
            'member' => ['required'],
            'book' => ['required'],
        ]);

        $borrowing->issue_date = $request->issueDate;
        $borrowing->book_id = $request->book;
        $borrowing->member_id = $request->member;
        if ($borrowing->save()) {
            return redirect()->back()->with('success', 'Book has been borrowod');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function bookReturn(Borrowing $borrowing)
    {
        $borrowing->return_date = date('y/m/d');
        if ($borrowing->save()) {
            return redirect()->back()->with('success', 'Book has been retrun');
        };
    }
}
