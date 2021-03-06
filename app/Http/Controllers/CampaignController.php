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
        $graphic = $request->graphic ?? "All";
        $orderby = $request->order ?? "name";
        $ascending = 1;
        if($request->ascending === true){
            $ascending = 1;
        }else{
            $ascending = -1;
        }
        $column = array_keys(Campaign::first()->toArray());
        $operator = ["==","!=",">","<",">=","<="];

        $graph = null;
        if($graphic == "All"){
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
        }elseif($graphic == "Clicks"){
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
                            'date' => [
                                '$toString' => '$date'
                            ],
                            'clicks' => '$clicks',
                        ]
                    ],
                    [
                        '$group' => [

                            '_id' => [
                                'date' => '$date'
                            ],
                            'clicks' => [
                                '$sum' => '$clicks',
                            ],
                        ],
                    ]
                ]);
            });
        }elseif($graphic == "Impressions"){
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
                            'date' => [
                                '$toString' => '$date'
                            ],
                            'impressions' => '$impressions',
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
                        ],
                    ]
                ]);
            });
        }elseif($graphic == "CTR"){
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
                            'date' => [
                                '$toString' => '$date'
                            ],
                            'rate' => '$rate',
                        ]
                    ],
                    [
                        '$group' => [

                            '_id' => [
                                'date' => '$date'
                            ],
                            'rate' => [
                                '$sum' => '$rate',
                            ],
                        ],
                    ]
                ]);
            });
        }

        $data = CampaignCollection::raw(function ($collection) use ($start, $end, $orderby, $ascending) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'date' => [
                            '$gte' => new UTCDateTime(Carbon::parse($start . ' 00:00:00')->format('Uv')),
                            '$lte' => new UTCDateTime(Carbon::parse($end . ' 23:59:59')->format('Uv')),
                        ],
                    ],


                ],
                ['$sort' => [$orderby => $ascending]],
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
                                        '$toString' =>  '$_id'
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
        $orderby = $request->order ?? "name";
        $ascending = $request->ascending ?? true;
        if($ascending){
            $campaign = Campaign::orderBy($orderby)->with('collection')->findOrFail($id);
        }else{
            $campaign = Campaign::orderByDesc($orderby)->with('collection')->findOrFail($id);
        }

        $start = $request->start ?? Carbon::parse($campaign->start_date)->format('Y-m-d');
        $end = $request->end ?? Carbon::parse($campaign->end_date)->format('Y-m-d');
        $graphic = $request->graphic ?? "All";
        $column = array_keys(Campaign::first()->toArray());
        $operator = ["==","!=",">","<",">=","<="];
        $data_collection = CampaignCollection::raw(function ($collection) use ($start, $end, $id) {
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

        if($graphic == "All"){
            $graph = $data_collection;
        }elseif($graphic == "Clicks"){
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
                            'date' => [
                                '$toString' => '$date'
                            ],
                            'clicks' => '$clicks',
                        ]
                    ],
                ]);
            });
        }elseif($graphic == "Impressions"){
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
                        ]
                    ],
                ]);
            });
        }elseif($graphic == "CTR"){
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
                            'date' => [
                                '$toString' => '$date'
                            ],
                            'rate' => '$rate'
                        ]
                    ],
                ]);
            });
        }

        // dd($campaign);
        return Inertia::render('Campaign/DetailDashboard', [
            'campaign'      => $campaign,
            'collection'    => $data_collection,
            'statistics'    => $graph,
            'columns'       => $column,
            'operators'     => $operator,
            'start'         => $start,
            'end'           => $end,
        ]);
    }

    public function filtering_graph(Request $request){
        // dd($request->sort);
        $sort = $request->sort;
        $start = $request->start ?? Carbon::now()->subDays(29)->format('Y-m-d');
        $end = $request->end ?? Carbon::now()->format('Y-m-d');
        $graph = null;
        if($sort == "All"){
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
        }elseif($sort == "Clicks"){
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
                            'date' => [
                                '$toString' => '$date'
                            ],
                            'clicks' => '$clicks',
                        ]
                    ],
                    [
                        '$group' => [

                            '_id' => [
                                'date' => '$date'
                            ],
                            'clicks' => [
                                '$sum' => '$clicks',
                            ],
                        ],
                    ]
                ]);
            });
        }elseif($sort == "Impressions"){
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
                            'date' => [
                                '$toString' => '$date'
                            ],
                            'impressions' => '$impressions',
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
                        ],
                    ]
                ]);
            });
        }elseif($sort == "CTR"){
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
                            'date' => [
                                '$toString' => '$date'
                            ],
                            'rate' => '$rate',
                        ]
                    ],
                    [
                        '$group' => [

                            '_id' => [
                                'date' => '$date'
                            ],
                            'rate' => [
                                '$sum' => '$rate',
                            ],
                        ],
                    ]
                ]);
            });
        }
        return response()->json([
            'statistics'=>$graph
        ]);
    }
}
