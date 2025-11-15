<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Sender;

class SenderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $senders = Sender::latest()->get();

        return Inertia::render('Senders/Index', [
            'senders' => $senders
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Senders/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'from_email' => 'required|email|max:255',
            'from_name' => 'required|string|max:255',
            'smtp_host' => 'required|string|max:255',
            'smtp_port' => 'required|integer',
            'smtp_username' => 'required|string|max:255',
            'smtp_password' => 'required|string|max:255',
            'smtp_encryption' => 'nullable|in:tls,ssl',
            'is_default' => 'boolean',
        ]);

        // If this sender is set as default, unset others
        if ($validated['is_default'] ?? false) {
            Sender::where('is_default', true)->update(['is_default' => false]);
        }

        Sender::create($validated);

        return redirect()->route('senders.index')
            ->with('success', 'Sender created successfully!');
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
        $sender = Sender::findOrFail($id);

        return Inertia::render('Senders/Edit', [
            'sender' => $sender
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $sender = Sender::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'from_email' => 'required|email|max:255',
            'from_name' => 'required|string|max:255',
            'smtp_host' => 'required|string|max:255',
            'smtp_port' => 'required|integer',
            'smtp_username' => 'required|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable|in:tls,ssl',
            'is_default' => 'boolean',
        ]);

        // If password not provided, keep the old one
        if (empty($validated['smtp_password'])) {
            unset($validated['smtp_password']);
        }

        // If this sender is set as default, unset others
        if ($validated['is_default'] ?? false) {
            Sender::where('id', '!=', $id)->update(['is_default' => false]);
        }

        $sender->update($validated);

        return redirect()->route('senders.index')
            ->with('success', 'Sender updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sender = Sender::findOrFail($id);

        // Prevent deleting default sender
        if ($sender->is_default) {
            return redirect()->route('senders.index')
                ->with('error', 'Cannot delete the default sender!');
        }

        $sender->delete();

        return redirect()->route('senders.index')
            ->with('success', 'Sender deleted successfully!');
    }

    /**
     * Set sender as default
     */
    public function setDefault(string $id)
    {
        $sender = Sender::findOrFail($id);

        // Unset all other defaults
        Sender::where('is_default', true)->update(['is_default' => false]);

        // Set this as default
        $sender->update(['is_default' => true]);

        return redirect()->route('senders.index')
            ->with('success', 'Default sender updated successfully!');
    }
}
