<?php

namespace App\Http\Controllers;

use App\Models\AdvertiseCollection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // $collection = DB::collection('advertise_collections')->groupBy('date')->sum('revenue');
        // return $collection;
        $date = Carbon::now()->year;
        $start = $date.'-01-01';
        $end = $date.'-12-31';

        $data = AdvertiseCollection::raw(function ($collection) use($start, $end) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'date_string' => [
                            '$gte' => $start.' 00:00:00',
                            '$lte' => $end.' 23:59:59',
                        ],
                    ],
                ],
                [
                    '$group' => [

                        '_id' => [
                            'month' => 
                                [
                                    '$month' => '$date'
                                ],
                            ],
                        'revenue' => [
                            '$sum' => '$revenue',
                        ],
                        'page_views' => [
                            '$sum' => '$page_views',
                        ],
                        'views' => [
                            '$sum' => '$views',
                        ],
                        'rpm' => [
                            '$sum' => '$rpm',
                        ]
                    ],
                ]
            ]);
        });

        return Inertia::render('Dashboard', [
            'statistics' => $data,
        ]);
    }
}
