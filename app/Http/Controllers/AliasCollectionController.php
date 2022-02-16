<?php

namespace App\Http\Controllers;

use App\Models\Advertise;
use App\Models\AliasCollection;
use App\Models\Website;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AliasCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aliasCollection = AliasCollection::orderBy('created_at', 'desc')->paginate(10);

        return Inertia::render('AliasCollection/Index', [
            'list' => $aliasCollection,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $advertises = Advertise::query()->get();
        $websites = Website::query()->get();
        return Inertia::render('AliasCollection/EditAdd', [
            'action' => 'Create',
            'advertises' => $advertises,
            'websites' => $websites,
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
            'alias' => 'required',
            'advertiseId' => 'required',
            'websiteId' => 'required',
        ]);

        $website = Website::find($request->websiteId);

        AliasCollection::create([
            'name' => $website->alias,
            'alias' => $request->alias,
            'advertise_id' => $request->advertiseId,
            'website_id' => $request->websiteId,
        ]);

        return redirect()->route('alias-collection.index')->with('message', 'Alias Collection created succesfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AliasCollection  $aliasCollection
     * @return \Illuminate\Http\Response
     */
    public function show(AliasCollection $aliasCollection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AliasCollection  $aliasCollection
     * @return \Illuminate\Http\Response
     */
    public function edit(AliasCollection $aliasCollection)
    {

        $advertises = Advertise::query()->get();
        $websites = Website::query()->get();
        return Inertia::render('AliasCollection/EditAdd', [
            'action' => 'Edit',
            'aliasCollection' => $aliasCollection,
            'advertises' => $advertises,
            'websites' => $websites,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AliasCollection  $aliasCollection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AliasCollection $aliasCollection)
    {
        $this->validate($request, [
            'alias' => 'required',
            'advertiseId' => 'required',
            'websiteId' => 'required',
        ]);


        $website = Website::find($request->websiteId);

        $aliasCollection->update([
            'name' => $website->alias,
            'alias' => $request->alias,
            'advertise_id' => $request->advertiseId,
            'website_id' => $request->websiteId,
        ]);

        return redirect()->route('alias-collection.index')->with('message', 'Alias Collection updated succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AliasCollection  $aliasCollection
     * @return \Illuminate\Http\Response
     */
    public function destroy(AliasCollection $aliasCollection)
    {
        $aliasCollection->delete();

        return redirect()->route('alias-collection.index')->with('message', 'Alias Collection deleted succesfully');
    }
}
