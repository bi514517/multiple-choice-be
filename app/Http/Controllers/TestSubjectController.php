<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TestSubject;
use App\TestSubjectQuestion;
use App\Question;
use Illuminate\Support\Facades\Validator;


class TestSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = TestSubject::getTestSubjectList($request->user()->id);
        $status = isset($data);
        if(!$status) 
        {
            return response()->json([
                'status' => $status,
                'data' => [
                ],
                'message' => ''   
            ]);
        }
        $result = [];
        foreach ($data as $value) {
            $tmp = [
                $value->id,
                $value->test_subject_name,
                $value->test_subject_description,
                $value->question ? count($value->question) : 0,
            ];
            array_push($result, $tmp);
        }
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
        $validator = Validator::make($request->subject, [
            'test_subject_name' => 'required',
            'test_subject_description' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 404);
        }
        $user = $request->user();
        $arr = ['userId'=>$user->id];
        foreach ($request->subject as $param_name => $param_val) {
            $arr[$param_name] = $param_val;
        }
        $id = TestSubject::create($arr);
        $arr = [];
        foreach ($request->question as $questionId) {
            array_push($arr,['test_subject_id' => $id,'questionId' => $questionId]);
        }
        TestSubjectQuestion::insert($arr);
        return response()->json([
            'status' => true,
            'message' => $id
        ], 201);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getQuestionBySubjectId($id)
    {
        return response()->json([
            'status' => true,
            'data' => Question::getByTestSubjectId($id),
            'message' => $id
        ], 201);
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
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 404);
        }
        $user = $request->user();
        $ownerId = TestSubject::getById($id)->userId;
        if (strcmp($ownerId,$user->id)) {
            return response()->json([
                'status' => false,
                'message' => 'you are not the owner (owner id '.$ownerId.')'
            ], 404);
        }
        $arr = ['userId'=>$user->id];
        foreach ($request->subject as $param_name => $param_val) {
            $arr[$param_name] = $param_val;
        }
        TestSubject::edit($id, $arr);
        TestSubjectQuestion::delBySubjectId($id);
        $arr = [];
        foreach ($request->question as $questionId) {
            array_push($arr,['test_subject_id' => $id,'questionId' => $questionId]);
        }
        TestSubjectQuestion::insert($arr);
        return response()->json([
            'status' => true,
            'message' => $id
        ], 201);
        return response()->json([
            'status' => true,
            'message' => $id
        ], 201);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateQues(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TestSubject::destroy($id);
    }
}
