<?php

namespace App\Http\Controllers;

use App\Http\Resources\BlogersIdentifierResource;
use App\News;
use Illuminate\Http\Request;

class NewsBlogersRelationshipsController extends Controller
{
    public function index(News $news)
    {
        return BlogersIdentifierResource::collection($news->blogers);
    }
}
