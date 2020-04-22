<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TestSubjectQuestion extends Model
{
    public static function delBySubjectId($test_subject_id)
    {
        $query = DB::table('test_subject_question')
            ->where('test_subject_id',$test_subject_id)->delete();
    }
    public static function insert($dataArr)
    {
        $query = DB::table('test_subject_question')->insert($dataArr);
    }
}
