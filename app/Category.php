<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    public static function getCategory()
    {
        $query = DB::table('question_category')->get();
        return $query ;
    }    
}
