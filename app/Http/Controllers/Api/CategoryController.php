<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\CategoryResource;
use App\Models\GovernanceStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function categories()
    {

        $standards = GovernanceStandard::orderBy('created_at', 'desc')->all();

        $categories = CategoryResource::collection(Category::orderBy('created_at', 'desc')->all());
        $data = [
            'categories' => $categories,
            'standards' => $standards
        ];
        return $data;
    }
}
