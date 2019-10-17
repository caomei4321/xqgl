<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Matter;
use App\Models\Responsibility;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProgramMattersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Matter $matter)
    {
        $matters = $matter->where('form',3)->orderBy('allocate', 'asc')->paginate();
        return view('admin.programMatter.index', compact('matters'));
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

    public function allocate(Matter $matter, Category $category, Responsibility $responsibility, User $user)
    {
        $categories = $category->all();
        $responsibilities = $responsibility->all();
        $users = $user->all();
        return view('admin.programMatter.allocate', compact('matter', 'categories', 'responsibilities', 'users'));
    }
}
