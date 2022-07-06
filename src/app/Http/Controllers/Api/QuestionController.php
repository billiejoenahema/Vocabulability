<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Question\IndexRequest;
use App\Http\Requests\Question\StoreRequest;
use App\Http\Requests\Question\UpdateRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class QuestionController extends Controller
{
    /**
     * 問題一覧を取得する。
     *
     * @param IndexRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        $keyword = $request['keyword'] ?? null;
        $questions = Question::when($keyword, function ($query, $keyword) {
            return $query->where('word', 'like', "%{$keyword}%");
        })->get();

        return QuestionResource::collection($questions);
    }

    /**
     * 問題を追加する。
     *
     * @param StoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
         DB::transaction(function () use ($request) {
            Question::create([
                'word' => $request->word,
                'correct_answer' => $request->correct_answer,
                'example' => $request->example,
            ]);
        });

        return response()->json(['message' => '問題を追加しました。'], Response::HTTP_CREATED);
    }

    /**
     * 問題を更新する。
     *
     * @param UpdateRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, $id): JsonResponse
    {
        $question = Question::findOrFail($id);
        $data = $request->all();

        DB::transaction(function () use ($data, $question) {
            $question->fill($data)->save();
        });

        return response()->json(['message' => '問題を更新しました。'], Response::HTTP_OK);
    }

    /**
     * 問題を削除する。
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return response()->json(['message' => '問題を削除しました。'], Response:: HTTP_OK);
    }
}
