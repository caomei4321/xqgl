<?php

namespace App\Http\Controllers\Admin;

use App\Models\Situation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SituationsController extends Controller
{
    public function index(Situation  $situation)
    {
        $situations = Situation::with(['Matter', 'User', 'Category'])->paginate(10);

        return view('admin.situation.index', compact('situations'));
    }
}
