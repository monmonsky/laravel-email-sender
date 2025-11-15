<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Template;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = Template::latest()
            ->get()
            ->map(function ($template) {
                return [
                    'id' => $template->id,
                    'name' => $template->name,
                    'subject' => $template->subject,
                    'body' => $template->body,
                    'created_at' => $template->created_at->format('M d, Y'),
                ];
            });

        return Inertia::render('Templates/Index', [
            'templates' => $templates
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Templates/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        Template::create($validated);

        return redirect()->route('templates.index')
            ->with('success', 'Template created successfully!');
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
        $template = Template::findOrFail($id);

        return Inertia::render('Templates/Edit', [
            'template' => $template
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $template = Template::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $template->update($validated);

        return redirect()->route('templates.index')
            ->with('success', 'Template updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $template = Template::findOrFail($id);
        $template->delete();

        return redirect()->route('templates.index')
            ->with('success', 'Template deleted successfully!');
    }

    /**
     * Duplicate a template
     */
    public function duplicate(string $id)
    {
        $template = Template::findOrFail($id);

        $newTemplate = $template->replicate();
        $newTemplate->name = $template->name . ' (Copy)';
        $newTemplate->save();

        return redirect()->route('templates.index')
            ->with('success', 'Template duplicated successfully!');
    }
}
