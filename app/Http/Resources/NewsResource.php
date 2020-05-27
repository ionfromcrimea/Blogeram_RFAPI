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
                        $this->whenLoaded('blogers')),
                ],
            ]
        ];
    }

    private function relations()
    {
        return [
            BlogersResourceShort::collection($this->whenLoaded('blogers')),
        ];
    }

    public function included($request)
    {
        return collect($this->relations())
            ->filter(function ($resource) {
                return $resource->collection !== null;
            })->flatMap->toArray($request);
    }

    public function with($request)
    {
        $with = [];
        if ($this->included($request)->isNotEmpty()) {
            $with['included'] = $this->included($request);
        }
        return $with;
    }

}
