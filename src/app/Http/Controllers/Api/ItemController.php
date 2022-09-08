<?php

namespace App\Http\Controllers\Api;

use App\Enums\ResponseEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Item\SaveRequest;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Models\Precedent;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ItemController extends Controller
{
    /**
     * 項目一覧を取得する。
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $query = Item::query();
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
            $item = Item::create([
                'name' => $data['name'],
                'name_kana' => $data['name_kana'],
                'category' => $data['category'],
            ]);
            $item->precedents()->createMany($data['precedents']);
        });

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
            foreach ($data['precedents'] as $precedent) {
                Precedent::updateOrCreate(['id' => $precedent['id']], $precedent);
            }
        });

        return response()->json(['message' => ResponseEnum::ITEM_UPDATED->value], Response::HTTP_OK);
    }

    /**
     * 項目を削除する。
     *
     * @param  Item $item
     * @return JsonResponse
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json(['message' => ResponseEnum::ITEM_DELETED->value], Response::HTTP_OK);
    }
}
