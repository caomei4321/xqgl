<?php

namespace App\Http\Controllers\Admin;

use App\Models\Version;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VersionController extends Controller
{
    public function index(Version $version)
    {
        $versions = $version->paginate(15);
        return view('admin.version.index', compact('versions'));
    }

    public function create(Version $version)
    {
        return view('admin.version.create', compact('version'));
    }

    public function store(Request $request, Version $version)
    {
        $version->fill($request->only(['name', 'version_number', 'version_url', 'description']));
        $version->save();
        return redirect()->route('admin.version.index');
    }

    public function destroy(Version $version)
    {
        $version->delete();
        return response()->json([
            'status' => 1,
            'msg' => '删除成功'
        ]);
    }
}
