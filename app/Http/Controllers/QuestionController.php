<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\answer;
use App\Type;
use App\Level;
use App\Category;
use App\TestSubjectQuestion;
use Illuminate\Support\Facades\Validator;


class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Question::getQuestionList($request);
        $result = [];
        $status = isset($data);
        if(!$status) 
            return response()->json([
                'status' => $status,
                'data' => [
                    'data' => [],
                    'table' => []
                ],
                'message' => ''   
            ]);
        foreach ($data as $value) {
            $tmp = [
                $value->id,
                $value->question,
                $value->categoryName,
                $value->levelName,
                $value->first_name.$value->last_name
            ];
            array_push($result, $tmp);
        }
        $status = isset($data);
        return response()->json([
            'status' => $status,
            'data' => [
                'data' => $data,
                'table' => $result
            ],
            'message' => ''   
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->question, [
            'question' => 'required',
            'typeId' => 'required',
            'levelId' => 'required',
            'categoryId' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 404);
        }
        $user = $request->user();
        $arr = ['userId'=>$user->id];
        foreach ($request->question as $param_name => $param_val) {
            $arr[$param_name] = $param_val;
        }
        $id = Question::create($arr);
        // add answer
        foreach ($request->answers as $answer) {
            $arr = ['questionId' => $id];
            foreach ($answer as $param_name => $param_val) {
                $arr[$param_name] = $param_val;
            }
            answer::create($arr);
        }
        return response()->json([
            'status' => true,
            'message' => "Successful"
        ], 201);
    }

    public function createExcel(Request $request)
    {
        $user = $request->user();
        $success = [];
        $error = [];
        if(isset($request->subjectId)){
            TestSubjectQuestion::delBySubjectId($request->subjectId);
        }
        foreach ($request->data as $question) {

                $validator = Validator::make($question, [
                    'question' => 'required',
                    'typeId' => 'required',
                ]);
            
                if ($validator->fails()) {
                    continue;
                }
                $arr = [
                    'userId'=>$user->id,
                    'question'=>$question['question'],
                    'typeId'=>$question['typeId']
                ];
                $id = Question::create($arr);
                // add answer
                foreach ($question['answer'] as $answer) {
                    $arr = ['questionId' => $id];
                    foreach ($answer as $param_name => $param_val) {
                        $arr[$param_name] = $param_val;
                    }
                    answer::create($arr);
                }
                // nếu có chọn subject thì cho hết vào subject
                if(isset($request->subjectId)){
                    TestSubjectQuestion::insert(['test_subject_id' => $request->subjectId,'questionId' => $id]);
                }
                array_push($success,$user->question);

        }
        return response()->json([
            'status' => true,
            'message' => implode(" ",$error)
        ], 201);
    }

    public function getType()
    {
        return response()->json([
            'status' => true,
            'data' => Type::getType(),
            'message' => ''
        ], 200);
    }

    public function getLevel()
    {
        return response()->json([
            'status' => true,
            'data' => Level::getType(),
            'message' => ''
        ], 200);
    }

    public function getCategory()
    {
        return response()->json([
            'status' => true,
            'data' => Category::getCategory(),
            'message' => ''
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $validator = Validator::make($request->question, [
            'question' => 'min:10',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 404);
        }
        $user = $request->user();
        $ownerId = Question::getById($id)->userId;
        if (strcmp($ownerId,$user->id)) {
            return response()->json([
                'status' => false,
                'message' => 'you are not the owner (owner id '.$ownerId.')'
            ], 404);
        }
        $arr = ['userId'=>$user->id];
        foreach ($request->question as $param_name => $param_val) {
            $arr[$param_name] = $param_val;
        }
        Question::edit($id, $arr);
        answer::deleteAnswerByQuestionId($id);
        // add answer
        foreach ($request->answers as $answer) {
            $arr = ['questionId' => $id];
            foreach ($answer as $param_name => $param_val) {
                if(strcmp($param_name,"id") != 0)
                    $arr[$param_name] = $param_val;
            }
            answer::create($arr);
        }
        return response()->json([
            'status' => true,
            'message' => "Successful"
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Question::destroy($id);
    }
}
