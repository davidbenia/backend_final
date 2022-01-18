<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Country::select('id', 'name as text')->where('name', 'LIKE', '%' . $request->input('q') . '%')->take(20)->get();

        return ["results" => $data];
    }

    public function tracked(Request $request)
    {
        return $request->user()->trackedCountries()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $country = Country::findOrFail($request->id);
        $tracked = $request->user()->trackedCountries();

        foreach ($tracked->get() as $entry) {
            if ($country->name == $entry->name) {
                return null;
            }
        }

        $tracked->attach($country->id);
        $request->user()->save();

        return ["status" => "success"];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $country = Country::findOrFail($id);
        $tracked = $request->user()->trackedCountries();

        foreach ($tracked->get() as $entry) {
            if ($country->name == $entry->name) {

                $tracked->detach($country->id);
                $request->user()->save();

                return ["status" => "success"];
            }
        }

        return null;
    }
}
