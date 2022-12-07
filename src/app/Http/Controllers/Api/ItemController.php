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
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;
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
        $query = Item::query()->with('precedents');
        $query->searchCondition($request);
        $order = $request->getSortDirection();
        $column = $request['column'] ?? null;

        if ($column && $column !== 'precedent') {
            $query->sortByColumn($column, $order);
        } else {
            $query->sortByIdDesc();
        }
        $items = $query->get();

        if ($column === 'precedent' && $request['created_at_from']) {
            $createdDate = Carbon::parse($request['created_at_from'])->format('Y-m-d');
            if ($order === 'asc') {
                $items = collect($items)->sortBy(function ($item) use ($createdDate) {
                    return  $item['precedents']->where('created_at', '<=', $createdDate)[0]['name'];
                })->values();
            } else {
                $items = collect($items)->sortByDesc(function ($item) use ($createdDate) {
                    return  $item['precedents']->where('created_at', '<=', $createdDate)[0]['name'];
                })->values();
            }
        }


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

        return response()->json(['message' => ResponseEnum::CREATED->value], Response::HTTP_CREATED);
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

        return response()->json(['message' => ResponseEnum::CREATED->value], Response::HTTP_CREATED);
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

        return response()->json(['message' => ResponseEnum::UPDATED->value], Response::HTTP_OK);
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

        return response()->json(['message' => ResponseEnum::DELETED->value], Response::HTTP_OK);
    }
}
