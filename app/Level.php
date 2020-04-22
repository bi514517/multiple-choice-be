<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Level extends Model
{
    public static function getType()
    {
        $query = DB::table('question_level')->get();
        return $query ;
    }    
}
