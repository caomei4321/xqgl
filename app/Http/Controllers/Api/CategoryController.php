<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\CategoryResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function categories()
    {
        return CategoryResource::collection(Category::all());
    }
}
