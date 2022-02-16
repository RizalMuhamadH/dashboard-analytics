<?php

namespace App\Http\Controllers;

use App\Http\Resources\CalculateResource;
use App\Models\AdvertiseCollection;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Website;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Analytics;
use Spatie\Analytics\Period;

class CalculateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $revenue = Website::where('alias', 'ayoindonesia.com')
        //     ->with('collection')
        //     ->get()
        //     ->map(function (Website $website) {
        //         $revenue = $website->collection->sum('revenue');
        //         $website->revenue = $revenue;
        //         return $website;
        //     });

        // $revenue = Website::first()->collection->sum('revenue');

        $analyticsData = Analytics::performQuery(
            Period::days(1),
            'ga:hostname',
            [
                'metrics' => 'ga:pageviews',
                'dimensions' => 'ga:dimension7, ga:dimension9'
            ]
        );

        // dd($analyticsData);

        // $analyticsData = Analytics::fetchMostVisitedPages(Period::days(7));


        return response()->json([
            'success' => true,
            'data' => $analyticsData
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
