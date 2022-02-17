<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignCollection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use MongoDB\BSON\UTCDateTime;

class CampaignCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collection = CampaignCollection::with('campaign')->orderBy('created_at', 'desc')->paginate(10);

        return Inertia::render('CampaignCollection/Index', [
            'list' => $collection,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $campaigns = Campaign::get();
        return Inertia::render('CampaignCollection/EditAdd', [
            'action' => 'Create',
            'campaigns' => $campaigns
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $date = new UTCDateTime(Carbon::parse($request->date)->format('Uv'));
        $dateString = Carbon::parse($request->date)->format('Y-m-d H:i:s');

        $campaignCollection = CampaignCollection::create([
            'campaign_id' => $request->campaign,
            'rate' => $request->rate,
            'impressions' => $request->impressions,
            'clicks' => $request->clicks,
            'date' => $date,
            'date_string' => $dateString
        ]);

        return redirect()->route('campaign-collection.index')->with('success', 'Campaign Collection created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CampaignCollection  $campaignCollection
     * @return \Illuminate\Http\Response
     */
    public function show(CampaignCollection $campaignCollection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CampaignCollection  $campaignCollection
     * @return \Illuminate\Http\Response
     */
    public function edit(CampaignCollection $campaignCollection)
    {
        $campaigns = Campaign::get();
        return Inertia::render('CampaignCollection/EditAdd', [
            'action' => 'Edit',
            'campaignCollection' => $campaignCollection,
            'campaigns' => $campaigns
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CampaignCollection  $campaignCollection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CampaignCollection $campaignCollection)
    {

        $date = new UTCDateTime(Carbon::parse($request->date)->format('Uv'));
        $dateString = Carbon::parse($request->date)->format('Y-m-d H:i:s');
        $campaignCollection->update([
            'campaign_id' => $request->campaign,
            'rate' => $request->rate,
            'impressions' => $request->impressions,
            'clicks' => $request->clicks,
            'date' => $date,
            'date_string' => $dateString
        ]);

        return redirect()->route('campaign-collection.index')->with('success', 'Campaign Collection updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CampaignCollection  $campaignCollection
     * @return \Illuminate\Http\Response
     */
    public function destroy(CampaignCollection $campaignCollection)
    {
        $campaignCollection->delete();
        return redirect()->route('campaign-collection.index')->with('success', 'Campaign Collection deleted successfully');
    }
}
