<?php

namespace App\Http\Controllers\Api;

use App\Models\Matter;
use App\Models\ProgramImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProgramImagesController extends Controller
{
    public function carouselMap(ProgramImage  $programImage)
    {
        $programImages = $programImage->orderBy('id','desc')->limit(3)->get();
        return $programImages;
    }

    public function matters(Matter $matter)
    {
        $matters = $matter->where('open', '1')->orderBy('id', 'desc')->get();
        
        return $matters;
    }
}
