<?php

namespace App\Http\Controllers\Admin;

use App\Models\GovernanceStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GovernanceStandardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GovernanceStandard $governanceStandard)
    {
        $governanceStandards = $governanceStandard->paginate(10);
        return view('admin.governanceStandard.index', compact('governanceStandards'));
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
    public function store(Request $request, GovernanceStandard $governanceStandard)
    {
        //dd($request->all());
        $governanceStandard->fill($request->all());
        $governanceStandard->save();
        return redirect()->route('admin.governanceStandard.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(GovernanceStandard $governanceStandard)
    {
        return response()->json([
            'status' => 1,
            'data' => $governanceStandard]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GovernanceStandard $governanceStandard)
    {
        $governanceStandard->update($request->all());
        return redirect()->route('admin.governanceStandard.index');
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
