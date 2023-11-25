<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Http\Requests\Item\IndexRequest;
use App\Http\Requests\Item\SaveRequest;
use App\Http\Resources\ItemResource;
use App\Imports\ItemImport;
use App\Models\Item;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ItemController extends Controller
{
    private const PER_PAGE = 10;

    /**
     * 項目一覧を取得する。
     *
     * @param IndexRequest $request
     */
    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        $query = Item::query()->with('precedents');
        // 検索
        $query->searchCondition($request);
        // ソート
        $query->sort($request);
        // ページング
        $items = $query->paginate(self::PER_PAGE);

        return ItemResource::collection($items);
    }

    /**
     * 項目を追加する。
     *
     * @param SaveRequest $request
     */
    public function store(SaveRequest $request): JsonResponse
    {
        $data = $request->all();
        DB::transaction(static function () use ($data) {
            $item = Item::create($data);
            $item->precedents()->createMany($data['precedents']);
        });

        return response()->json(['message' => ResponseMessage::CREATED->value], Response::HTTP_CREATED);
    }

    /**
     * CSVファイルから項目を追加する。
     *
     * @param ImportRequest $request
     */
    public function importCSV(ImportRequest $request): JsonResponse
    {
        Excel::import(new ItemImport, $request->file('file'));

        return response()->json(['message' => ResponseMessage::CREATED->value], Response::HTTP_CREATED);
    }


    /**
     * 項目を更新する。
     *
     * @param SaveRequest $request
     * @param Item $item
     */
    public function update(SaveRequest $request, Item $item): JsonResponse
    {
        $data = $request->all();

        DB::transaction(static function () use ($data, $item) {
            $item->fill($data)->save();
            $item->precedents()->upsert($data['precedents'], ['id']);
        });

        return response()->json(['message' => ResponseMessage::UPDATED->value], Response::HTTP_OK);
    }

    /**
     * 項目を削除する。
     *
     * @param Item $item
     */
    public function destroy(Item $item): JsonResponse
    {
        $item->delete();

        return response()->json(['message' => ResponseMessage::DELETED->value], Response::HTTP_OK);
    }
}
