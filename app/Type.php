<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Type extends Model
{
    public static function getType()
    {
        $query = DB::table('question_type')->get();
        return $query ;
    }    
}
