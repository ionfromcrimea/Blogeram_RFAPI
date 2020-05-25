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
//    public function toArray($request)
//    {
//        return parent::toArray($request);

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
                    'data' => $this->news->map(function($news) {
                        return [
                            'id' => $news->id,
                            'type' => 'news',
                        ];
                    })
                ],
            ]
        ];
    }
}
