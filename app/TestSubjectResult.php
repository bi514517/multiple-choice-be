<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class TestSubjectResult extends Model
{
    public static function create($userId, $subjectId, $result, $questions, $userAnswer, $appreciate, $mark, $total, $correct) 
    {
        return DB::table('test_subject_result')->insertGetId([
            'test_subject_id'=>$subjectId,
            'userId'=>$userId,
            'result'=>$result,
            'questions'=>$questions,
            'userAnswer'=>$userAnswer,
            'appreciate'=>$appreciate,
            'mark'=>$mark,
            'total'=>$total,
            'correct'=>$correct,
            ]);
    }

  
    public static function getResults($subjectId, $userId, $topOf, $sortBy) 
    {

        $query = DB::table('test_subject_result')
            ->leftJoin('test_subject', 'test_subject.id', '=', 'test_subject_result.test_subject_id');
        if(isset($subjectId)){
            $query->where('test_subject_id',$subjectId);
        }
        if(isset($userId)){
            $query->where('test_subject_result.userId',$userId);
        }
        switch ($topOf) {
            case 'WEEK':
                $query->whereRaw('created_at < NOW() - INTERVAL 1 WEEK');
                break;
            case 'MONTH':
                $query->whereRaw('created_at < NOW() - INTERVAL 1 MONTH');
                break;
            case 'YEAR':
                $query->whereRaw('created_at < NOW() - INTERVAL 1 YEAR');
                break;        
            default:
                # code...
                break;
        }
        if(isset($sortBy)){
            $query->orderBy('test_subject_result.'.$sortBy,'DESC');
        }else {
            $query->orderBy('mark','DESC');
        }
        return $query->get();
    }
}