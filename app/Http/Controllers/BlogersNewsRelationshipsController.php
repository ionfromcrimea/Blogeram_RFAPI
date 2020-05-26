<?php

namespace App\Http\Controllers;

use App\Bloger;
use App\Http\Requests\BlogersNewsRelationshipsRequest;
use App\Http\Resources\NewsIdentifierResource;
use Illuminate\Http\Request;

class BlogersNewsRelationshipsController extends Controller

{
    public function index(Bloger $bloger)
    {
        return NewsIdentifierResource::collection($bloger->news);
    }

    public function update(BlogersNewsRelationshipsRequest $request, Bloger $bloger)
    {
        $ids = $request->input('data.*.id');
        $bloger->news()->sync($ids);
        return response(null, 204);
    }
}
