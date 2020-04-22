<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::options('{any}', function () {
    return response('OK', \Illuminate\Http\Response::HTTP_NO_CONTENT)
          ->header('Access-Control-Allow-Origin', implode(',', ['*']))
          ->header('Access-Control-Allow-Methods', implode(',', [
            'POST',
            'GET',
            'OPTIONS',
            'PUT',
            'PATCH',
            'DELETE',
        ]))
          ->header('Access-Control-Allow-Headers', implode(',',  [
            'Content-Type',
            'X-Auth-Token',
            'Origin',
            'Authorization',
        ]));
});

Route::group([
    'prefix' => 'auth',
    'middleware' => ['modeheader']
    ], function () {
        Route::post('login', 'AuthController@login');
        Route::post('signup', 'AuthController@signup');
        Route::group([
            'middleware' => ['modeheader','auth:api']
        ], function() {
            Route::get('logout', 'AuthController@logout');
            Route::post('update', 'AuthController@update');
            Route::get('user', 'AuthController@user');
        });
    }
);

Route::group([
    'prefix' => 'public',
    'middleware' => ['modeheader','auth:api']
    ], function () {
        Route::post('getQuestion', 'TestingController@getQuestion');
        Route::get('getQuestion/{id}', 'TestingController@getQuestionBySubjectId');
        Route::post('getAnwser', 'AnswerController@getAnswer');
        Route::post('getTrueAnswers', 'AnswerController@getTrueAnswers');
        Route::post('checkCorrectAnswers', 'AnswerController@checkCorrectAnswers');
        Route::get('getTestSubjectList', 'TestingController@index');
    }
);

Route::group([
    'prefix' => 'question',
    'middleware' => ['modeheader','auth:api']
    ], function () {
        Route::post('create', 'QuestionController@create');
        Route::post('createExcel', 'QuestionController@createExcel');
        Route::post('edit/{id}', 'QuestionController@edit');
        Route::get('delete/{id}', 'QuestionController@destroy');
        Route::post('getQuestionData', 'QuestionController@index');
        Route::get('getTypes', 'QuestionController@getType');
        Route::get('getLevels', 'QuestionController@getLevel');
        Route::get('getCategories', 'QuestionController@getCategory');
    }
);

Route::group([
    'prefix' => 'test_subject',
    'middleware' => ['modeheader','auth:api']
    ], function () {
        Route::post('create', 'TestSubjectController@create');
        Route::post('edit/{id}', 'TestSubjectController@edit');
        Route::get('delete/{id}', 'TestSubjectController@destroy');
        Route::get('getQuestion/{id}', 'TestSubjectController@getQuestionBySubjectId');
        Route::post('getTestSubjectList', 'TestSubjectController@index');
    }
);
