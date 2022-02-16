<?php

namespace App\Http\Controllers;

use App\Models\Advertise;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaigns = Campaign::orderBy('created_at', 'desc')->paginate(10);

        return Inertia::render('Campaign/Index', [
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $advertise = Advertise::query()->get();
        return Inertia::render('Campaign/EditAdd', [
            'action' => 'Create',
            'advertises' => $advertise,
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
        $campaign = Campaign::create([
            'name' => $request->name,
            'deposit' => $request->deposit,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
            'advertise_id' => $request->advertiseId,
            'rate' => $request->rate,
            'goal' => $request->goal,
            'impressions' => $request->impressions,
            'clicks' => $request->clicks,
        ]);

        return redirect()->route('campaigns.index')->with('success', 'Campaign created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function show(Campaign $campaign)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function edit(Campaign $campaign)
    {
        return Inertia::render('Campaign/EditAdd', [
            'action' => 'Edit',
            'campaign' => $campaign,
            'advertises' => Advertise::query()->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Campaign $campaign)
    {
        $campaign->update([
            'name' => $request->name,
            'deposit' => $request->deposit,
            'start_date' => Carbon::parse($request->startDate),
            'end_date' => Carbon::parse($request->endDate),
            'advertise_id' => $request->advertise,
            'rate' => $request->rate,
            'goal' => $request->goal,
            'impressions' => $request->impressions,
            'clicks' => $request->clicks,
        ]);

        return redirect()->route('campaigns.index')->with('success', 'Campaign updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return redirect()->route('campaigns.index')->with('success', 'Campaign deleted successfully.');
    }
}
