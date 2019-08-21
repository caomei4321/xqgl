<?php

namespace App\Http\Controllers\Admin;

use App\Models\CityPart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CityPartsController extends Controller
{
    public function index(CityPart $cityPart)
    {
        $parts = $cityPart->paginate(15);

        return view('admin.part.index', compact('parts'));
    }

    public function create(CityPart $cityPart)
    {
        return view('admin.part.create_and_edit', compact('cityPart'));
    }
}
