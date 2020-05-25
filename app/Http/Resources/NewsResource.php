<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (string)$this->id,
            'type' => 'news',
            'attributes' => [
                'title' => $this->title,
                'image' => $this->image,
                'list1' => $this->list1,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            'relationships' => [
                'blogers' => [
                    'links' => [
                        'self' => route(
                            'news.relationships.blogers',
                            ['news' => $this->id]
                        ),
                        'related' => route(
                            'news.blogers',
                            ['news' => $this->id]
                        ),
                    ],
                    'data' => BlogersIdentifierResource::collection(
                        $this->blogers),
                ],
            ]
        ];
    }
}
