<?php
namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryObserver
{
    public function deleted(Category $category)
    {
        DB::table('responsibility')->where('category_id', $category->id)->delete();
    }
}
