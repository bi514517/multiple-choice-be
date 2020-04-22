<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Question;

class TestSubject extends Model
{
    public static function create($request) 
    {
        return DB::table('test_subject')->insertGetId($request);
    }

    public static function getTestSubjectList($id)
    {
        $query = DB::table('test_subject')
            ->select('*')
            ->where('userId',$id)
            ->where('test_subject.publish',true);
        if($query->count() > 0){
            $result = [];
            $data = $query->orderBy('test_subject.created_at', 'ASC')->get();
            foreach ($data as $value) {
                $value = json_decode(json_encode($value),true);
                $value['question'] = Question::getByTestSubjectId($value['id']);
                array_push($result, (object)$value);
            }
            return $result;
        }
        return null;
    }

    public static function getTestSubjectPublic()
    {
        $query = DB::table('test_subject')
            ->select('*')
            ->where('test_subject.publish',true);
        if($query->count() > 0){
            $result = [];
            $data = $query->orderBy('test_subject.created_at', 'DESC')->get();
            foreach ($data as $value) {
                $value = json_decode(json_encode($value),true);
                $value['question'] = Question::getByTestSubjectId($value['id']);
                array_push($result, (object)$value);
            }
            return $result;
        }
        return null;
    }

    public static function getById($id)
    {
        $number = 1;
        $query = DB::table('test_subject')->where('id',$id);
        if($query->count() > 0){
            $data = $query->first();
            return $data;
        }
        return null;
    }  

    public static function edit($id, $request)
    {
        DB::table('test_subject')
            ->where('test_subject.id', $id)
            ->update($request);
    }

    public static function destroy($id)
    {
        DB::table('test_subject')
            ->where('test_subject.id', $id)
            ->update(['publish'=>false]);
    }
}
