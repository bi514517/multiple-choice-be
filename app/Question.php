<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\answer;
class Question extends Model
{
    public static function getById($id)
    {
        $number = 1;
        $query = DB::table('question')->where('id',$id);
        $query = $query->where('question.publish',true);
        if($query->count() > 0){
            $data = $query->first();
            return $data;
        }
        return null;
    }  

    public static function getByTestSubjectId($test_subject_id, $isMix = false)
    {
        $number = 1;
        $query = DB::table('question')
            ->leftJoin('test_subject_question', 'question.id', '=', 'test_subject_question.questionId');
        $query = $query->where('test_subject_question.test_subject_id',$test_subject_id);
        if($query->count() > 0){
            $data = $query->get();
            $result = [];
            foreach ($data as $value) {
                $value = json_decode(json_encode($value),true);
                $value['answers'] = answer::getAnswers($value['id']);
                array_push($result, $value);
            }
            if($isMix)
                shuffle($result);
            return $result;
        }
        return null;
    }  


    public static function getQuestion($request)
    {
        $number = 0;
        $data = [];
        $notIn = [];
        if(isset($request->number)) {
            $number = $request->number;
        }
        foreach ($request->levels as $value) {
            if($number > 0){
                $newNumber = $value['number'];
                $newNumber = $newNumber > $number ? $number : $newNumber;
                $query = DB::table('question');
                if(isset($request->categories)) {
                    $query = $query->whereIn('categoryId',$request->categories);
                }
                $query = $query->where('question.publish',true)->where('levelId',$value['id'])->limit($newNumber);
                $newData =  $query->get();
                foreach ($newData as $value) {
                    array_push($notIn,$value->id);
                    array_push($data, $value);
                }
                $number = $number - count($newData);
            }
            
        }
        $query = DB::table('question');
        if(isset($request->categories)) {
            $query = $query->whereIn('categoryId',$request->categories);
        }
        $query = $query->where('question.publish',true);
        $newData = $query->whereNotIn('id', $notIn)->limit($number)->get();
        foreach ($newData as $value) {
            array_push($data, $value);
        }
        $result = [];
        foreach ($data as $value) {
            $value = json_decode(json_encode($value),true);
            $value['answers'] = answer::getAnswers($value['id']);
            array_push($result, $value);
        }
        shuffle($result);
        return $result;
    }  
    
    public static function getQuestionList($request)
    {
        $number = 1;
        $query = DB::table('question')
            ->select('*','question.id','question.question','question_category.categoryName','question_level.levelName','users.first_name','users.last_name')
            ->leftJoin('question_category', 'question.categoryId', '=', 'question_category.id')
            ->leftJoin('question_level', 'question.levelId', '=', 'question_level.id')
            ->leftJoin('question_type', 'question.typeId', '=', 'question_type.id')
            ->leftJoin('users', 'question.userId', '=', 'users.id');
            if(isset($request->categoryId)) {
                $query = $query->where('categoryId',$request->categoryId);
            }
            if(isset($request->levelId)) {
                $query = $query->where('levelId',$request->levelId);
            }
            if(isset($request->typeId)) {
                $query = $query->where('typeId',$request->typeId);
            }
        $query = $query->where('question.publish',true);
        if($query->count() > 0){
            $result = [];
            $data = $query->orderBy('question.created_at', 'ASC')->get();
            foreach ($data as $value) {
                $value = json_decode(json_encode($value),true);
                $value['answers'] = answer::getAnswers($value['id']);
                array_push($result, (object)$value);
            }
            return $result;
        }
        return null;
    } 
    
    public static function create($request) 
    {
        return DB::table('question')->insertGetId($request);
    }

    public static function edit($id, $request)
    {
        DB::table('question')
            ->where('question.id', $id)
            ->update($request);
    }

    public static function destroy($id)
    {
        DB::table('question')
            ->where('question.id', $id)
            ->update(['publish'=>false]);
    }

}
