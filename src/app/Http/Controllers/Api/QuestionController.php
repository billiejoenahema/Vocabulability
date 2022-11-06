<?php

namespace App\Http\Controllers\Api;

use App\Enums\ResponseEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Http\Requests\Question\IndexRequest;
use App\Http\Requests\Question\SaveRequest;
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
        $query = Question::query();

        if (isset($request['keyword'])) {
            $query->where('word', 'like', "%{$request['keyword']}%");
        }
        if (isset($request['filter'])) {
            $query->where('word', 'like', "{$request['filter']}%");
        }

        $order = $request->getSortDirection();
        $column = $request->getSortColumn();
        if ($column) {
            $query->sortByColumn($column, $order);
        } else {
            $query->sortByWordAsc();
        }
        $questions = $query->get();

        return QuestionResource::collection($questions);
    }

    /**
     * 問題を追加する。
     *
     * @param SaveRequest $request
     *
     * @return JsonResponse
     */
    public function store(SaveRequest $request): JsonResponse
    {
        $data = $request->all();
        DB::transaction(function () use ($data) {
            Question::create($data);
        });

        return response()->json(['message' => ResponseEnum::CREATED->value], Response::HTTP_CREATED);
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

        return response()->json(['message' => ResponseEnum::CREATED->value], Response::HTTP_CREATED);
    }

    /**
     * 問題を更新する。
     *
     * @param SaveRequest  $request
     * @param  Question  $question
     * @return JsonResponse
     */
    public function update(SaveRequest $request, Question $question): JsonResponse
    {
        $data = $request->all();
        DB::transaction(function () use ($data, $question) {
            $question->fill($data)->save();
        });

        return response()->json(['message' => ResponseEnum::UPDATED->value], Response::HTTP_OK);
    }

    /**
     * 問題を削除する。
     *
     * @param  Question  $question
     * @return JsonResponse
     */
    public function destroy(Question $question): JsonResponse
    {
        $question->delete();

        return response()->json(['message' => ResponseEnum::DELETED->value], Response::HTTP_OK);
    }
}
