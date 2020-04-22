<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class answer extends Model
{
    public static function getAnswers($questionId)
    {
        $query = DB::table('answer');
        if(isset($questionId)) {
            $query = $query->where('questionId',$questionId);
            return $query->get();
        }
        return null;
    }    
    public static function getTrueAnswers($questionId)
    {
        $query = DB::table('answer');
        if(isset($questionId)) {
            $query = $query->where('questionId',$questionId)
                            ->where('isTrue',true);
            return $query->get();
        }
        return null;
    }    

    public static function checkCorrectAnswer($questionId, $answerArray)
    {
        $correctAnswers = answer::getTrueAnswers($questionId);
        $isCorrect = count($correctAnswers) == count($answerArray);
        foreach ($answerArray as $answer) {
            $isFound = false;
            foreach ($correctAnswers as $correct) {   
                if(isset($answer->answerId)){                    
                    if($answer->answerId == $correct->id) {
                        $isFound = true;
                    }
                }
                else {
                    if($answer->answerContent == $correct->answerContent) {
                        $isFound = true;
                    }
                }
            }
            $isCorrect = $isCorrect && $isFound;
        }
        return $isCorrect;
    }   

    public static function checkCorrectAnswers($answers)
    {
        $resultArray = [];
        $answers = json_decode(json_encode($answers));
        $countCorrect = 0;
        $count = 0;
        foreach ($answers as $key => $answer) {
            $result = [];
            $isCorrect = answer::checkCorrectAnswer($key, $answer);
            if($isCorrect)
            {
                $countCorrect++;
            }    
            $count++;
            $result['isCorrect'] = $isCorrect;
            $result['correctAnswers'] = answer::getTrueAnswers($key);
            $resultArray[$key] = $result;
        }
        $mark = number_format((float)$countCorrect / $count * 10, 1, '.', '');
        $result = [];
        $appreciate = ["noob","too bad","not bad","nice","very well","excellent"];
        $result['appreciate'] = $appreciate[round($mark /2)];
        $result['mark'] = $mark;
        $result['data'] = $resultArray;
        $result['total'] = $count;
        $result['correct'] = $countCorrect;
        return json_decode(json_encode($result));
    }   

    public static function create($request) 
    {
        return DB::table('answer')->insertGetId($request);
    }

    public static function deleteAnswerByQuestionId($questionId){
        return DB::table('answer')->where('questionId', $questionId)->delete();
    }

}
