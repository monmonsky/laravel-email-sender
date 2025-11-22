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

    /**
     * Show import form
     */
    public function importForm()
    {
        $contactLists = ContactList::all()->map(function ($list) {
            return [
                'id' => $list->id,
                'name' => $list->name,
            ];
        });

        return Inertia::render('Contacts/Import', [
            'contactLists' => ['data' => $contactLists]
        ]);
    }

    /**
     * Import contacts from CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240', // Max 10MB
            'contact_list_id' => 'required|exists:contact_lists,id',
        ]);

        $file = $request->file('file');
        $contactListId = $request->contact_list_id;

        $imported = 0;
        $skipped = 0;
        $errors = [];

        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            $header = fgetcsv($handle); // Read header row

            // Validate CSV header
            $requiredColumns = ['name', 'email'];
            $headerLower = array_map('strtolower', array_map('trim', $header));

            foreach ($requiredColumns as $required) {
                if (!in_array($required, $headerLower)) {
                    fclose($handle);
                    return back()->with('error', "CSV must contain '{$required}' column!");
                }
            }

            // Map column names to indices
            $nameIndex = array_search('name', $headerLower);
            $emailIndex = array_search('email', $headerLower);
            $phoneIndex = array_search('phone', $headerLower);

            $row = 1;
            while (($data = fgetcsv($handle)) !== false) {
                $row++;

                try {
                    $name = trim($data[$nameIndex] ?? '');
                    $email = trim($data[$emailIndex] ?? '');
                    $phone = isset($phoneIndex) && isset($data[$phoneIndex]) ? trim($data[$phoneIndex]) : null;

                    // Validate required fields
                    if (empty($name) || empty($email)) {
                        $skipped++;
                        $errors[] = "Row {$row}: Name and email are required";
                        continue;
                    }

                    // Validate email format
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $skipped++;
                        $errors[] = "Row {$row}: Invalid email format - {$email}";
                        continue;
                    }

                    // Check if email already exists
                    if (Contact::where('email', $email)->exists()) {
                        $skipped++;
                        $errors[] = "Row {$row}: Email already exists - {$email}";
                        continue;
                    }

                    // Create contact
                    Contact::create([
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'contact_list_id' => $contactListId,
                        'status' => 'active',
                    ]);

                    $imported++;
                } catch (\Exception $e) {
                    $skipped++;
                    $errors[] = "Row {$row}: " . $e->getMessage();
                }
            }

            fclose($handle);
        }

        $message = "Import completed! Imported: {$imported}, Skipped: {$skipped}";

        if (!empty($errors)) {
            $errorMessage = implode("\n", array_slice($errors, 0, 10)); // Show first 10 errors
            if (count($errors) > 10) {
                $errorMessage .= "\n... and " . (count($errors) - 10) . " more errors";
            }

            return back()->with('warning', $message)
                ->with('errors_detail', $errorMessage);
        }

        return redirect()->route('contacts.index')
            ->with('success', $message);
    }

    /**
     * Export contacts to CSV
     */
    public function export(Request $request)
    {
        $query = Contact::with('contactList');

        // Filter by list if specified
        if ($request->has('list') && $request->get('list') !== 'all') {
            $query->where('contact_list_id', $request->get('list'));
        }

        $contacts = $query->get();

        $filename = 'contacts_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($contacts) {
            $file = fopen('php://output', 'w');

            // Add CSV header
            fputcsv($file, ['Name', 'Email', 'Phone', 'Contact List', 'Status', 'Created At']);

            // Add data rows
            foreach ($contacts as $contact) {
                fputcsv($file, [
                    $contact->name,
                    $contact->email,
                    $contact->phone ?? '',
                    $contact->contactList->name ?? '',
                    $contact->status,
                    $contact->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Download CSV template
     */
    public function downloadTemplate()
    {
        $filename = 'contacts_import_template.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');

            // Add CSV header
            fputcsv($file, ['name', 'email', 'phone']);

            // Add example rows
            fputcsv($file, ['John Doe', 'john@example.com', '+1234567890']);
            fputcsv($file, ['Jane Smith', 'jane@example.com', '+0987654321']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
