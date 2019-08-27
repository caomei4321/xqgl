<?php
namespace App\Observers;

use App\Models\Matter;
use Illuminate\Support\Facades\DB;

class MatterObserver
{
    public function deleted(Matter $matter)
    {
        DB::table('user_has_matters')->where('matter_id', $matter->id)->delete();
    }
}