<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var \App\Models\Item $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_kana' => $this->name_kana,
            'category' => $this->category,
            'precedents' => PrecedentResource::collection($this->precedents),
            'created_at' => $this->created_at,
        ];
    }
}
