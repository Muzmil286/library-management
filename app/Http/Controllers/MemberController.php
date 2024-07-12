<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Member;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $name = request('name');
        // dd($name);
        $members = Member::query()->when($name, function ($query, $name) {
            $query->whereRaw('CONCAT(first_name,"",last_name) LIKE?', ['%' . $name . '%']);
        })->paginate(10)
            ->withQueryString()
            ->through(fn ($member) => [
                'id' => $member->id,
                'name' => $member->full_name,
                'email' => $member->email,
                'phone' => $member->phone,
            ]);
        return Inertia::render('Member/Index', [
            'members' => $members
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Member/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstName' => ['required', 'max:50', 'alpha'],
            'lastName' => ['required', 'max:50', 'alpha'],
            'address' => ['required', 'max:100',],
            'phone' => ['required', 'integer', 'unique:members'],
            'cnic' => ['integer', 'unique:members'],
            'age' => ['required', 'integer'],
            'email' => ['required', 'unique:members', 'max:50'],
        ]);
        $new_member = new Member();
        $new_member->first_name = ucfirst($request->firstName);
        $new_member->last_name = ucfirst($request->lastName);
        $new_member->address = $request->address;
        $new_member->phone = $request->phone;
        $new_member->email = $request->email;
        $new_member->cnic = $request->cnic;
        $new_member->age = $request->age;
        if ($new_member->save()) {
            return redirect()->route('members.create')->with('success', $new_member->full_name . ' has been added');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        $currentBook = Borrowing::where([
            ['return_date', '=', null],
            ['member_id', '=', $member->id]
        ])->first();

        return Inertia::render('Member/Show', [
            'member' => [
                'id' => $member->id,
                'name' => $member->full_name,
                'address' => $member->address,
                'age' => $member->age,
                'email' => $member->email,
                'phone' => $member->phone,
                'cnic' => $member->cnic,
                'totalIsuedBook' => count($member->books),
                'currentBook' => $currentBook ? [
                    'id' => $currentBook->id,
                    'bookTitle' => $currentBook->book->title,
                    'issueDate' => $currentBook->issue_date
                ] : null,
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        return Inertia::render('Member/Edit', [
            'member' => [
                'id' => $member->id,
                'firstName' => $member->first_name,
                'lastName' => $member->last_name,
                'address' => $member->address,
                'age' => $member->age,
                'email' => $member->email,
                'phone' => $member->phone,
                'cnic' => $member->cnic,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $request->validate([
            'firstName' => ['required', 'max:50', 'alpha'],
            'lastName' => ['required', 'max:50', 'alpha'],
            'address' => ['required', 'max:100',],
            'phone' => ['required', 'integer'],
            'cnic' => ['integer'],
            'age' => ['required', 'integer'],
            'email' => ['required', 'max:50'],
        ]);
        $member->first_name = ucfirst($request->firstName);
        $member->last_name = ucfirst($request->lastName);
        $member->address = $request->address;
        $member->phone = $request->phone;
        $member->email = $request->email;
        $member->cnic = $request->cnic;
        $member->age = $request->age;
        if ($member->save()) {
            return redirect()->route('members.edit', $member->id)->with('success', 'Member informations has been updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        if ($member->delete()) {
            return redirect()->route('members.index')->with('success', 'Member has been remove');
        }
    }
}
