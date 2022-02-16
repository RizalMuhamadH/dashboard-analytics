<?php

namespace App\Http\Controllers;

use App\Models\Advertise;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdvertiseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $advertisements = Advertise::orderBy('created_at', 'desc')->paginate(10);

        return Inertia::render('Advertise/Index', [
            'list' => $advertisements,
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Advertise/EditAdd', [
            'action' => 'Create'
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
            'name' => 'required'
        ]);

        Advertise::create([
            'name' => $request->name
        ]);

        return redirect()->route('advertisements.index')->with('message', 'Advertisement created succesfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Advertise  $advertise
     * @return \Illuminate\Http\Response
     */
    public function show(Advertise $advertise)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Advertise  $advertise
     * @return \Illuminate\Http\Response
     */
    public function edit(Advertise $advertisement)
    {
        return Inertia::render('Advertise/EditAdd', [
            'advertise' => $advertisement,
            'action' => 'Edit'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Advertise  $advertise
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Advertise $advertise)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $advertise->update([
            'name' => $request->name
        ]);

        return redirect()->route('advertisements.index')->with('message', 'Advertisement updated succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Advertise  $advertise
     * @return \Illuminate\Http\Response
     */
    public function destroy(Advertise $advertise)
    {
        $advertise->delete();

        return redirect()->route('advertisements.index')->with('message', 'Advertisement deleted succesfully');
    }
}
