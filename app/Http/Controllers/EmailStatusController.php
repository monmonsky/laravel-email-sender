<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\CampaignLog;
use App\Models\Campaign;
use App\Models\Contact;

class EmailStatusController extends Controller
{
    /**
     * Display email delivery status for all campaigns
     */
    public function index(Request $request)
    {
        $query = CampaignLog::with(['campaign', 'contact']);

        // Filter by campaign if provided
        if ($request->has('campaign_id') && $request->campaign_id) {
            $query->where('campaign_id', $request->campaign_id);
        }

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search by contact name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('contact', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $logs = $query->latest()
            ->paginate(20)
            ->through(function ($log) {
                return [
                    'id' => $log->id,
                    'campaign_name' => $log->campaign->name ?? 'N/A',
                    'contact_name' => $log->contact->name ?? 'N/A',
                    'contact_email' => $log->contact->email ?? 'N/A',
                    'status' => $log->status,
                    'sent_at' => $log->sent_at ? $log->sent_at->format('M d, Y H:i') : 'Not sent',
                    'opened_at' => $log->opened_at ? $log->opened_at->format('M d, Y H:i') : null,
                    'clicked_at' => $log->clicked_at ? $log->clicked_at->format('M d, Y H:i') : null,
                    'error_message' => $log->error_message,
                ];
            });

        // Get all campaigns for filter dropdown
        $campaigns = Campaign::select('id', 'name')->get();

        // Get statistics
        $stats = [
            'total' => CampaignLog::count(),
            'sent' => CampaignLog::where('status', 'sent')->count(),
            'failed' => CampaignLog::where('status', 'failed')->count(),
            'pending' => CampaignLog::where('status', 'pending')->count(),
            'opened' => CampaignLog::whereNotNull('opened_at')->count(),
            'clicked' => CampaignLog::whereNotNull('clicked_at')->count(),
        ];

        return Inertia::render('EmailStatus/Index', [
            'logs' => $logs,
            'campaigns' => $campaigns,
            'filters' => $request->only(['campaign_id', 'status', 'search']),
            'stats' => $stats,
        ]);
    }

    /**
     * Display email status for a specific contact
     */
    public function showContact(string $contactId)
    {
        $contact = Contact::findOrFail($contactId);

        $logs = CampaignLog::with('campaign')
            ->where('contact_id', $contactId)
            ->latest()
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'campaign_name' => $log->campaign->name ?? 'N/A',
                    'campaign_subject' => $log->campaign->subject ?? 'N/A',
                    'status' => $log->status,
                    'sent_at' => $log->sent_at ? $log->sent_at->format('M d, Y H:i') : 'Not sent',
                    'opened_at' => $log->opened_at ? $log->opened_at->format('M d, Y H:i') : null,
                    'clicked_at' => $log->clicked_at ? $log->clicked_at->format('M d, Y H:i') : null,
                    'error_message' => $log->error_message,
                ];
            });

        return Inertia::render('EmailStatus/Contact', [
            'contact' => [
                'id' => $contact->id,
                'name' => $contact->name,
                'email' => $contact->email,
            ],
            'logs' => $logs,
        ]);
    }

    /**
     * Display email status for a specific campaign
     */
    public function showCampaign(string $campaignId)
    {
        $campaign = Campaign::findOrFail($campaignId);

        $logs = CampaignLog::with('contact')
            ->where('campaign_id', $campaignId)
            ->latest()
            ->paginate(50)
            ->through(function ($log) {
                return [
                    'id' => $log->id,
                    'contact_name' => $log->contact->name ?? 'N/A',
                    'contact_email' => $log->contact->email ?? 'N/A',
                    'status' => $log->status,
                    'sent_at' => $log->sent_at ? $log->sent_at->format('M d, Y H:i') : 'Not sent',
                    'opened_at' => $log->opened_at ? $log->opened_at->format('M d, Y H:i') : null,
                    'clicked_at' => $log->clicked_at ? $log->clicked_at->format('M d, Y H:i') : null,
                    'error_message' => $log->error_message,
                ];
            });

        // Campaign statistics
        $stats = [
            'total' => CampaignLog::where('campaign_id', $campaignId)->count(),
            'sent' => CampaignLog::where('campaign_id', $campaignId)->where('status', 'sent')->count(),
            'failed' => CampaignLog::where('campaign_id', $campaignId)->where('status', 'failed')->count(),
            'pending' => CampaignLog::where('campaign_id', $campaignId)->where('status', 'pending')->count(),
            'opened' => CampaignLog::where('campaign_id', $campaignId)->whereNotNull('opened_at')->count(),
            'clicked' => CampaignLog::where('campaign_id', $campaignId)->whereNotNull('clicked_at')->count(),
        ];

        return Inertia::render('EmailStatus/Campaign', [
            'campaign' => [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'subject' => $campaign->subject,
            ],
            'logs' => $logs,
            'stats' => $stats,
        ]);
    }
}
