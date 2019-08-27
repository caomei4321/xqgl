<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\MatterResource;
use App\Http\Resources\Api\UserResource;
use App\Models\Matter;
use App\Models\User;
use Illuminate\Http\Request;

class MattersController extends Controller
{
    public function userHasMatters()
    {
        return new UserResource($this->user());
    }

    public function matter(Request $request)
    {
        $matter = Matter::find($request->id);

        return new MatterResource($matter);
    }
}
