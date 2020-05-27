<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogersResource extends JsonResource
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
            'type' => 'blogers',
            'attributes' => [
                'login' => $this->login,
                'password' => $this->password,
                'status' => (string)$this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            'relationships' => [
                'news' => [
                    'links' => [
                        'self' => route(
                            'blogers.relationships.news',
                            ['bloger' => $this->id]
                        ),
                        'related' => route(
                            'blogers.news',
                            ['bloger' => $this->id]
                        ),
                    ],
                    'data' => NewsIdentifierResource::collection(
                        $this->whenLoaded('news')),
                ],
            ]
        ];
    }

    private function relations()
    {
        return [
            NewsResourceShort::collection($this->whenLoaded('news')),
        ];
    }

//    public function included($request)
//    {
//        return collect($this->relations())
//            ->filter(function ($resource) {
//                return $resource->collection !== null;
//            })
//            ->flatMap(function ($resource) use ($request) {
//                return $resource->toArray($request);
//            })
//            ;
//    }
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
