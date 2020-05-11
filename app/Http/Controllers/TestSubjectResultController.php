<?php

namespace App\Http\Controllers;

use App\TestSubjectResult;
use Illuminate\Http\Request;

class TestSubjectResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userId = $request->userId ? $request->user()->id: null;
        $data = TestSubjectResult::getResults($request->subjectId, $userId, $request->topOf, $request->sortBy);
        $status = isset($data);
        return response()->json([
            'status' => $status,
            'data' => $data,
            'message' => ' '
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
     * @param  \App\TestSubjectResult  $testSubjectResult
     * @return \Illuminate\Http\Response
     */
    public function show(TestSubjectResult $testSubjectResult)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TestSubjectResult  $testSubjectResult
     * @return \Illuminate\Http\Response
     */
    public function edit(TestSubjectResult $testSubjectResult)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TestSubjectResult  $testSubjectResult
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TestSubjectResult $testSubjectResult)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TestSubjectResult  $testSubjectResult
     * @return \Illuminate\Http\Response
     */
    public function destroy(TestSubjectResult $testSubjectResult)
    {
        //
    }
}
