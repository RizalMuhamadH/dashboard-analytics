<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Type;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->search) {
            $websites = Website::where('name', 'like', '%' . request()->search . '%')->orderBy('created_at', 'desc')->paginate(10)->appends(array('search' => request()->search));
        } else {
            $websites = Website::orderBy('created_at', 'desc')->paginate(10);
        }
        // $websites = Website::where('name', 'like', '%'.$request->search.'%')->orderBy('id', 'desc')->paginate(10);
        // $websites = Website::orderBy('id', 'desc')->paginate(10);

        return Inertia::render('Website/Index', [
            'list' => $websites,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Website/EditAdd', [
            'action' => 'Create',
            // 'websites' => Website::query()->select(['wid as id', 'name as text'])->get()
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
        $request->validate([
            'name' => 'required',
            'url' => 'required',
            'alias' => 'required',
            'wid' => 'required|integer',
        ]);

        $website = Website::create([
            'name' => $request->name,
            'url' => $request->url,
            'alias' => $request->alias,
            'wid' => $request->wid,
            'is_active' => $request->isActive,
            'path' => $request->path,

        ]);

        return redirect()->back()->with('message', 'Website created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function show(Website $website)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function edit(Website $website)
    {
        return Inertia::render('Website/EditAdd', [
            'action' => 'Edit',
            // 'websites' => Website::query()->select(['wid as id', 'name as text'])->get(),
            'data' => Website::with(['image', 'extras'])->find($website->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Website $website)
    {
        $request->validate([
            'name' => 'required',
            'url' => 'required',
            'alias' => 'required',
            'wid' => 'required|integer',
        ]);

        $website->update([
            'name' => $request->name,
            'url' => $request->url,
            'alias' => $request->alias,
            'wid' => $request->wid,
            'is_active' => $request->isActive,
            'path' => $request->path,
        ]);

        return redirect()->back()->with('message', 'Website updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function destroy(Website $website)
    {
        $website->delete();

        return redirect()->back()->with('message', 'Website deleted successfully.');
    }

    public function import()
    {
        return Inertia::render('Website/Import');
    }

    public function storeImport(Request $request)
    {

        // $chunks = collect($request->import)->chunk(100);
        
        // dd($chunks[0]->toArray());

        DB::disableQueryLog();

        // $chunks = array_chunk($request->import, 100);

        // foreach ($chunks as $chunk) {
        //     Website::insert($chunk);
        // }

        $data = [];
        foreach ($request->import as $website) {
            $data[] = [
                'name' => $website['name'] ?? '',
                'url' => $website['url'] ?? '',
                'alias' => $website['alias'] ?? '',
                'wid' => $website['wid'] ?? 0,
                'path' => $website['path'] ?? null,
                'is_active' => $website['is_active'] == 1 ? true : false,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ];
        }

        $chunks = array_chunk($data, 200);

        foreach ($chunks as $chunk) {
            Website::insert($chunk);
        }

        
        return redirect()->route('websites.import')->with('message', 'Website created successfully.');
    }
}
