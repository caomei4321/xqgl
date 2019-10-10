<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProgramImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProgramImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProgramImage $programImage)
    {
        $programImages = $programImage->paginate();
        return view('admin.programImage.index', compact('programImages'));
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
    public function store(Request $request, ProgramImage $programImage)
    {

        //$path = Storage::disk('public')->putFile('miniProgramHeaderImg',$request->file('image'));
        $path = Storage::disk('public')->putFile('miniProgramHeaderImg',$request->file('image'));
        $path = '/storage/' . $path;

        $programImage->fill(['image' => $path]);
        $programImage->save();

        return response()->json(['status' => 1, 'msg' => '添加成功']);
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
    public function destroy(ProgramImage $programImage)
    {
        $programImage->delete();
        return response()->json(['status' => 1, 'msg' => '删除成功']);
    }
}
