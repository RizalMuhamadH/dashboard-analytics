<?php

namespace App\Http\Controllers;

use App\Models\Advertise;
use App\Models\AdvertiseCollection;
use App\Models\AliasCollection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use MongoDB\BSON\UTCDateTime;

class AdvertiseCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collection = AdvertiseCollection::with('advertise')->orderBy('created_at', 'desc')->paginate(10);

        return Inertia::render('AdvertiseCollection/Index', [
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
        $advertise = Advertise::query()->get();
        return Inertia::render('AdvertiseCollection/EditAdd', [
            'action' => 'create',
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
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
            'pageViews' => 'required',
            'views' => 'required',
            'rpm' => 'required',
            'revenue' => 'required',
            'subscribe' => 'required',
            'advertise' => 'required',
            'date' => 'required'
        ]);

        $date = new UTCDateTime(Carbon::parse($request->date)->format('Uv'));
        $dateString = Carbon::parse($request->date)->format('Y-m-d H:i:s');

        AdvertiseCollection::create([
            'name' => $request->name,
            'status' => $request->status,
            'page_views' => $request->pageViews,
            'views' => $request->views,
            'rpm' => $request->rpm,
            'revenue' => $request->revenue,
            'subscribe' => $request->subscribe,
            'advertise_id' => $request->advertise,
            'date' => $date,
            'date_string' => $dateString
        ]);

        return redirect()->route('advertise-collection.index')->with('message', 'Advertise Collection created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdvertiseCollection  $advertiseCollection
     * @return \Illuminate\Http\Response
     */
    public function show(AdvertiseCollection $advertiseCollection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AdvertiseCollection  $advertiseCollection
     * @return \Illuminate\Http\Response
     */
    public function edit(AdvertiseCollection $advertiseCollection)
    {
        $advertise = Advertise::query()->get();
        return Inertia::render('AdvertiseCollection/EditAdd', [
            'action' => 'Edit',
            'collect' => $advertiseCollection,
            'advertises' => $advertise,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdvertiseCollection  $advertiseCollection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdvertiseCollection $advertiseCollection)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
            'pageViews' => 'required',
            'views' => 'required',
            'rpm' => 'required',
            'revenue' => 'required',
            'subscribe' => 'required',
            'advertise' => 'required',
            'date' => 'required'
        ]);

        $date = new UTCDateTime(Carbon::parse($request->date)->format('Uv'));
        $dateString = Carbon::parse($request->date)->format('Y-m-d H:i:s');

        $advertiseCollection->update([
            'name' => $request->name,
            'status' => $request->status,
            'page_views' => $request->pageViews,
            'views' => $request->views,
            'rpm' => $request->rpm,
            'revenue' => $request->revenue,
            'subscribe' => $request->subscribe,
            'advertise_id' => $request->advertise,
            'date' => $date,
            'date_string' => $dateString,
        ]);

        return redirect()->route('advertise-collection.index')->with('message', 'Advertise Collection updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdvertiseCollection  $advertiseCollection
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvertiseCollection $advertiseCollection)
    {
        $advertiseCollection->delete();

        return redirect()->route('advertise-collection.index')->with('message', 'Advertise Collection deleted successfully.');
    }

    public function import()
    {
        // return Advertise::with('collection')->get();
        $advertises = Advertise::get();
        return Inertia::render('AdvertiseCollection/Import', [
            'advertises' => $advertises,
        ]);
    }

    public function storeImport(Request $request)
    {
        $datasheet = AliasCollection::where('advertise_id', $request->advertise)->get();


        $date = new UTCDateTime(Carbon::parse($request->date)->format('Uv'));
        $dateString = Carbon::parse($request->date)->format('Y-m-d H:i:s');
        $advertise = $request->advertise;
        $data = [];
        foreach ($request->import as $collection) {
            $alias = $datasheet->where("alias", $collection['name'])->first();
            $data[] = [
                'name' => $alias->name ?? $collection['name'],
                'status' => $collection['status'] ?? true,
                'page_views' => $collection['page_views'],
                'views' => $collection['views'],
                'rpm' => $collection['rpm'],
                'revenue' => $collection['revenue'],
                'subscribe' => $collection['subscribe'] ?? 0,
                'advertise_id' => $advertise,
                'date' => $date,
                'date_string' => $dateString,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ];
        }

        $chunks = array_chunk($data, 200);

        foreach ($chunks as $chunk) {
            AdvertiseCollection::insert($chunk);
        }


        return redirect()->route('advertise-collection.import')->with('message', 'Collection created successfully.');
    }
}
