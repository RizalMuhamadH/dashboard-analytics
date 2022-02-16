<?php

namespace App\Http\Controllers;

use App\Models\AdvertiseCollection;
use DateTimeImmutable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CalculateRevenueAdvertiseController extends Controller
{
    public function index(Request $request)
    {
        
        return Inertia::render('CalculateRevenueAdvertise/Index', [
            // 'revenue' => $revenue,
        ]);
    }

    public function record(Request $request)
    {
        $start = $request->start ?? "";
        $end = $request->end ?? "";

        $revenue = AdvertiseCollection::raw(function ($collection) use($start, $end) {
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

                        '_id' => ['name' => '$name'],
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

        return redirect()->back()->with('data', $revenue);

    }
}
