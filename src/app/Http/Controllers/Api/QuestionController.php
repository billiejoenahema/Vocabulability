<?php

namespace App\Http\Controllers\Api;

use App\Consts\QuestionConst;
use App\Http\Controllers\Controller;
use App\Http\Requests\Question\ImportRequest;
use App\Http\Requests\Question\IndexRequest;
use App\Http\Requests\Question\StoreRequest;
use App\Http\Requests\Question\UpdateRequest;
use App\Http\Resources\QuestionResource;
use App\Imports\QuestionImport;
use App\Models\Question;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
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
        $filter = $request['filter'] ?? null;

        $query = Question::when($keyword, function ($query, $keyword) {
            return $query->where('word', 'like', "%{$keyword}%");
        })->when($filter, function ($query, $filter) {
            return $query->where('word', 'like', "{$filter}%");
        });

        $questions = $query->orderBy('word', 'asc')->get();

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

        return response()->json(['message' => QuestionConst::CREATED_MESSAGE], Response::HTTP_CREATED);
    }

    /**
     * CSVファイルから問題を追加する。
     *
     * @param ImportRequest $request
     *
     * @return JsonResponse
     */
    public function importCSV(ImportRequest $request): JsonResponse
    {

        Excel::import(new QuestionImport, $request->file('file'));

        return response()->json(['message' => QuestionConst::CREATED_MESSAGE], Response::HTTP_CREATED);
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

        return response()->json(['message' => QuestionConst::UPDATED_MESSAGE], Response::HTTP_OK);
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

        return response()->json(['message' => QuestionConst::DELETED_MESSAGE], Response::HTTP_OK);
    }
}
