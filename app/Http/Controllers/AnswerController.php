<?php

namespace App\Http\Controllers;

use App\answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    
    public function getAnswer(Request $request)
    {
        $data = answer::getAnswers($request->questionId);
        $status = isset($data);
        return response()->json([
            'status' => $status,
            'data' => $data,
            'message' => $status ? 'lấy thành công' : 'không tìm thấy câu trả lời' 
        ], 201);
    }
    public function getTrueAnswers(Request $request)
    {
        $data = answer::getTrueAnswers($request->questionId);
        $status = isset($data);
        return response()->json([
            'status' => $status,
            'data' => $data,
            'message' => $status ? 'lấy thành công' : 'không tìm thấy câu trả lời' 
            
        ], 201);
    }    

    /*public static function checkCorrectAnswer(Request $request)
    {
        if(isset($request->questionId)&&isset($request->answerArray)){
            $data = answer::checkCorrectAnswer($request->questionId, $request->answerArray);
            $status = isset($data);
            return response()->json([
                'status' => $status,
                'data' => $data,
                'message' => ''
                
            ], 201);
        }
        return response()->json([
            'status' => false,
            'data' => '',
            'message' => 'không tìm thấy câu hỏi '
            
        ], 201);
    }   */

    public static function checkCorrectAnswers(Request $request)
    {
        if(isset($request->answers)){
            $data = answer::checkCorrectAnswers($request->answers);
            $status = isset($data);
            return response()->json([
                'status' => $status,
                'data' => $data,
                'message' => ''
                
            ], 201);
        }
        return response()->json([
            'status' => false,
            'data' => '',
            'message' => 'không tìm thấy câu hỏi '
            
        ], 201);
    }   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function show(answer $answer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(answer $answer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, answer $answer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(answer $answer)
    {
        //
    }
}
