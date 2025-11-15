<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Campaign;
use App\Models\Contact;
use App\Models\ContactList;
use App\Models\Template;
use App\Models\CampaignLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $totalCampaigns = Campaign::count();
        $totalContacts = Contact::where('status', 'active')->count();
        $totalContactLists = ContactList::count();
        $totalTemplates = Template::count();

        // Get campaign statistics
        $draftCampaigns = Campaign::where('status', 'draft')->count();
        $scheduledCampaigns = Campaign::where('status', 'scheduled')->count();
        $sentCampaigns = Campaign::where('status', 'sent')->count();

        // Get recent campaigns
        $recentCampaigns = Campaign::with(['contactList', 'template'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($campaign) {
                return [
                    'id' => $campaign->id,
                    'name' => $campaign->name,
                    'contact_list' => $campaign->contactList->name ?? 'N/A',
                    'status' => $campaign->status,
                    'created_at' => $campaign->created_at->format('M d, Y'),
                ];
            });

        // Get email statistics for the last 7 days
        $emailStats = CampaignLog::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) as sent"),
                DB::raw("SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed"),
                DB::raw("SUM(CASE WHEN status = 'opened' THEN 1 ELSE 0 END) as opened"),
                DB::raw("SUM(CASE WHEN status = 'clicked' THEN 1 ELSE 0 END) as clicked")
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get contact status breakdown
        $contactStats = [
            'active' => Contact::where('status', 'active')->count(),
            'unsubscribed' => Contact::where('status', 'unsubscribed')->count(),
            'bounced' => Contact::where('status', 'bounced')->count(),
        ];

        return Inertia::render('Dashboard', [
            'stats' => [
                'campaigns' => $totalCampaigns,
                'contacts' => $totalContacts,
                'contact_lists' => $totalContactLists,
                'templates' => $totalTemplates,
            ],
            'campaignStats' => [
                'draft' => $draftCampaigns,
                'scheduled' => $scheduledCampaigns,
                'sent' => $sentCampaigns,
            ],
            'recentCampaigns' => $recentCampaigns,
            'emailStats' => $emailStats,
            'contactStats' => $contactStats,
        ]);
    }
}
