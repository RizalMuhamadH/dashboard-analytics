<?php

namespace App\Http\Controllers;

use App\Models\Advertise;
use App\Models\Campaign;
use App\Models\CampaignCollection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;
use MongoDB\Collection;

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

    public function dashboard(Request $request){
        // $collection = DB::collection('advertise_collections')->groupBy('date')->sum('revenue');
        // return $collection;
        $start = $request->start ?? Carbon::now()->subDays(29)->format('Y-m-d');
        $end = $request->end ?? Carbon::now()->format('Y-m-d');
        $column = array_keys(Campaign::first()->toArray());
        $operator = ["==","!=",">","<",">=","<="];


        $graph = CampaignCollection::raw(function ($collection) use ($start, $end) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'date' => [
                            '$gte' => new UTCDateTime(Carbon::parse($start . ' 00:00:00')->format('Uv')),
                            '$lte' => new UTCDateTime(Carbon::parse($end . ' 23:59:59')->format('Uv')),
                        ],
                    ],
                ],
                [
                    '$project' => [
                        'campaign_id' => [
                            '$toObjectId' => '$campaign_id'
                        ],
                        'impressions' => '$impressions',
                        'date' => [
                            '$toString' => '$date'
                        ],
                        'clicks' => '$clicks',
                        'rate' => '$rate'
                    ]
                ],
                [
                    '$group' => [

                        '_id' => [
                            'date' => '$date'
                        ],
                        'impressions' => [
                            '$sum' => '$impressions',
                        ],
                        'clicks' => [
                            '$sum' => '$clicks',
                        ],
                        'rate' => [
                            '$sum' => '$rate',
                        ]
                    ],
                ]
            ]);
        });

        $data = CampaignCollection::raw(function ($collection) use ($start, $end) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'date' => [
                            '$gte' => new UTCDateTime(Carbon::parse($start . ' 00:00:00')->format('Uv')),
                            '$lte' => new UTCDateTime(Carbon::parse($end . ' 23:59:59')->format('Uv')),
                        ],
                    ],
                ],
                [
                    '$project' => [
                        'campaign_id' => [
                            '$toObjectId' => '$campaign_id'
                        ],
                        'impressions' => '$impressions',
                        'clicks' => '$clicks',
                        'rate' => '$rate',
                    ]
                ],
                [
                    '$lookup' => [
                        'from' => 'campaigns',
                        'localField' => "campaign_id",
                        'foreignField' => "_id",
                        'as' => "campaign",
                        'pipeline' => [
                            [
                                '$project' => [
                                    '_id' => [
                                        '$toString' => '$_id'
                                    ],
                                    'start_date' => [
                                        '$toString' => '$start_date'
                                    ],
                                    'end_date' => [
                                        '$toString' => '$end_date'
                                    ],
                                    'camp_id'   => [
                                        '$toString' =>  '$campaign_id'
                                    ],
                                    'name' => '$name',
                                    'deposit' => '$deposit',
                                    'goal' => '$goal',
                                ]
                            ]
                        ]
                    ]
                ],

                ['$unwind' => '$campaign'],
                [
                    '$group' => [

                        '_id' => [
                            'campaign_id' => '$campaign_id',
                        ],
                        'impressions' => [
                            '$sum' => '$impressions',
                        ],
                        'clicks' => [
                            '$sum' => '$clicks',
                        ],
                        'rate' => [
                            '$sum' => '$rate',
                        ],
                        'campaign' => [
                            '$first' => '$campaign',
                        ],
                    ],
                ]
            ]);
        });

        // dd($data);
        return Inertia::render('Campaign/Dashboard', [
            'campaigns'     => $data,
            'statistics'    => $graph,
            'columns'       => $column,
            'operators'     => $operator,
            'start'         => $start,
            'end'           => $end,
        ]);
    }

    public function detail(Request $request, $id){
        $campaign = Campaign::with('collection')->findOrFail($id);
        $start = $request->start ?? Carbon::parse($campaign->start_date)->format('Y-m-d');
        $end = $request->end ?? Carbon::parse($campaign->end_date)->format('Y-m-d');
        $column = array_keys(Campaign::first()->toArray());
        $operator = ["==","!=",">","<",">=","<="];


        $graph = CampaignCollection::raw(function ($collection) use ($start, $end, $id) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'date' => [
                            '$gte' => new UTCDateTime(Carbon::parse($start . ' 00:00:00')->format('Uv')),
                            '$lte' => new UTCDateTime(Carbon::parse($end . ' 23:59:59')->format('Uv')),
                        ],
                    ],
                ],
                [
                    '$match' => [
                        'campaign_id' => $id,
                    ]
                ],
                [
                    '$project' => [
                        'campaign_id' => '$campaign_id',
                        'impressions' => '$impressions',
                        'date' => [
                            '$toString' => '$date'
                        ],
                        'clicks' => '$clicks',
                        'rate' => '$rate'
                    ]
                ],
            ]);
        });
        // return $graph;


        
        return Inertia::render('Campaign/DetailDashboard', [
            'campaign'      => $campaign,
            'collection'    => $graph,
            'statistics'    => $graph,
            'columns'       => $column,
            'operators'     => $operator,
            'start'         => $start,
            'end'           => $end,
        ]);
    }

    public function filtering(Request $request){

    }
}
