<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ContactList;

class ContactListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contactLists = ContactList::withCount('contacts')
            ->latest()
            ->get()
            ->map(function ($list) {
                return [
                    'id' => $list->id,
                    'name' => $list->name,
                    'description' => $list->description,
                    'contacts_count' => $list->contacts_count,
                    'created_at' => $list->created_at->format('M d, Y'),
                ];
            });

        return Inertia::render('ContactLists/Index', [
            'contactLists' => ['data' => $contactLists]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('ContactLists/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        ContactList::create($validated);

        return redirect()->route('contact-lists.index')
            ->with('success', 'Contact list created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $contactList = ContactList::findOrFail($id);

        return Inertia::render('ContactLists/Edit', [
            'contactList' => $contactList
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $contactList = ContactList::findOrFail($id);
        $contactList->update($validated);

        return redirect()->route('contact-lists.index')
            ->with('success', 'Contact list updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contactList = ContactList::findOrFail($id);
        $contactList->delete();

        return redirect()->route('contact-lists.index')
            ->with('success', 'Contact list deleted successfully!');
    }
}
