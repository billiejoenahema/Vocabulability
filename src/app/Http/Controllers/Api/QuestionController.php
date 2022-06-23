<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * 問題一覧を取得する。
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::all();

        return QuestionResource::collection($questions);
    }

    /**
     * 問題を追加する。
     *
     * @param StoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $question = DB::transaction(function () use ($request) {
            $question = Question::create([
                'word' => $request->word,
                'correct_answer' => $request->correct_answer,
                'example' => $request->example,
            ]);
            return $question;
        });

        return new QuestionResource($question);
    }

    /**
     * 問題を更新する。
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        $data = $request->all();

        $question = DB::transaction(function () use ($data, $question) {
            $question->fill($data)->save();
            return $question;
        });

        return new QuestionResource($question);
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
