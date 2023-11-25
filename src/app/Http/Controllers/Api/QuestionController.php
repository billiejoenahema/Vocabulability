<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\ResponseMessage;
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
    private const PER_PAGE = 10;
    private const PER_PAGE_FOR_RANDOM = 100;

    /**
     * 問題一覧を取得する。
     *
     * @param IndexRequest $request
     */
    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        $query = Question::query();
        // 検索
        if (isset($request['keyword'])) {
            $query->where('word', 'like', "%{$request['keyword']}%");
        }
        if (isset($request['filter'])) {
            $query->where('word', 'like', "{$request['filter']}%");
        }
        // ソート
        $query->sort($request);
        // ページング
        $questions = $query->paginate(self::PER_PAGE);

        return QuestionResource::collection($questions);
    }
    /**
     * ランダムな並びの問題一覧を取得する。
     */
    public function randomIndex(): AnonymousResourceCollection
    {
        $query = Question::query();
        $query->randomSort();
        $questions = $query->paginate(self::PER_PAGE_FOR_RANDOM);

        return QuestionResource::collection($questions);
    }

    /**
     * 問題を追加する。
     *
     * @param SaveRequest $request
     */
    public function store(SaveRequest $request): JsonResponse
    {
        $data = $request->all();
        DB::transaction(static function () use ($data) {
            Question::create($data);
        });

        return response()->json(['message' => ResponseMessage::CREATED->value], Response::HTTP_CREATED);
    }

    /**
     * CSVファイルから問題を追加する。
     *
     * @param ImportRequest $request
     */
    public function importCSV(ImportRequest $request): JsonResponse
    {
        Excel::import(new QuestionImport, $request->file('file'));

        return response()->json(['message' => ResponseMessage::CREATED->value], Response::HTTP_CREATED);
    }

    /**
     * 問題を更新する。
     *
     * @param SaveRequest $request
     * @param Question $question
     */
    public function update(SaveRequest $request, Question $question): JsonResponse
    {
        $data = $request->all();
        DB::transaction(static function () use ($data, $question) {
            $question->fill($data)->save();
        });

        return response()->json(['message' => ResponseMessage::UPDATED->value], Response::HTTP_OK);
    }

    /**
     * 問題を削除する。
     *
     * @param Question $question
     */
    public function destroy(Question $question): JsonResponse
    {
        $question->delete();

        return response()->json(['message' => ResponseMessage::DELETED->value], Response::HTTP_OK);
    }
}
