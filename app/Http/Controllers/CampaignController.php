<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Campaign;
use App\Models\ContactList;
use App\Models\Template;
use App\Models\Contact;
use App\Models\Sender;
use App\Jobs\SendCampaignEmail;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Campaign::with(['contactList', 'template']);

        // Filter by search (name or subject)
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('subject', 'ILIKE', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by contact list
        if ($request->has('contact_list') && $request->contact_list !== 'all') {
            $query->where('contact_list_id', $request->contact_list);
        }

        $campaigns = $query->latest()
            ->paginate(10)
            ->withQueryString()
            ->through(function ($campaign) {
                return [
                    'id' => $campaign->id,
                    'name' => $campaign->name,
                    'contact_list' => $campaign->contactList->name ?? 'N/A',
                    'contact_list_id' => $campaign->contact_list_id,
                    'subject' => $campaign->subject,
                    'status' => $campaign->status,
                    'created_at' => $campaign->created_at->format('M d, Y H:i:s'),
                ];
            });

        // Get contact lists for filter dropdown
        $contactLists = ContactList::all()->map(function ($list) {
            return [
                'id' => $list->id,
                'name' => $list->name,
            ];
        });

        return Inertia::render('Campaigns/Index', [
            'campaigns' => $campaigns,
            'contactLists' => ['data' => $contactLists],
            'filters' => [
                'search' => $request->search,
                'status' => $request->status ?? 'all',
                'contact_list' => $request->contact_list ?? 'all',
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contactLists = ContactList::withCount('contacts')->get()->map(function ($list) {
            return [
                'id' => $list->id,
                'name' => $list->name,
                'contacts_count' => $list->contacts_count,
            ];
        });

        $templates = Template::all()->map(function ($template) {
            return [
                'id' => $template->id,
                'name' => $template->name,
                'subject' => $template->subject,
                'body' => $template->body,
            ];
        });

        $senders = Sender::all()->map(function ($sender) {
            return [
                'id' => $sender->id,
                'name' => $sender->name,
                'from_email' => $sender->from_email,
                'from_name' => $sender->from_name,
                'is_default' => $sender->is_default,
            ];
        });

        return Inertia::render('Campaigns/Create', [
            'contactLists' => ['data' => $contactLists],
            'templates' => ['data' => $templates],
            'senders' => ['data' => $senders],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_list_id' => 'required|exists:contact_lists,id',
            'template_id' => 'nullable|exists:templates,id',
            'sender_id' => 'nullable|exists:senders,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,scheduled,sent',
        ]);

        Campaign::create($validated);

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign created successfully!');
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
        $campaign = Campaign::findOrFail($id);

        $contactLists = ContactList::withCount('contacts')->get()->map(function ($list) {
            return [
                'id' => $list->id,
                'name' => $list->name,
                'contacts_count' => $list->contacts_count,
            ];
        });

        $templates = Template::all()->map(function ($template) {
            return [
                'id' => $template->id,
                'name' => $template->name,
                'subject' => $template->subject,
                'body' => $template->body,
            ];
        });

        $senders = Sender::all()->map(function ($sender) {
            return [
                'id' => $sender->id,
                'name' => $sender->name,
                'from_email' => $sender->from_email,
                'from_name' => $sender->from_name,
                'is_default' => $sender->is_default,
            ];
        });

        return Inertia::render('Campaigns/Edit', [
            'campaign' => $campaign,
            'contactLists' => ['data' => $contactLists],
            'templates' => ['data' => $templates],
            'senders' => ['data' => $senders],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $campaign = Campaign::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_list_id' => 'required|exists:contact_lists,id',
            'template_id' => 'nullable|exists:templates,id',
            'sender_id' => 'nullable|exists:senders,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,scheduled,sent',
        ]);

        $campaign->update($validated);

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->delete();

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign deleted successfully!');
    }

    /**
     * Preview campaign email
     */
    public function preview(string $id)
    {
        $campaign = Campaign::with(['sender'])->findOrFail($id);

        // Get first contact from the list for preview
        $contact = Contact::where('contact_list_id', $campaign->contact_list_id)->first();

        if (!$contact) {
            return back()->with('error', 'No contacts found in the selected list!');
        }

        // Replace variables
        $subject = $this->replaceVariables($campaign->subject, $contact);
        $content = $this->replaceVariables($campaign->content, $contact);

        $sender = $campaign->sender_id
            ? Sender::find($campaign->sender_id)
            : Sender::where('is_default', true)->first();

        $senderName = $sender ? $sender->from_name : config('mail.from.name');

        return Inertia::render('Campaigns/Preview', [
            'campaign' => $campaign,
            'subject' => $subject,
            'content' => $content,
            'senderName' => $senderName,
            'previewContact' => $contact,
        ]);
    }

    /**
     * Replace variables in text
     */
    private function replaceVariables($text, $contact)
    {
        $variables = [
            '{{name}}' => $contact->name,
            '{{email}}' => $contact->email,
            '{{phone}}' => $contact->phone ?? '',
        ];

        return str_replace(array_keys($variables), array_values($variables), $text);
    }

    /**
     * Send campaign to all contacts in the contact list
     */
    public function send(string $id)
    {
        $campaign = Campaign::with('contactList')->findOrFail($id);

        // Get all active contacts from the contact list
        $contacts = Contact::where('contact_list_id', $campaign->contact_list_id)
            ->where('status', 'active')
            ->get();

        if ($contacts->isEmpty()) {
            return redirect()->route('campaigns.index')
                ->with('error', 'No active contacts found in the selected list!');
        }

        // Dispatch jobs for each contact
        foreach ($contacts as $contact) {
            SendCampaignEmail::dispatch($campaign, $contact);
        }

        // Update campaign status to sent
        $campaign->update(['status' => 'sent']);

        return redirect()->route('campaigns.index')
            ->with('success', "Campaign queued for sending to {$contacts->count()} contacts!");
    }
}
