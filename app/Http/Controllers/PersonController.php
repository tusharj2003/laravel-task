<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('people.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getPeople(Request $request)
    {
        if ($request->ajax()) {
            
            $query = Person::query();

            
            if ($request->filled('role')) {
                $query->where('role', $request->role);
            }

          
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    return '
                <button class="btn btn-sm btn-info editBtn" data-id="' . $row->id . '">Edit</button>
                <button class="btn btn-sm btn-danger deleteBtn" data-id="' . $row->id . '">Delete</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:people,email',
            'marital_status' => 'required|in:Married,Unmarried',
            'dob' => 'required|date',
            'role' => 'required|in:Admin,Designer',
            'designation' => 'required|string',
            'status' => 'required|in:Active,Inactive',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('photos', $filename, 'public');
            $validated['photo'] = $filename;
        }

        Person::create($validated);
        return response()->json(['success' => 'Person added']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Person $person)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return Person::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:people,email,' . $id,
            'marital_status' => 'required|in:Married,Unmarried',
            'dob' => 'required|date',
            'role' => 'required|in:Admin,Designer',
            'designation' => 'required|string',
            'status' => 'required|in:Active,Inactive',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $person = Person::findOrFail($id);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('photos', $filename, 'public');
            $validated['photo'] = $filename;
        }
        $person->update($validated);

        return response()->json(['success' => 'Person updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Person::destroy($id);
        return response()->json(['success' => 'Person deleted']);
    }
}
