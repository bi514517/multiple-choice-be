<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\TestSubject;


class TestingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TestSubject::getTestSubjectPublic();
        $status = isset($data);
        return response()->json([
            'status' => $status,
            'data' => $data,
            'message' => ''
        ], 201);
    }

    public function getQuestion(Request $request)
    {
        $data = Question::getQuestion($request);
        $status = isset($data);
        return response()->json([
            'status' => $status,
            'data' => $data,
            'message' => ''
            
        ], 201);
    }

    public function getQuestionBySubjectId($id)
    {
        return response()->json([
            'status' => true,
            'data' => Question::getByTestSubjectId($id,true),
            'message' => $id
        ], 201);
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
    public function edit($id)
    {
        //
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
        //
    }
}
