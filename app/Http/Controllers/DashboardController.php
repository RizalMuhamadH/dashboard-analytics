<?php

namespace App\Http\Controllers;

use App\Models\CampaignCollection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use MongoDB\BSON\UTCDateTime;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // $collection = DB::collection('advertise_collections')->groupBy('date')->sum('revenue');
        // return $collection;
        $start = $request->start ?? Carbon::now()->subDays(7)->format('Y-m-d');
        $end = $request->end ?? Carbon::now()->format('Y-m-d');


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
                        'date' => [
                            '$toDate' => '$date'
                        ],
                        'clicks' => '$clicks',
                        'rate' => '$rate'
                    ]
                ],
                [
                    '$lookup' => [
                        'from' => 'campaigns',
                        'localField' => "campaign_id",
                        'foreignField' => "_id",
                        'as' => "campaign"
                    ]
                ],
                [
                    '$group' => [

                        '_id' => [
                            'campaign_id' => '$campaign_id',
                            'campaign' => '$campaign',
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


        return Inertia::render('Dashboard', [
            'statistics' => $graph,
            'data' => $data,
        ]);
    }
}
