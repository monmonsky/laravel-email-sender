<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Contact;
use App\Models\ContactList;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get contact lists with counts
        $contactLists = ContactList::withCount('contacts')
            ->get()
            ->map(function ($list) {
                return [
                    'id' => $list->id,
                    'name' => $list->name,
                    'contacts_count' => $list->contacts_count,
                ];
            });

        // Get selected list if any
        $selectedList = null;
        if ($request->has('list')) {
            $listId = $request->get('list');
            $selectedList = $contactLists->firstWhere('id', (int)$listId);
        }

        // Get contacts, filter by list if selected
        $query = Contact::with('contactList');

        if ($request->has('list') && $request->get('list') !== 'all') {
            $query->where('contact_list_id', $request->get('list'));
        }

        $contacts = $query->latest()
            ->get()
            ->map(function ($contact) {
                return [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'phone' => $contact->phone,
                    'contact_list' => $contact->contactList->name ?? 'N/A',
                    'status' => $contact->status,
                    'created_at' => $contact->created_at->format('M d, Y'),
                ];
            });

        return Inertia::render('Contacts/Index', [
            'contacts' => ['data' => $contacts],
            'contactLists' => ['data' => $contactLists],
            'selectedList' => $selectedList,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contactLists = ContactList::all()->map(function ($list) {
            return [
                'id' => $list->id,
                'name' => $list->name,
            ];
        });

        return Inertia::render('Contacts/Create', [
            'contactLists' => ['data' => $contactLists]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:contacts,email',
            'phone' => 'nullable|string|max:20',
            'contact_list_id' => 'nullable|exists:contact_lists,id',
            'status' => 'required|in:active,unsubscribed,bounced',
        ]);

        Contact::create($validated);

        return redirect()->route('contacts.index')
            ->with('success', 'Contact created successfully!');
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
        $contact = Contact::findOrFail($id);

        $contactLists = ContactList::all()->map(function ($list) {
            return [
                'id' => $list->id,
                'name' => $list->name,
            ];
        });

        return Inertia::render('Contacts/Edit', [
            'contact' => $contact,
            'contactLists' => ['data' => $contactLists],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $contact = Contact::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:contacts,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'contact_list_id' => 'nullable|exists:contact_lists,id',
            'status' => 'required|in:active,unsubscribed,bounced',
        ]);

        $contact->update($validated);

        return redirect()->route('contacts.index')
            ->with('success', 'Contact updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()->route('contacts.index')
            ->with('success', 'Contact deleted successfully!');
    }
}
