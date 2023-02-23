<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artist;
use Illuminate\Support\Facades\Validator;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $artist = Artist::with('song')->get();
        if (is_null($artist)) {
            return response()->json('Not found any data!', 404);
        }
        return response()->json($artist, 200);
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
        $rules = [
            'name'  => 'required|min:2'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $create = Artist::create($request->all());
        if (is_null($create)) {
            return response()->json('Somthing not correct for this create artist please try again!', 404);
        }
        return response()->json($create, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $artist = Artist::find($id);
        if (is_null($artist)) {
            return response()->json('Artist not found!', 404);
        }
        return response()->json($artist, 200);
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
        $artist = Artist::find($id);
        if (is_null($artist)) {
            return response()->json('Somthing not correct for this update artist please try again!', 404);
        }
        $artist->update($request->all());
        return response()->json($artist, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $artist = Artist::find($id);
        if (is_null($artist)) {
            return response()->json('deleted failed!', 404);
        }
        $artist->delete();
        return response()->json(null, 204);
    }
}
