<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Test extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::table('user_role')->insert( [
            ['user_role_name'=>'Admin'],
            ['user_role_name'=>'Teacher'],
            ['user_role_name'=>'Student'],
            //...
        ]);
        Schema::create('question_category', function (Blueprint $table) {
            $table->increments('id');
            $table->text('categoryName');
        }); 
        DB::table('question_category')->insert( [
            ['categoryName'=>'History - Geography'],
            ['categoryName'=>'Science - life'],
            ['categoryName'=>'Cultural - Social'],
            ['categoryName'=>'Esport - Sport'],
            //...
        ]);
        Schema::create('question_level', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('level')->unique();
            $table->text('levelName');
        }); 
        DB::table('question_level')->insert( [
            ['levelName'=>'easy','level'=>1],
            ['levelName'=>'amateur','level'=>2],
            ['levelName'=>'medium','level'=>3],
            ['levelName'=>'hard','level'=>4],
            ['levelName'=>'very hard','level'=>5],
            ['levelName'=>'unfair','level'=>6],
            //...
        ]);
        Schema::create('question_type', function (Blueprint $table) {
            $table->increments('id');
            $table->text('typeName');
        }); 
        DB::table('question_type')->insert( [
            ['typeName'=>'Single answer question'],
            ['typeName'=>'Multiple answer question'],
            ['typeName'=>'Typing answer question'],
            //...
        ]);
        Schema::create('question', function (Blueprint $table) {
            $table->increments('id');
            $table->text('question');
            $table->bigInteger('userId')->unsigned();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->integer('typeId')->unsigned()->default(1);
            $table->integer('levelId')->nullable()->unsigned();
            $table->integer('categoryId')->nullable()->unsigned();
            $table->boolean('publish')->nullable()->default(true);
            // reference
            $table->foreign('userId')->references('id')->on('users');
            $table->foreign('typeId')->references('id')->on('question_type');
            $table->foreign('levelId')->references('id')->on('question_level');
            $table->foreign('categoryId')->references('id')->on('question_category');
        });
        Schema::create('answer', function (Blueprint $table) {
            $table->increments('id');
            $table->text('answerContent');
            $table->integer('questionId')->unsigned();
            $table->boolean('isTrue');
            // reference
            $table->foreign('questionId')->references('id')->on('question');
        });

        Schema::create('test_subject', function (Blueprint $table) {
            $table->increments('id');
            $table->text('test_subject_name');
            $table->text('test_subject_description');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->bigInteger('userId')->unsigned();
            $table->boolean('publish')->nullable()->default(true);
            $table->bigInteger('duration')->default(5400000);
            //
            $table->foreign('userId')->references('id')->on('users');
        });

        Schema::create('test_subject_question', function (Blueprint $table) {
            $table->integer('questionId')->unsigned();
            $table->integer('test_subject_id')->unsigned();
            
            // reference
            $table->foreign('questionId')->references('id')->on('question');
            $table->foreign('test_subject_id')->references('id')->on('test_subject');
        });

        Schema::create('test_subject_result', function (Blueprint $table) {
            $table->bigInteger('userId')->unsigned();
            $table->integer('test_subject_id')->unsigned();
            $table->json('result')->nullable();
            $table->json('questions')->nullable();
            $table->json('userAnswer')->nullable();
            $table->text('appreciate')->nullable();
            $table->float('mark')->nullable();
            $table->integer('total')->nullable();
            $table->integer('correct')->nullable();
            $table->timestamp('created_at')->useCurrent();
            // reference
            $table->foreign('userId')->references('id')->on('users');
            $table->foreign('test_subject_id')->references('id')->on('test_subject');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
