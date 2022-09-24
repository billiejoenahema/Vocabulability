<?php

namespace App\Http\Controllers\Api;

use App\Enums\ResponseEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Http\Requests\Item\IndexRequest;
use App\Http\Requests\Item\SaveRequest;
use App\Http\Resources\ItemResource;
use App\Imports\ItemImport;
use App\Models\Item;
use App\Models\Precedent;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ItemController extends Controller
{
    /**
     * 項目一覧を取得する。
     *
     * @param IndexRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        $keyword = $request['keyword'] ?? null;
        $filter = $request['filter'] ?? null;

        $query = Item::when($keyword, function ($query, $keyword) {
            return $query->where('name', 'like', "%{$keyword}%");
        })->when($filter, function ($query, $filter) {
            return $query->where('name_kana', 'like', "{$filter}%");
        });
        $items = $query->orderBy('name_kana', 'asc')->get();

        return ItemResource::collection($items);
    }

    /**
     * 項目を追加する。
     *
     * @param SaveRequest $request
     * @return JsonResponse
     */
    public function store(SaveRequest $request): JsonResponse
    {
        $data = $request->all();
        DB::transaction(function () use ($data) {
            $item = Item::create($data);
            $item->precedents()->createMany($data['precedents']);
        });

        return response()->json(['message' => ResponseEnum::ITEM_CREATED->value], Response::HTTP_CREATED);
    }

    /**
     * CSVファイルから項目を追加する。
     *
     * @param ImportRequest $request
     *
     * @return JsonResponse
     */
    public function importCSV(ImportRequest $request): JsonResponse
    {
        Excel::import(new ItemImport, $request->file('file'));

        return response()->json(['message' => ResponseEnum::ITEM_CREATED->value], Response::HTTP_CREATED);
    }


    /**
     * 項目を更新する。
     *
     * @param  SaveRequest  $request
     * @param  Item $item
     * @return JsonResponse
     */
    public function update(SaveRequest $request, Item $item): JsonResponse
    {
        $data = $request->all();

        DB::transaction(function () use ($data, $item) {
            $item->fill($data)->save();
            $item->precedents()->upsert($data['precedents'], ['id']);
        });

        return response()->json(['message' => ResponseEnum::ITEM_UPDATED->value], Response::HTTP_OK);
    }

    /**
     * 項目を削除する。
     *
     * @param  Item $item
     * @return JsonResponse
     */
    public function destroy(Item $item): JsonResponse
    {
        $item->delete();

        return response()->json(['message' => ResponseEnum::ITEM_DELETED->value], Response::HTTP_OK);
    }
}
