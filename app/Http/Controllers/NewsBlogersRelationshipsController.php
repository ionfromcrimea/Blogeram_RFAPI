<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsBlogersRelationshipsRequest;
use App\Http\Resources\BlogersIdentifierResource;
use App\News;
use Illuminate\Http\Request;

class NewsBlogersRelationshipsController extends Controller

{
    public function index(News $news)
    {
        return BlogersIdentifierResource::collection($news->blogers);
    }

    public function update(NewsBlogersRelationshipsRequest $request, News $news)
    {
        $ids = $request->input('data.*.id');
        $news->blogers()->sync($ids);
        return response(null, 204);
    }
}
