<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignCollection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Str;

class CampaignCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search ? Str::of($request->search)->explode(':') : null;
        $date = $request->date;
        $campaigns = $request->campaign;
        $collection = CampaignCollection::with('campaign')->where(function ($q) use ($search, $date, $campaigns) {
            if ($search) {
                if (count($search) == 1) {

                    $q->orWhere('impressions', (int)$search[0])->orWhere('clicks', (int)$search[0])->orWhere('rate', (int)$search[0]);
                }
                if (count($search) == 2) {

                    $between = Str::of($search[1])->explode('-');

                    if (count($between) == 1) {
                        $q->where($search[0], (int)$search[1]);
                    }

                    if (count($between) == 2) {
                        $q->whereBetween($search[0], [(int)$between[0], (int)$between[1]]);
                    }
                }
            }
            if ($date) {
                $q->where('date', new UTCDateTime(Carbon::parse($date . ' 00:00:00')->format('Uv')));
            }
            if ($campaigns) {
                $q->where('campaign_id', $campaigns);
            }
        })->orderBy('created_at', 'desc')->paginate(10)->appends(array('search' => $request->search, 'date' => $request->date, 'campaign' => $request->campaign));

        // if($request->search) {
        //     $collection->orWhere('impressions', $request->search)->orWhere('clicks', $request->search)->orWhere('rate', $request->search);
        // }

        // if($request->date) {
        //     $collection->where('date', new UTCDateTime(Carbon::parse($request->date.' 00:00:00')->format('Uv')));
        // }
        // if($request->campaign) {
        //     $collection->where('campaign_id', $request->campaign_id);
        // }


        // $collection->orderBy('created_at', 'desc')->paginate(10)->appends(array('search' => $request->search, 'date' => $request->date, 'campaign' => $request->campaign));

        // return $collection;


        return Inertia::render('CampaignCollection/Index', [
            'list' => $collection,
            'campaigns' => Campaign::get(['id', 'name']),
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
